<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LDAP Admin Site</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 50px;
            text-align: center;
            max-width: 600px;
        }
        .welcome-card h1 {
            color: #667eea;
            margin-bottom: 20px;
        }
        .welcome-card p {
            color: #6c757d;
            margin-bottom: 30px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            font-size: 18px;
            border-radius: 50px;
            color: white;
            transition: transform 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .features {
            margin-top: 40px;
            text-align: left;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .feature-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="welcome-card">
        <h1><i class="fas fa-shield-alt"></i> LDAP Admin Site</h1>
        <p class="lead">Sistema de gerenciamento com autenticação LDAP e controle de acesso por níveis</p>
        
        <a href="{{ route('login') }}" class="btn btn-custom">
            <i class="fas fa-sign-in-alt"></i> Entrar no Sistema
        </a>

        <div class="features">
            <h5>Recursos do Sistema:</h5>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <strong>Autenticação LDAP</strong><br>
                    <small class="text-muted">Login seguro com seu servidor LDAP</small>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div>
                    <strong>3 Níveis de Acesso</strong><br>
                    <small class="text-muted">Usuário, Administrador e Super Administrador</small>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <strong>Gerenciamento Completo</strong><br>
                    <small class="text-muted">Interface moderna com SB Admin 2</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
