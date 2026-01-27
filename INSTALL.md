# Guia de Instalação Rápida - LDAP Admin Site

## 🚀 Início Rápido

### 1. Instalar Dependências

```bash
cd ldap-admin-site
composer install
```

### 2. Configurar Ambiente

```bash
# Copiar arquivo de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 3. Configurar Banco de Dados

Edite o arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ldap_admin
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

Crie o banco de dados:
```bash
mysql -u root -p
CREATE DATABASE ldap_admin;
exit;
```

### 4. Executar Migrations

```bash
php artisan migrate
```

### 5. Popular Banco com Dados de Teste (Opcional)

```bash
php artisan db:seed
```

Isso criará 6 usuários de teste:
- **superadmin@example.com** (senha: password) - Super Administrador
- **admin@example.com** (senha: password) - Administrador
- **user@example.com** (senha: password) - Usuário Comum
- E mais 3 usuários adicionais

### 6. Configurar LDAP (se necessário)

Edite o arquivo `.env` com as configurações do seu servidor LDAP:

```env
LDAP_HOSTS=ldap://seu-servidor.com
LDAP_PORT=389
LDAP_BASE_DN=dc=exemplo,dc=com
LDAP_USERNAME=cn=admin,dc=exemplo,dc=com
LDAP_PASSWORD=senha_ldap
```

### 7. Iniciar Servidor

```bash
php artisan serve
```

Acesse: http://localhost:8000

## 🔑 Login de Teste

Se você executou o seeder, pode fazer login com:

- **Super Admin**: superadmin@example.com / password
- **Admin**: admin@example.com / password  
- **User**: user@example.com / password

> **Nota**: Para teste local, modifique o `AuthenticatedSessionController` para aceitar login com email ao invés de username LDAP.

## 📝 Modificação para Teste Local (Sem LDAP)

Se você não tem um servidor LDAP disponível para testes, edite o arquivo:
`app/Http/Controllers/Auth/AuthenticatedSessionController.php`

Altere o método `store()` para:

```php
public function store(Request $request)
{
    $request->validate([
        'username' => ['required', 'string'], // Ou 'email' para teste
        'password' => ['required', 'string'],
    ]);

    // Para teste local sem LDAP
    if (Auth::attempt([
        'email' => $request->username, // Usar email ao invés de username
        'password' => $request->password
    ], $request->boolean('remember'))) {
        $request->session()->regenerate();
        return $this->redirectBasedOnRole(Auth::user());
    }

    throw ValidationException::withMessages([
        'username' => 'As credenciais fornecidas estão incorretas.',
    ]);
}
```

E no formulário de login (`resources/views/auth/login.blade.php`), altere:
```html
<input type="text" name="username" placeholder="Email">
```

## 🎨 Estrutura de Acessos

### Usuário Comum (user)
- ✅ Dashboard pessoal
- ✅ Ver suas próprias informações

### Administrador (admin)
- ✅ Tudo do usuário comum
- ✅ Dashboard administrativo
- ✅ Ver e editar usuários comuns
- ✅ Ver e editar outros administradores
- ❌ Não pode gerenciar super admins

### Super Administrador (superadmin)
- ✅ Acesso total ao sistema
- ✅ Ver, editar e deletar qualquer usuário
- ✅ Acessar configurações do sistema
- ✅ Gerenciar todos os níveis de acesso

## 🛠️ Comandos Úteis

```bash
# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Recriar banco de dados
php artisan migrate:fresh --seed

# Ver rotas
php artisan route:list

# Criar novo usuário via tinker
php artisan tinker
>>> User::create(['name' => 'Teste', 'email' => 'teste@example.com', 'password' => bcrypt('password'), 'role' => 'user']);
```

## 📦 Estrutura de Pastas Importante

```
ldap-admin-site/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controladores
│   │   └── Middleware/      # Middlewares de autorização
│   ├── Models/              # Model User com roles
│   └── Services/            # LdapAuthService
├── config/
│   └── ldap.php            # Configurações LDAP
├── database/
│   ├── migrations/         # Estrutura do banco
│   └── seeders/            # Dados de teste
├── resources/views/
│   ├── layouts/            # Layout principal (SB Admin)
│   ├── auth/               # Tela de login
│   ├── dashboard/          # Dashboard usuário
│   ├── admin/              # Área administrativa
│   └── superadmin/         # Área super admin
└── routes/
    └── web.php             # Todas as rotas
```

## 🐛 Solução de Problemas

### Erro de conexão LDAP
- Verifique se o servidor LDAP está acessível
- Confirme as credenciais em `.env`
- Teste conectividade: `telnet seu-servidor.com 389`

### Erro ao fazer login
- Verifique se executou as migrations
- Confirme que há usuários no banco
- Limpe o cache de configuração

### Layout não carrega
- Verifique conexão com internet (SB Admin usa CDN)
- Limpe cache do navegador

## 📞 Próximos Passos

1. Configure seu servidor LDAP em produção
2. Personalize as cores e logo no layout
3. Adicione funcionalidades específicas do seu negócio
4. Configure SSL/TLS para produção
5. Implemente logs de auditoria

## 🎯 Acessos Rápidos Após Login

- **Dashboard**: http://localhost:8000/dashboard
- **Admin Dashboard**: http://localhost:8000/admin/dashboard
- **SuperAdmin Dashboard**: http://localhost:8000/superadmin/dashboard
- **Gerenciar Usuários (Admin)**: http://localhost:8000/admin/users
- **Gerenciar Usuários (SuperAdmin)**: http://localhost:8000/superadmin/users
- **Configurações**: http://localhost:8000/superadmin/settings

---

✅ **Sistema instalado e pronto para uso!**
