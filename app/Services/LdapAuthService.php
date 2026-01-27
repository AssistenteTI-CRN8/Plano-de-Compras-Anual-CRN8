<?php

namespace App\Services;

use App\Models\User;
use Exception;

class LdapAuthService
{
    protected $connection;
    protected $config;

    public function __construct()
    {
        $this->config = config('ldap');
    }

    /**
     * Conectar ao servidor LDAP
     */
    protected function connect()
    {
        if ($this->connection) {
            return $this->connection;
        }

        $host = $this->config['hosts'][0];
        $port = $this->config['port'];

        $this->connection = @ldap_connect($host, $port);

        if (!$this->connection) {
            throw new Exception('Não foi possível conectar ao servidor LDAP');
        }

        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->connection, LDAP_OPT_NETWORK_TIMEOUT, $this->config['timeout']);

        return $this->connection;
    }

    /**
     * Autenticar usuário via LDAP
     */
    public function authenticate(string $username, string $password): ?User
    {
        try {
            $connection = $this->connect();

            // Fazer bind administrativo primeiro
            $adminDn = $this->config['username'];
            $adminPassword = $this->config['password'];

            if (!@ldap_bind($connection, $adminDn, $adminPassword)) {
                throw new Exception('Falha na autenticação administrativa LDAP');
            }

            // Buscar usuário
            $userAttr = $this->config['user_attributes']['username'];
            $filter = "({$userAttr}={$username})";
            $baseDn = $this->config['base_dn'];

            $search = @ldap_search($connection, $baseDn, $filter);
            if (!$search) {
                return null;
            }

            $entries = ldap_get_entries($connection, $search);
            if ($entries['count'] === 0) {
                return null;
            }

            $entry = $entries[0];
            $userDn = $entry['dn'];

            // Tentar autenticar com as credenciais do usuário
            if (!@ldap_bind($connection, $userDn, $password)) {
                return null;
            }

            // Extrair informações do usuário
            $name = $entry[$this->config['user_attributes']['name']][0] ?? $username;
            $email = $entry[$this->config['user_attributes']['email']][0] ?? "{$username}@example.com";

            // Determinar role baseado nos grupos
            $role = $this->getRoleFromLdapGroups($entry);

            // Criar ou atualizar usuário no banco
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'ldap_dn' => $userDn,
                    'role' => $role,
                    'password' => bcrypt($password), // Salvar hash como fallback
                ]
            );

            return $user;

        } catch (Exception $e) {
            logger()->error('Erro na autenticação LDAP: ' . $e->getMessage());
            return null;
        } finally {
            if ($this->connection) {
                @ldap_unbind($this->connection);
                $this->connection = null;
            }
        }
    }

    /**
     * Determinar role do usuário baseado nos grupos LDAP
     */
    protected function getRoleFromLdapGroups(array $entry): string
    {
        $groupsAttr = $this->config['user_attributes']['groups'];
        $roleMapping = $this->config['role_mapping'];

        if (!isset($entry[$groupsAttr])) {
            return 'user';
        }

        $groups = $entry[$groupsAttr];
        unset($groups['count']);

        // Verificar superadmin primeiro
        foreach ($groups as $group) {
            if (isset($roleMapping[$group]) && $roleMapping[$group] === 'superadmin') {
                return 'superadmin';
            }
        }

        // Depois admin
        foreach ($groups as $group) {
            if (isset($roleMapping[$group]) && $roleMapping[$group] === 'admin') {
                return 'admin';
            }
        }

        // Por padrão, user
        return 'user';
    }
}
