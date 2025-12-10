<?php
require_once 'verificar_autenticacao.php';

// Verifica se é cliente (não admin)
if ($_SESSION['usuario_tipo'] != 'cliente') {
    header("Location: acesso_negado.php");
    exit;
}

require_once 'conexao.php';

$busca = $_GET['busca'] ?? '';
$filtro_loja = $_GET['loja'] ?? '';

// Buscar lojas
try {
    $sql_lojas = "SELECT * FROM Loja ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql_lojas);
    $stmt->execute();
    $lojas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $lojas = [];
}

// Buscar produtos e lojas
try {
    $sql = "SELECT p.id, p.nome, p.descricao, p.preco, p.tipo, p.categoria, 
                   l.id as loja_id, l.nome as loja_nome, l.cidade as loja_cidade, e.quantidade_disponivel
            FROM Produto p
            INNER JOIN Estoque e ON p.id = e.id_produto
            INNER JOIN Loja l ON l.id = e.id_loja
            WHERE 1=1";
    
    $parametros = [];
    
    if (!empty($busca)) {
        $sql .= " AND (p.nome LIKE ? OR p.descricao LIKE ?)";
        $parametros[] = "%$busca%";
        $parametros[] = "%$busca%";
    }
    
    if (!empty($filtro_loja)) {
        $sql .= " AND l.id = ?";
        $parametros[] = $filtro_loja;
    }
    
    $sql .= " ORDER BY p.nome ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produtos = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hub - Lojas e Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
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
        }

        .search-container {
            max-width: 800px;
            margin: 30px auto;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 15px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-btn {
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .filter-container {
            margin: 20px 0;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .product-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
        }

        .product-header h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .product-type {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-top: 5px;
        }

        .product-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-descricao {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
            flex-grow: 1;
        }

        .product-info {
            font-size: 0.85rem;
            color: #999;
            margin: 8px 0;
        }

        .product-footer {
            border-top: 1px solid #eee;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-footer form {
            margin: 0;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #667eea;
        }

        .product-stock {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .stock-available {
            background: #d4edda;
            color: #155724;
        }

        .stock-unavailable {
            background: #f8d7da;
            color: #721c24;
        }

        .store-badge {
            display: inline-block;
            background: #e7f3ff;
            color: #0066cc;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-results-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .breadcrumb-custom {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="hub.php">🛍️ Loja Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="carrinho.php" style="position: relative;">
                            🛒 Carrinho
                            <?php if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0): ?>
                                <span class="badge bg-danger" style="position: absolute; top: 5px; right: 0;">
                                    <?php echo count($_SESSION['carrinho']); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <div class="user-info ms-3">
                            👤 <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>
                        </div>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Search Container -->
    <div class="container">
        <div class="search-container">
            <form method="GET" action="hub.php" class="search-box">
                <input 
                    type="text" 
                    class="search-input" 
                    name="busca" 
                    placeholder="🔍 Buscar produtos..." 
                    value="<?php echo htmlspecialchars($busca); ?>"
                >
                <button type="submit" class="search-btn">Buscar</button>
            </form>
        </div>

        <!-- Filter -->
        <div class="filter-container">
            <form method="GET" action="hub.php" class="row g-3">
                <div class="col-12 col-md-8">
                    <input 
                        type="hidden" 
                        name="busca" 
                        value="<?php echo htmlspecialchars($busca); ?>"
                    >
                    <label class="form-label">🏪 Filtrar por Loja:</label>
                    <select name="loja" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas as Lojas</option>
                        <?php foreach ($lojas as $loja): ?>
                            <option value="<?php echo $loja['id']; ?>" <?php echo $filtro_loja == $loja['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($loja['nome']) . ' - ' . htmlspecialchars($loja['cidade']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <a href="hub.php" class="btn btn-secondary w-100">Limpar Filtros</a>
                </div>
            </form>
        </div>

        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <span>🏠 Início</span>
            <?php if (!empty($busca)): ?>
                <span> / 🔍 Busca: <?php echo htmlspecialchars($busca); ?></span>
            <?php endif; ?>
            <?php if (!empty($filtro_loja)): ?>
                <span> / 🏪 Filtrado por loja</span>
            <?php endif; ?>
            <span> (<?php echo count($produtos); ?> produtos encontrados)</span>
        </div>

        <!-- Products Grid -->
        <?php if (count($produtos) > 0): ?>
            <div class="row g-4 mb-5">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <div class="product-card">
                            <div class="product-header">
                                <h5><?php echo htmlspecialchars($produto['nome']); ?></h5>
                                <span class="product-type">
                                    <?php echo htmlspecialchars($produto['tipo']); ?>
                                </span>
                            </div>

                            <div class="product-body">
                                <span class="store-badge">
                                    🏪 <?php echo htmlspecialchars($produto['loja_nome']); ?>
                                </span>

                                <p class="product-descricao">
                                    <?php echo htmlspecialchars(substr($produto['descricao'], 0, 100)); ?>
                                    <?php if (strlen($produto['descricao']) > 100): ?>...<?php endif; ?>
                                </p>

                                <div class="product-info">
                                    <strong>Categoria:</strong> <?php echo htmlspecialchars($produto['categoria']); ?>
                                </div>

                                <div class="product-info">
                                    <strong>Cidade:</strong> <?php echo htmlspecialchars($produto['loja_cidade']); ?>
                                </div>

                                <div class="product-footer" style="margin-top: auto; flex-direction: column; gap: 10px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                        <div class="product-price">
                                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                                        </div>
                                        <span class="product-stock <?php echo $produto['quantidade_disponivel'] > 0 ? 'stock-available' : 'stock-unavailable'; ?>">
                                            <?php echo $produto['quantidade_disponivel'] > 0 ? '✓ Em estoque' : '✗ Indisponível'; ?>
                                        </span>
                                    </div>
                                    
                                    <?php if ($produto['quantidade_disponivel'] > 0): ?>
                                        <form method="POST" action="carrinho.php" style="width: 100%;">
                                            <input type="hidden" name="acao" value="adicionar">
                                            <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
                                            <input type="hidden" name="id_loja" value="<?php echo $produto['loja_id']; ?>">
                                            <div style="display: flex; gap: 8px; width: 100%;">
                                                <input type="number" name="quantidade" class="form-control form-control-sm" value="1" min="1" max="<?php echo $produto['quantidade_disponivel']; ?>" style="flex: 0.4;">
                                                <button type="submit" class="btn btn-sm btn-primary" style="flex: 0.6; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-weight: 600;">
                                                    + Carrinho
                                                </button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <div class="no-results-icon">🔍</div>
                <h3>Nenhum produto encontrado</h3>
                <p>
                    <?php 
                    if (!empty($busca)) {
                        echo "Nenhum produto corresponde à sua busca por \"" . htmlspecialchars($busca) . "\".";
                    } else if (!empty($filtro_loja)) {
                        echo "Nenhum produto disponível nesta loja.";
                    } else {
                        echo "Nenhum produto disponível no momento.";
                    }
                    ?>
                </p>
                <a href="hub.php" class="btn btn-primary mt-3">Voltar à Página Principal</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
