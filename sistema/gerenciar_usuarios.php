<?php
require_once 'verificar_autenticacao.php';
verificar_admin();
require_once 'conexao.php';

$mensagem = "";
$tipo_mensagem = "";

// Deletar usuário (cliente)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'deletar') {
    $id_cliente = intval($_POST['id_cliente']);
    
    try {
        $sql = "DELETE FROM Cliente WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_cliente]);
        
        $mensagem = "Cliente deletado com sucesso!";
        $tipo_mensagem = "success";
    } catch (PDOException $e) {
        $mensagem = "Erro ao deletar cliente!";
        $tipo_mensagem = "danger";
    }
}

// Obter lista de clientes
try {
    $sql = "SELECT id, nome, email, telefone, data_cadastro FROM Cliente ORDER BY data_cadastro DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $clientes = [];
    $mensagem = "Erro ao carregar clientes!";
    $tipo_mensagem = "danger";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
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
        }

        .badge-admin {
            background-color: #ffc107;
            color: #000;
            font-weight: 600;
        }

        .badge-cliente {
            background-color: #17a2b8;
            color: white;
            font-weight: 600;
        }

        .table-responsive {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-action {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">🛍️ Loja Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Painel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="gerenciar_usuarios.php">Clientes</a>
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

    <div class="container py-5">
        <h1 class="mb-4">👥 Gerenciar Clientes</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($mensagem); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($clientes) > 0): ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($cliente['nome']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefone'] ?? '-'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($cliente['data_cadastro'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-action btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeletar" onclick="document.getElementById('id_cliente_deletar').value = <?php echo $cliente['id']; ?>;">
                                        Deletar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Nenhum cliente cadastrado
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">← Voltar ao Painel</a>
        </div>
    </div>

    <!-- Modal de Confirmação de Deleção -->
    <div class="modal fade" id="modalDeletar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar Deleção</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja deletar este cliente? Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="acao" value="deletar">
                        <input type="hidden" name="id_cliente" id="id_cliente_deletar" value="">
                        <button type="submit" class="btn btn-danger">Deletar Permanentemente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
