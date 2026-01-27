# LDAP Admin Site

Sistema de gerenciamento com autenticação LDAP e três níveis de acesso utilizando Laravel 11 + Breeze e template SB Admin 2.

## 🚀 Características

- **Autenticação LDAP**: Login integrado com servidor LDAP
- **3 Níveis de Acesso**:
  - **Usuário**: Acesso básico ao dashboard
  - **Administrador**: Gerenciamento de usuários comuns e admins
  - **Super Administrador**: Acesso total ao sistema, incluindo configurações
- **Interface Moderna**: Template SB Admin 2
- **Controle Granular**: Middlewares específicos para cada nível de acesso
- **Sincronização LDAP**: Mapeamento automático de grupos LDAP para roles

## 📁 Estrutura do Projeto

```
ldap-admin-site/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthenticatedSessionController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── AdminController.php
│   │   │   └── SuperAdminController.php
│   │   └── Middleware/
│   │       ├── CheckRole.php
│   │       ├── CheckAdmin.php
│   │       └── CheckSuperAdmin.php
│   ├── Models/
│   │   └── User.php
│   └── Services/
│       └── LdapAuthService.php
├── config/
│   └── ldap.php
├── database/
│   └── migrations/
│       └── 2024_01_01_000000_create_users_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── users.blade.php
│       │   └── edit-user.blade.php
│       ├── superadmin/
│       │   ├── dashboard.blade.php
│       │   ├── users.blade.php
│       │   ├── edit-user.blade.php
│       │   └── settings.blade.php
│       └── welcome.blade.php
└── routes/
    └── web.php
```

## 🛠️ Instalação

### Pré-requisitos

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Servidor LDAP configurado

### Passos

1. **Clone o repositório** (ou extraia os arquivos)

2. **Instale as dependências**:
```bash
composer install
```

3. **Configure o arquivo .env**:
```bash
cp .env .env.local
```

Edite o `.env` com suas configurações:

```env
# Banco de Dados
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ldap_admin
DB_USERNAME=root
DB_PASSWORD=sua_senha

# Configurações LDAP
LDAP_HOSTS=ldap://seu-servidor-ldap.com
LDAP_PORT=389
LDAP_BASE_DN=dc=exemplo,dc=com
LDAP_USERNAME=cn=admin,dc=exemplo,dc=com
LDAP_PASSWORD=senha_ldap
```

4. **Gere a chave da aplicação**:
```bash
php artisan key:generate
```

5. **Execute as migrations**:
```bash
php artisan migrate
```

6. **Inicie o servidor**:
```bash
php artisan serve
```

Acesse: `http://localhost:8000`

## 🔐 Configuração LDAP

### Mapeamento de Grupos

Edite o arquivo `config/ldap.php` para mapear grupos LDAP para roles:

```php
'role_mapping' => [
    'cn=superadmin,ou=groups,dc=example,dc=com' => 'superadmin',
    'cn=admin,ou=groups,dc=example,dc=com' => 'admin',
    'cn=users,ou=groups,dc=example,dc=com' => 'user',
],
```

### Atributos do Usuário

Configure quais atributos LDAP serão usados:

```php
'user_attributes' => [
    'username' => 'uid',        // Atributo do username
    'email' => 'mail',          // Atributo do email
    'name' => 'cn',             // Atributo do nome completo
    'groups' => 'memberOf',     // Atributo dos grupos
],
```

## 🎯 Rotas

### Públicas
- `GET /` - Página de boas-vindas
- `GET /login` - Formulário de login
- `POST /login` - Processar login

### Autenticadas (Todos os níveis)
- `GET /dashboard` - Dashboard do usuário
- `POST /logout` - Logout

### Administradores (admin + superadmin)
- `GET /admin/dashboard` - Dashboard administrativo
- `GET /admin/users` - Lista de usuários
- `GET /admin/users/{id}/edit` - Editar usuário
- `PUT /admin/users/{id}` - Atualizar usuário

### Super Administradores (apenas superadmin)
- `GET /superadmin/dashboard` - Dashboard super admin
- `GET /superadmin/users` - Todos os usuários
- `GET /superadmin/users/{id}/edit` - Editar qualquer usuário
- `PUT /superadmin/users/{id}` - Atualizar qualquer usuário
- `DELETE /superadmin/users/{id}` - Deletar usuário
- `GET /superadmin/settings` - Configurações do sistema

## 🔒 Middlewares

### CheckRole
Middleware genérico que aceita múltiplas roles:
```php
Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {
    // Rotas acessíveis por admin e superadmin
});
```

### CheckAdmin
Permite acesso apenas para admin e superadmin:
```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Rotas administrativas
});
```

### CheckSuperAdmin
Permite acesso apenas para superadmin:
```php
Route::middleware(['auth', 'superadmin'])->group(function () {
    // Rotas exclusivas do super admin
});
```

## 📊 Níveis de Acesso

| Recurso | Usuário | Admin | SuperAdmin |
|---------|---------|-------|------------|
| Dashboard Pessoal | ✅ | ✅ | ✅ |
| Dashboard Admin | ❌ | ✅ | ✅ |
| Dashboard SuperAdmin | ❌ | ❌ | ✅ |
| Ver Usuários Comuns | ❌ | ✅ | ✅ |
| Editar Usuários Comuns | ❌ | ✅ | ✅ |
| Ver Admins | ❌ | ✅ | ✅ |
| Editar Admins | ❌ | ❌ | ✅ |
| Ver SuperAdmins | ❌ | ❌ | ✅ |
| Editar SuperAdmins | ❌ | ❌ | ✅ |
| Deletar Usuários | ❌ | ❌ | ✅ |
| Configurações Sistema | ❌ | ❌ | ✅ |

## 🎨 Template SB Admin

O projeto utiliza o template SB Admin 2, que inclui:
- Sidebar responsivo
- Topbar com informações do usuário
- Cards e componentes Bootstrap
- Ícones Font Awesome
- Design moderno e profissional

## 🧪 Testando sem LDAP

Para testes sem servidor LDAP, você pode criar usuários manualmente no banco:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Criar Super Admin
User::create([
    'name' => 'Super Admin',
    'email' => 'superadmin@example.com',
    'password' => Hash::make('password'),
    'role' => 'superadmin',
]);

// Criar Admin
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
]);

// Criar Usuário
User::create([
    'name' => 'User',
    'email' => 'user@example.com',
    'password' => Hash::make('password'),
    'role' => 'user',
]);
```

**Nota**: Modifique o `AuthenticatedSessionController` para aceitar login com email ao invés de username do LDAP durante os testes.

## 📝 Personalização

### Adicionar Novos Níveis de Acesso

1. Adicione a role na migration e no enum do Model User
2. Crie um novo middleware (opcional)
3. Adicione rotas específicas
4. Atualize o menu do sidebar em `layouts/app.blade.php`
5. Configure o mapeamento no `config/ldap.php`

### Customizar Layout

Edite o arquivo `resources/views/layouts/app.blade.php` para personalizar:
- Logo e nome da aplicação
- Cores do tema
- Itens do menu
- Footer

## 🔧 Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Gerar autoload
composer dump-autoload

# Executar migrations
php artisan migrate

# Reverter última migration
php artisan migrate:rollback

# Criar nova migration
php artisan make:migration nome_da_migration

# Criar controller
php artisan make:controller NomeController

# Criar middleware
php artisan make:middleware NomeMiddleware
```

## 📄 Licença

Este projeto é open-source sob a licença MIT.

## 👥 Suporte

Para dúvidas ou problemas:
1. Verifique a documentação do Laravel
2. Consulte a documentação do SB Admin 2
3. Revise as configurações LDAP

## 🔄 Próximas Melhorias

- [ ] Logs de auditoria
- [ ] Recuperação de senha
- [ ] Perfis de usuário editáveis
- [ ] API REST
- [ ] Testes unitários
- [ ] Dashboard com gráficos
- [ ] Exportação de relatórios
- [ ] Notificações por email
