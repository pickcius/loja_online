<?php
require_once 'verificar_autenticacao.php';
verificar_cliente();

require_once 'conexao.php';

// Inicia sessão para carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$mensagem = "";
$tipo_mensagem = "";

// Ações POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'] ?? '';

    // Adicionar ao carrinho
    if ($acao == 'adicionar') {
        $id_produto = intval($_POST['id_produto']);
        $id_loja = intval($_POST['id_loja']);
        $quantidade = intval($_POST['quantidade'] ?? 1);

        if ($quantidade > 0) {
            $chave = "{$id_produto}_{$id_loja}";

            if (isset($_SESSION['carrinho'][$chave])) {
                $_SESSION['carrinho'][$chave]['quantidade'] += $quantidade;
            } else {
                $_SESSION['carrinho'][$chave] = [
                    'id_produto' => $id_produto,
                    'id_loja' => $id_loja,
                    'quantidade' => $quantidade
                ];
            }

            $mensagem = "Produto adicionado ao carrinho!";
            $tipo_mensagem = "success";
        }
    }

    // Remover item do carrinho
    if ($acao == 'remover') {
        $chave = $_POST['chave'] ?? '';
        if (isset($_SESSION['carrinho'][$chave])) {
            unset($_SESSION['carrinho'][$chave]);
            $mensagem = "Produto removido do carrinho!";
            $tipo_mensagem = "warning";
        }
    }

    // Atualizar quantidade
    if ($acao == 'atualizar_quantidade') {
        $chave = $_POST['chave'] ?? '';
        $nova_quantidade = intval($_POST['nova_quantidade'] ?? 0);

        if (isset($_SESSION['carrinho'][$chave]) && $nova_quantidade > 0) {
            $_SESSION['carrinho'][$chave]['quantidade'] = $nova_quantidade;
            $mensagem = "Quantidade atualizada!";
            $tipo_mensagem = "info";
        } else if ($nova_quantidade <= 0 && isset($_SESSION['carrinho'][$chave])) {
            unset($_SESSION['carrinho'][$chave]);
            $mensagem = "Produto removido!";
            $tipo_mensagem = "warning";
        }
    }

    // Finalizar compra
    if ($acao == 'finalizar_compra' && count($_SESSION['carrinho']) > 0) {
        try {
            // Validar estoque para todos os itens primeiro
            $todos_estoque_valido = true;
            $mensagem_estoque = "";

            foreach ($_SESSION['carrinho'] as $chave => $item) {
                $sql_check = "SELECT e.quantidade_disponivel, p.nome, l.cidade 
                              FROM Estoque e
                              INNER JOIN Produto p ON p.id = e.id_produto
                              INNER JOIN Loja l ON l.id = e.id_loja
                              WHERE e.id_produto = ? AND e.id_loja = ?";
                $stmt = $pdo->prepare($sql_check);
                $stmt->execute([$item['id_produto'], $item['id_loja']]);
                $estoque = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$estoque || $estoque['quantidade_disponivel'] < $item['quantidade']) {
                    $todos_estoque_valido = false;
                    $mensagem_estoque .= "❌ " . $estoque['nome'] . " - " . $estoque['cidade'] . " (disponível: " . ($estoque['quantidade_disponivel'] ?? 0) . ")<br>";
                }
            }

            if (!$todos_estoque_valido) {
                $mensagem = "Estoque insuficiente para alguns itens:<br>" . $mensagem_estoque;
                $tipo_mensagem = "danger";
            } else {
                // Inicia transação
                $pdo->beginTransaction();

                // Cria venda
                $sql_venda = "INSERT INTO Venda (id_cliente, id_loja, valor_total) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql_venda);

                // Usa a primeira loja como referência
                $primeira_loja = null;
                $valor_total = 0;

                // Primeira passagem: calcular total
                foreach ($_SESSION['carrinho'] as $chave => $item) {
                    $sql_produto = "SELECT p.preco, p.desconto_usados, p.tipo FROM Produto p WHERE p.id = ?";
                    $stmt_prod = $pdo->prepare($sql_produto);
                    $stmt_prod->execute([$item['id_produto']]);
                    $produto = $stmt_prod->fetch(PDO::FETCH_ASSOC);

                    $preco_final = $produto['preco'];
                    if ($produto['tipo'] == 'Usado') {
                        $preco_final -= $produto['desconto_usados'];
                    }

                    $valor_total += $preco_final * $item['quantidade'];

                    if (!$primeira_loja) {
                        $primeira_loja = $item['id_loja'];
                    }
                }

                $stmt->execute([$_SESSION['usuario_id'], $primeira_loja, $valor_total]);
                $id_venda = $pdo->lastInsertId();

                // Segunda passagem: inserir itens e atualizar estoque
                foreach ($_SESSION['carrinho'] as $chave => $item) {
                    $sql_produto = "SELECT p.preco, p.desconto_usados, p.tipo FROM Produto p WHERE p.id = ?";
                    $stmt_prod = $pdo->prepare($sql_produto);
                    $stmt_prod->execute([$item['id_produto']]);
                    $produto = $stmt_prod->fetch(PDO::FETCH_ASSOC);

                    $preco_final = $produto['preco'];
                    if ($produto['tipo'] == 'Usado') {
                        $preco_final -= $produto['desconto_usados'];
                    }

                    // Insere item na venda
                    $sql_item = "INSERT INTO ItemVenda (id_venda, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
                    $stmt_item = $pdo->prepare($sql_item);
                    $stmt_item->execute([$id_venda, $item['id_produto'], $item['quantidade'], $preco_final]);

                    // Atualiza estoque
                    $sql_estoque = "UPDATE Estoque SET quantidade_disponivel = quantidade_disponivel - ? WHERE id_produto = ? AND id_loja = ?";
                    $stmt_estoque = $pdo->prepare($sql_estoque);
                    $stmt_estoque->execute([$item['quantidade'], $item['id_produto'], $item['id_loja']]);
                }

                // Confirma transação
                $pdo->commit();

                $_SESSION['carrinho'] = [];
                $mensagem = "✅ Compra finalizada com sucesso! Pedido #" . $id_venda;
                $tipo_mensagem = "success";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem = "Erro ao finalizar compra: " . $e->getMessage();
            $tipo_mensagem = "danger";
        }
    }
}

// Recuperar dados do carrinho com join
$itens_carrinho = [];
$valor_total = 0;
$total_itens = 0;

if (count($_SESSION['carrinho']) > 0) {
    foreach ($_SESSION['carrinho'] as $chave => $item) {
        $sql = "SELECT p.id, p.nome, p.categoria, p.tipo, p.preco, p.desconto_usados,
                       l.id as loja_id, l.nome as loja_nome, l.cidade,
                       e.quantidade_disponivel
                FROM Produto p
                INNER JOIN Estoque e ON p.id = e.id_produto
                INNER JOIN Loja l ON l.id = e.id_loja
                WHERE p.id = ? AND l.id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$item['id_produto'], $item['id_loja']]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            $preco_final = $produto['preco'];
            if ($produto['tipo'] == 'Usado') {
                $preco_final -= $produto['desconto_usados'];
            }

            $subtotal = $preco_final * $item['quantidade'];
            $valor_total += $subtotal;
            $total_itens += $item['quantidade'];

            $produto['quantidade'] = $item['quantidade'];
            $produto['preco_final'] = $preco_final;
            $produto['subtotal'] = $subtotal;
            $produto['chave'] = $chave;

            $itens_carrinho[] = $produto;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
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

        .container-carrinho {
            max-width: 1000px;
            margin: 30px auto;
        }

        .card-carrinho {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .table-carrinho {
            margin-bottom: 0;
        }

        .table-carrinho thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table-carrinho th {
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table-carrinho td {
            padding: 15px;
            vertical-align: middle;
            border-color: #eee;
        }

        .produto-info {
            font-weight: 600;
            color: #333;
        }

        .preco-cell {
            font-weight: 600;
            color: #667eea;
        }

        .quantidade-input {
            width: 80px;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        .btn-remover {
            padding: 5px 10px;
            font-size: 0.85rem;
        }

        .resumo-carrinho {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
        }

        .resumo-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .resumo-item.total {
            font-size: 1.4rem;
            font-weight: 700;
            border-top: 2px solid rgba(255, 255, 255, 0.3);
            padding-top: 15px;
            margin-top: 15px;
        }

        .btn-compra {
            background: white;
            color: #667eea;
            border: none;
            padding: 15px 40px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 20px;
            font-size: 1.1rem;
        }

        .btn-compra:hover:not(:disabled) {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-compra:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .badge-loja {
            background: #e7f3ff;
            color: #0066cc;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .breadcrumb-custom {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .alert {
            border-radius: 8px;
            border: none;
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
                        <a class="nav-link" href="hub.php">← Voltar para Loja</a>
                    </li>
                    <li class="nav-item">
                        <div class="user-info">
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

    <div class="container-carrinho">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <span>🏠 <a href="hub.php" style="color: #667eea; text-decoration: none;">Loja</a></span>
            <span> / 🛒 Carrinho</span>
        </div>

        <!-- Mensagens -->
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                <?php echo $mensagem; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Título -->
        <h1 class="mb-4">🛒 Carrinho de Compras</h1>

        <?php if (count($itens_carrinho) > 0): ?>
            <!-- Tabela de Itens -->
            <div class="card-carrinho">
                <table class="table table-hover table-carrinho">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Loja</th>
                            <th style="text-align: center;">Qtd</th>
                            <th style="text-align: right;">Preço</th>
                            <th style="text-align: right;">Subtotal</th>
                            <th style="text-align: center;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens_carrinho as $item): ?>
                            <tr>
                                <td>
                                    <strong class="produto-info"><?php echo htmlspecialchars($item['nome']); ?></strong>
                                    <br>
                                    <small style="color: #666;">
                                        <?php echo htmlspecialchars(substr($item['tipo'], 0, 20)); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge-loja"><?php echo htmlspecialchars($item['categoria']); ?></span>
                                </td>
                                <td>
                                    <span class="badge-loja">
                                        🏪 <?php echo htmlspecialchars($item['loja_nome'] . ' - ' . $item['cidade']); ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="acao" value="atualizar_quantidade">
                                        <input type="hidden" name="chave" value="<?php echo htmlspecialchars($item['chave']); ?>">
                                        <input type="number" name="nova_quantidade" class="quantidade-input" value="<?php echo $item['quantidade']; ?>" min="1" max="999" onchange="this.form.submit();">
                                    </form>
                                    <small style="color: #999;">(máx: <?php echo $item['quantidade_disponivel']; ?>)</small>
                                </td>
                                <td style="text-align: right;">
                                    <span class="preco-cell">
                                        R$ <?php echo number_format($item['preco_final'], 2, ',', '.'); ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <strong class="preco-cell">
                                        R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?>
                                    </strong>
                                </td>
                                <td style="text-align: center;">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="acao" value="remover">
                                        <input type="hidden" name="chave" value="<?php echo htmlspecialchars($item['chave']); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger btn-remover">🗑️ Remover</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Resumo e Finalizar Compra -->
            <div class="resumo-carrinho">
                <div class="resumo-item">
                    <span>Total de Itens:</span>
                    <strong><?php echo $total_itens; ?></strong>
                </div>
                <div class="resumo-item total">
                    <span>Valor Total da Compra:</span>
                    <strong>R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></strong>
                </div>

                <form method="POST">
                    <input type="hidden" name="acao" value="finalizar_compra">
                    <button type="submit" class="btn-compra" <?php echo $valor_total == 0 ? 'disabled' : ''; ?>>
                        ✅ Finalizar Compra
                    </button>
                </form>
            </div>

        <?php else: ?>
            <!-- Carrinho Vazio -->
            <div class="card-carrinho">
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <h3>Seu carrinho está vazio</h3>
                    <p>Adicione produtos da loja para iniciar uma compra</p>
                    <a href="hub.php" class="btn btn-primary" style="margin-top: 20px;">
                        ← Continuar Comprando
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
