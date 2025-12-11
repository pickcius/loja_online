<?php
session_start();
require_once '../config/urls.php';

// Se já está logado, redireciona para o local apropriado
if (isset($_SESSION['usuario_id']) && isset($_SESSION['usuario_tipo'])) {
    if ($_SESSION['usuario_tipo'] == 'administrador') {
        header("Location: " . ADMIN_URL . "admin_index.php");
    } else {
        header("Location: " . HUB_URL . "hub.php");
    }
    exit;
}

require_once '../config/conexao.php';

$erro = "";
$sucesso = "";
$modo_admin = false;

// Processa o formulário de login de cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'login_cliente') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $erro = "Email e senha são obrigatórios!";
    } else {
        try {
            // Buscar cliente apenas pelo email (não comparar senha aqui)
            $sql = "SELECT id, nome, email, senha_hash FROM Cliente WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar a senha com password_verify()
                if (password_verify($senha, $cliente['senha_hash'])) {
                    // Login de cliente bem-sucedido
                    $_SESSION['usuario_id'] = $cliente['id'];
                    $_SESSION['usuario_nome'] = $cliente['nome'];
                    $_SESSION['usuario_email'] = $cliente['email'];
                    $_SESSION['usuario_tipo'] = 'cliente';

                    header("Location: " . HUB_URL . "hub.php");
                    exit;
                } else {
                    $erro = "Email ou senha incorretos!";
                }
            } else {
                $erro = "Email ou senha incorretos!";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao conectar ao banco de dados: " . $e->getMessage();
        }
    }
}

// Processa o formulário de login de administrador
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'login_admin') {
    $usuario = trim($_POST['usuario_admin'] ?? '');
    $senha = $_POST['senha_admin'] ?? '';

    if (empty($usuario) || empty($senha)) {
        $erro = "Usuário e senha são obrigatórios!";
        $modo_admin = true;
    } else {
        try {
            $sql = "SELECT id, nome, senha_hash FROM Administrador WHERE nome = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario]);

            if ($stmt->rowCount() > 0) {
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar a senha com password_verify()
                if (password_verify($senha, $admin['senha_hash'])) {
                    // Login bem-sucedido
                    $_SESSION['usuario_id'] = $admin['id'];
                    $_SESSION['usuario_nome'] = $admin['nome'];
                    $_SESSION['usuario_tipo'] = 'administrador';

                    header("Location: " . ADMIN_URL . "admin_index.php");
                    exit;
                } else {
                    $erro = "Senha incorreta!";
                    $modo_admin = true;
                }
            } else {
                $erro = "Usuário não encontrado!";
                $modo_admin = true;
            }
        } catch (PDOException $e) {
            $erro = "Erro ao conectar ao banco de dados: " . $e->getMessage();
            $modo_admin = true;
        }
    }
}

// Processa o formulário de cadastro de cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'cadastro') {
    $nome = trim($_POST['nome_cadastro'] ?? '');
    $email = trim($_POST['email_cadastro'] ?? '');
    $telefone = trim($_POST['telefone_cadastro'] ?? '');
    $senha = $_POST['senha_cadastro'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = "Nome, email e senha são obrigatórios!";
    } else if ($senha !== $confirmar_senha) {
        $erro = "As senhas não conferem!";
    } else if (strlen($senha) < 4) {
        $erro = "A senha deve ter no mínimo 4 caracteres!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido!";
    } else {
        try {
            $sql_check = "SELECT id FROM Cliente WHERE email = ?";
            $stmt = $pdo->prepare($sql_check);
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $erro = "Este email já está cadastrado!";
            } else {
                // Gerar hash seguro da senha com password_hash()
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                $sql_insert = "INSERT INTO Cliente (nome, email, telefone, senha_hash) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql_insert);
                $stmt->execute([$nome, $email, $telefone, $senha_hash]);

                $sucesso = "Cadastro realizado com sucesso! Agora você pode fazer login com seu email.";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Loja Online</title>
    <link href="<?php echo CSS_URL; ?>bootstrap.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header h1 {
            margin: 0;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .login-header p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            border: none;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
            position: relative;
        }

        .divider::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }

        .divider::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }

        .toggle-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .toggle-btn {
            flex: 1;
            padding: 12px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
        }

        .toggle-btn.active {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }

        .toggle-btn:hover {
            border-color: #667eea;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .btn-cadastro {
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-cadastro:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-admin-login {
            background: white;
            border: 2px solid #666;
            color: #666;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .btn-admin-login:hover {
            background: #666;
            color: white;
        }

        .footer-text {
            text-align: center;
            color: #999;
            font-size: 0.9rem;
            margin-top: 20px;
        }

        .badge-admin {
            display: inline-block;
            background: #666;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 5px;
        }

        @media (max-width: 600px) {
            .login-body {
                padding: 30px 20px;
            }

            .login-header {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <h1>🛍️ Loja Online</h1>
                <p>Sistema de Compras</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Mensagens de Erro/Sucesso -->
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erro:</strong> <?php echo htmlspecialchars($erro); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($sucesso)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sucesso!</strong> <?php echo htmlspecialchars($sucesso); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Toggle Buttons -->
                <div class="toggle-buttons">
                    <button type="button" class="toggle-btn active" onclick="mostrarCliente()">
                        👤 Cliente
                    </button>
                    <button type="button" class="toggle-btn" onclick="mostrarAdmin()">
                        🔐 Admin <span class="badge-admin">acesso restrito</span>
                    </button>
                </div>

                <!-- FORMULÁRIO DE LOGIN CLIENTE -->
                <div id="form-cliente" class="form-section active">
                    <form method="POST" action="login.php">
                        <input type="hidden" name="acao" value="login_cliente">

                        <div class="form-group">
                            <label class="form-label">📧 Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">🔒 Senha</label>
                            <input type="password" class="form-control" name="senha" required>
                        </div>

                        <button type="submit" class="btn-login">Entrar na Loja</button>
                    </form>

                    <div class="divider">ou</div>

                    <button type="button" class="btn-cadastro" onclick="scrollToCadastro()">
                        📝 Criar Nova Conta
                    </button>

                    <div class="footer-text">
                        Não tem conta? Cadastre-se gratuitamente
                    </div>
                </div>

                <!-- FORMULÁRIO DE LOGIN ADMINISTRADOR -->
                <div id="form-admin" class="form-section">
                    <form method="POST" action="login.php">
                        <input type="hidden" name="acao" value="login_admin">

                        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                            <p style="margin: 0; font-size: 0.9rem; color: #856404;">
                                ⚠️ Área restrita para administradores
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">👤 Usuário Admin</label>
                            <input type="text" class="form-control" name="usuario_admin" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">🔒 Senha</label>
                            <input type="password" class="form-control" name="senha_admin" required>
                        </div>

                        <button type="submit" class="btn-login">Acessar Painel</button>
                    </form>

                    <button type="button" class="btn-admin-login" onclick="mostrarCliente()">
                        ← Voltar para Cliente
                    </button>
                </div>
            </div>
        </div>

        <!-- Seção de Cadastro -->
        <div id="cadastro-section" style="margin-top: 40px; display: none;">
            <div class="login-card">
                <div class="login-header">
                    <h1>📝 Criar Conta</h1>
                    <p>Junte-se à nossa loja</p>
                </div>

                <div class="login-body">
                    <form method="POST" action="login.php">
                        <input type="hidden" name="acao" value="cadastro">

                        <div class="form-group">
                            <label class="form-label">👤 Nome Completo</label>
                            <input type="text" class="form-control" name="nome_cadastro" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">📧 Email</label>
                            <input type="email" class="form-control" name="email_cadastro" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">📱 Telefone (Opcional)</label>
                            <input type="tel" class="form-control" name="telefone_cadastro">
                        </div>

                        <div class="form-group">
                            <label class="form-label">🔒 Senha</label>
                            <input type="password" class="form-control" name="senha_cadastro" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">✔️ Confirmar Senha</label>
                            <input type="password" class="form-control" name="confirmar_senha" required>
                        </div>

                        <button type="submit" class="btn-login">Criar Conta</button>
                        <button type="button" class="btn-admin-login" onclick="scrollToLogin()">
                            ← Voltar ao Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo JS_URL; ?>bootstrap.bundle.js"></script>
    <script>
        function mostrarCliente() {
            document.getElementById('form-cliente').classList.add('active');
            document.getElementById('form-admin').classList.remove('active');
            document.getElementById('cadastro-section').style.display = 'none';
            
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            if (event.target.parentElement.querySelector('.toggle-btn:first-child')) {
                event.target.parentElement.querySelector('.toggle-btn:first-child').classList.add('active');
            }
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function mostrarAdmin() {
            document.getElementById('form-cliente').classList.remove('active');
            document.getElementById('form-admin').classList.add('active');
            document.getElementById('cadastro-section').style.display = 'none';
            
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            if (event.target.classList.contains('toggle-btn')) {
                event.target.classList.add('active');
            } else {
                event.target.closest('.toggle-btn').classList.add('active');
            }
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function scrollToCadastro() {
            document.getElementById('cadastro-section').style.display = 'block';
            document.getElementById('form-cliente').classList.remove('active');
            document.getElementById('form-admin').classList.remove('active');
            
            setTimeout(() => {
                document.getElementById('cadastro-section').scrollIntoView({ behavior: 'smooth' });
            }, 100);
        }

        function scrollToLogin() {
            document.getElementById('cadastro-section').style.display = 'none';
            document.getElementById('form-cliente').classList.add('active');
            document.getElementById('form-admin').classList.remove('active');
            
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.toggle-btn')[0].classList.add('active');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

 <script src="<?php echo JS_URL; ?>bootstrap.bundle.js"></script>

</body>

</html>
