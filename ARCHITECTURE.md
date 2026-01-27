# Arquitetura do Sistema - LDAP Admin Site

## 🏗️ Visão Geral da Arquitetura

```
┌─────────────────────────────────────────────────────────────┐
│                        NAVEGADOR                             │
│                    (Interface do Usuário)                    │
└───────────────────────┬─────────────────────────────────────┘
                        │ HTTP/HTTPS
                        │
┌───────────────────────▼─────────────────────────────────────┐
│                    LARAVEL APPLICATION                       │
│  ┌──────────────────────────────────────────────────────┐  │
│  │                     ROUTES (web.php)                  │  │
│  │  • Públicas (/, /login)                             │  │
│  │  • Autenticadas (/dashboard)                        │  │
│  │  • Admin (/admin/*)                                 │  │
│  │  • SuperAdmin (/superadmin/*)                       │  │
│  └────────┬─────────────────────────────────────┬───────┘  │
│           │                                      │           │
│  ┌────────▼────────┐                   ┌────────▼────────┐ │
│  │  MIDDLEWARES    │                   │  CONTROLLERS    │ │
│  │  • CheckRole    │◄──────────────────│  • Auth         │ │
│  │  • CheckAdmin   │                   │  • Dashboard    │ │
│  │  • CheckSuper   │                   │  • Admin        │ │
│  │                 │                   │  • SuperAdmin   │ │
│  └─────────────────┘                   └────────┬────────┘ │
│                                                  │           │
│                                         ┌────────▼────────┐ │
│                                         │    SERVICES     │ │
│                                         │  LdapAuthService│ │
│                                         └────────┬────────┘ │
│                                                  │           │
│                                         ┌────────▼────────┐ │
│                                         │     MODELS      │ │
│                                         │      User       │ │
│                                         └────────┬────────┘ │
└──────────────────────────────────────────────────┼──────────┘
                                                   │
                        ┌──────────────────────────┴──────────────────┐
                        │                                             │
         ┌──────────────▼──────────────┐         ┌─────────────────▼──────────┐
         │      MYSQL DATABASE         │         │      LDAP SERVER           │
         │  • users                    │         │  • Autenticação            │
         │  • password_reset_tokens    │         │  • Grupos/Roles            │
         │  • sessions                 │         │  • Atributos Usuário       │
         └─────────────────────────────┘         └────────────────────────────┘
```

## 🔄 Fluxo de Autenticação

```
┌─────────┐
│ USUÁRIO │
└────┬────┘
     │ 1. Acessa /login
     ▼
┌──────────────────┐
│  LOGIN FORM      │
│  • username      │
│  • password      │
└────┬─────────────┘
     │ 2. POST /login
     ▼
┌──────────────────────────────┐
│ AuthenticatedSessionCtrl     │
│  • Valida dados              │
└────┬─────────────────────────┘
     │ 3. Chama LdapAuthService
     ▼
┌──────────────────────────────┐
│   LdapAuthService            │
│  • Conecta ao LDAP           │
│  • Autentica usuário         │
│  • Busca grupos              │
│  • Mapeia para role          │
└────┬─────────────────────────┘
     │ 4. Retorna User
     ▼
┌──────────────────────────────┐
│  User Model                  │
│  • Salva/Atualiza no DB      │
│  • Define role               │
└────┬─────────────────────────┘
     │ 5. Login sucesso
     ▼
┌──────────────────────────────┐
│  Redirect por Role           │
│  • user → /dashboard         │
│  • admin → /admin/dashboard  │
│  • super → /superadmin/...   │
└──────────────────────────────┘
```

## 🛡️ Fluxo de Autorização

```
┌─────────────────┐
│ REQUEST         │
│ /admin/users    │
└────┬────────────┘
     │
     ▼
┌─────────────────────────┐
│ auth middleware         │
│ • Verifica autenticação │
└────┬────────────────────┘
     │ ✓ Autenticado
     ▼
┌─────────────────────────┐
│ CheckRole middleware    │
│ • Verifica role         │
│ • Permite: admin,super  │
└────┬────────────────────┘
     │
     ├─► ✓ Autorizado → Controller → View
     │
     └─► ✗ Negado → HTTP 403
```

## 📊 Modelo de Dados

```
┌─────────────────────────────────────┐
│              USERS                  │
├─────────────────────────────────────┤
│ id: bigint (PK)                     │
│ name: varchar(255)                  │
│ email: varchar(255) UNIQUE          │
│ password: varchar(255)              │
│ role: enum('user','admin','super')  │
│ ldap_dn: varchar(255) NULLABLE      │
│ remember_token: varchar(100)        │
│ created_at: timestamp               │
│ updated_at: timestamp               │
└─────────────────────────────────────┘
```

## 🎯 Hierarquia de Roles

```
        ┌──────────────────┐
        │   SUPERADMIN     │ ◄── Acesso Total
        │   (superadmin)   │
        └────────┬─────────┘
                 │
                 │ Herda permissões
                 ▼
        ┌──────────────────┐
        │      ADMIN       │ ◄── Gerencia users/admins
        │     (admin)      │
        └────────┬─────────┘
                 │
                 │ Herda permissões
                 ▼
        ┌──────────────────┐
        │      USER        │ ◄── Acesso básico
        │     (user)       │
        └──────────────────┘
```

## 📁 Estrutura de Controllers

```
Controllers/
│
├── Auth/
│   └── AuthenticatedSessionController
│       ├── create()        → Exibe login
│       ├── store()         → Processa login (LDAP)
│       └── destroy()       → Logout
│
├── DashboardController
│   └── index()             → Dashboard usuário comum
│
├── AdminController
│   ├── dashboard()         → Dashboard admin
│   ├── users()             → Lista users/admins
│   ├── editUser()          → Formulário edição
│   └── updateUser()        → Salva alterações
│
└── SuperAdminController
    ├── dashboard()         → Dashboard superadmin
    ├── users()             → Lista todos usuários
    ├── editUser()          → Editar qualquer user
    ├── updateUser()        → Atualizar qualquer user
    ├── deleteUser()        → Deletar usuário
    └── settings()          → Configurações sistema
```

## 🔐 Matriz de Permissões

| Recurso                    | User | Admin | SuperAdmin |
|----------------------------|------|-------|------------|
| Ver próprio dashboard      | ✅   | ✅    | ✅         |
| Ver dashboard admin        | ❌   | ✅    | ✅         |
| Ver dashboard superadmin   | ❌   | ❌    | ✅         |
| Listar users               | ❌   | ✅    | ✅         |
| Editar users               | ❌   | ✅    | ✅         |
| Listar admins              | ❌   | ✅    | ✅         |
| Editar admins              | ❌   | ❌    | ✅         |
| Listar superadmins         | ❌   | ❌    | ✅         |
| Editar superadmins         | ❌   | ❌    | ✅         |
| Deletar usuários           | ❌   | ❌    | ✅         |
| Ver configurações          | ❌   | ❌    | ✅         |

## 🗺️ Mapa de Rotas

```
/
├── login (GET/POST)
│
└── [auth]
    │
    ├── dashboard (GET)
    │   └── DashboardController@index
    │
    ├── admin/ [role:admin,superadmin]
    │   ├── dashboard (GET)
    │   ├── users (GET)
    │   ├── users/{id}/edit (GET)
    │   └── users/{id} (PUT)
    │
    └── superadmin/ [role:superadmin]
        ├── dashboard (GET)
        ├── users (GET)
        ├── users/{id}/edit (GET)
        ├── users/{id} (PUT)
        ├── users/{id} (DELETE)
        └── settings (GET)
```

## 🎨 Estrutura de Views

```
views/
│
├── layouts/
│   └── app.blade.php          # Layout SB Admin
│       ├── Sidebar (dinâmico por role)
│       ├── Topbar (info usuário)
│       └── Content area
│
├── auth/
│   └── login.blade.php        # Formulário LDAP
│
├── dashboard/
│   └── index.blade.php        # Dashboard usuário
│
├── admin/
│   ├── dashboard.blade.php    # Dashboard admin
│   ├── users.blade.php        # Lista users
│   └── edit-user.blade.php    # Editar user
│
├── superadmin/
│   ├── dashboard.blade.php    # Dashboard super
│   ├── users.blade.php        # Todos users
│   ├── edit-user.blade.php    # Editar qualquer
│   └── settings.blade.php     # Configurações
│
└── welcome.blade.php          # Página inicial
```

## 🔌 Integração LDAP

```
config/ldap.php
├── hosts              → Servidor(es) LDAP
├── port               → Porta (389/636)
├── base_dn            → Base DN busca
├── username/password  → Credenciais bind
│
├── user_attributes    → Mapeamento atributos
│   ├── username       → uid
│   ├── email          → mail
│   ├── name           → cn
│   └── groups         → memberOf
│
└── role_mapping       → Grupos → Roles
    ├── cn=superadmin  → superadmin
    ├── cn=admin       → admin
    └── cn=users       → user
```

## 🚀 Deployment

```
┌──────────────────────────────────┐
│      SERVIDOR PRODUÇÃO           │
│                                  │
│  ┌────────────────────────────┐ │
│  │     NGINX/APACHE           │ │
│  │  • SSL/TLS                 │ │
│  │  • Reverse Proxy           │ │
│  └──────────┬─────────────────┘ │
│             │                    │
│  ┌──────────▼─────────────────┐ │
│  │   PHP-FPM 8.2+             │ │
│  │   Laravel Application      │ │
│  └──────────┬─────────────────┘ │
│             │                    │
│  ┌──────────▼─────────────────┐ │
│  │   MySQL 8.0+               │ │
│  └────────────────────────────┘ │
└──────────────────────────────────┘
         │
         │ LDAPS (636)
         ▼
┌──────────────────────────────────┐
│      SERVIDOR LDAP               │
└──────────────────────────────────┘
```

## 📝 Fluxo de Dados Completo

```
1. Usuário acessa sistema
   └──► Página Welcome

2. Clica em "Entrar"
   └──► Formulário Login

3. Insere username/password
   └──► POST /login

4. Laravel recebe request
   └──► AuthenticatedSessionController

5. Controller chama serviço
   └──► LdapAuthService

6. Serviço conecta LDAP
   ├──► Bind administrativo
   ├──► Busca usuário
   ├──► Autentica com credenciais
   └──► Obtém grupos

7. Mapeia grupos → role
   └──► Retorna User object

8. Salva/atualiza no MySQL
   └──► User model

9. Faz login sessão
   └──► Auth::login()

10. Redireciona por role
    ├──► user → /dashboard
    ├──► admin → /admin/dashboard
    └──► superadmin → /superadmin/dashboard

11. Middleware verifica permissões
    └──► Acesso permitido/negado

12. Controller processa
    └──► Retorna view

13. View renderiza com SB Admin
    └──► HTML enviado ao navegador
```

---

Esta arquitetura garante:
- ✅ Separação de responsabilidades
- ✅ Segurança em camadas
- ✅ Escalabilidade
- ✅ Manutenibilidade
- ✅ Integração LDAP transparente
