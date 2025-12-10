<?php
// Requer autenticação
require_once '../auth/verificar_autenticacao.php';
require_once '../config/urls.php';
verificar_admin();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Produtos</title>
    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo CSS_URL; ?>bootstrap.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: color 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: white !important;
        }

        .user-info {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 20px;
            color: white;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-admin {
            background-color: #ffc107;
            color: #000;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .dashboard-card {
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .icon-placeholder {
            font-size: 2.5rem;
            color: #0d6efd;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo ADMIN_URL; ?>admin_index.php">🛍️ Loja Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo AUTH_URL; ?>gerenciar_usuarios.php">👥 Clientes</a>
                    </li>
                    <li class="nav-item">
                        <div class="user-info">
                            <span>👤 <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                            <span class="badge-admin">ADMIN</span>
                        </div>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link" href="<?php echo AUTH_URL; ?>logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="text-center mb-5">Painel de Controle Administrativo</h1>

        <div class="row g-4">
            <!-- Relatórios -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo RELATORIOS_URL; ?>relProdutosLoja.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-placeholder mb-3">
                                📊
                            </div>
                            <h5 class="card-title">Relatórios</h5>
                            <p class="card-text text-muted">Visualize métricas e análises</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Cadastro de Produtos -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo PRODUTOS_URL; ?>produtos.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-placeholder mb-3">
                                📦
                            </div>
                            <h5 class="card-title">Produtos</h5>
                            <p class="card-text text-muted">Cadastre e gerencie produtos</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Cadastro de Lojas -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo LOJAS_URL; ?>lojas.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-placeholder mb-3">
                                🏪
                            </div>
                            <h5 class="card-title">Lojas</h5>
                            <p class="card-text text-muted">Cadastre e gerencie lojas</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Cadastro de Características -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo CARACTERISTICAS_URL; ?>caracteristicas.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-placeholder mb-3">
                                ⚙️
                            </div>
                            <h5 class="card-title">Características</h5>
                            <p class="card-text text-muted">Tipos, categorias e atributos</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Relatório -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo RELATORIOS_URL; ?>relatorio.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-placeholder mb-3">
                                📄
                            </div>
                            <h5 class="card-title">Relatório Analitico</h5>
                            <p class="card-text text-muted">Lista de realtorios</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Relatório -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo RELATORIOS_URL; ?>relatorio_analitico.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-placeholder mb-3">
                                📄
                            </div>
                            <h5 class="card-title">Relatório Analitico-Prof</h5>
                            <p class="card-text text-muted">Exemplos</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Relatório -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo RELATORIOS_URL; ?>relAnalitico.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-placeholder mb-3">
                                📄
                            </div>
                            <h5 class="card-title">Relatório Analitico Modificado</h5>
                            <p class="card-text text-muted">Lista de Atividades</p>
                        </div>
                    </div>
                </a>
            </div>


        </div>
    </div>

    <!-- Bootstrap JS  -->
    <script src="../js/bootstrap.bundle.js"></script>
</body>

</html>