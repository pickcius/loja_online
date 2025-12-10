<?php
include '../config/conexao.php';
include '../config/urls.php';
$sql = $pdo->query("SELECT * FROM Caracteristica ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>bootstrap.css">
    <title>Características</title>
</head>
<body>

<div class="container mt-4">

    <h1>Características</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome da Característica</th>
                <th>Descrição</th>
                <th>Editar</th>
                <th>Excluir</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?= $linha['id'] ?></td>
                <td><?= $linha['nome'] ?></td>
                <td><?= $linha['descricao'] ?></td>

                <td>
                    <form action="editar_caracteristica.php" method="POST">
                        <button class="btn btn-primary" name="id" value="<?= $linha['id'] ?>">
                            Editar
                        </button>
                        <input type="hidden" name="descricao" value="<?= $linha['descricao'] ?>">
                    </form>
                </td>

                <td>
                    <form action="excluir_caracteristica.php" method="POST">
                        <button class="btn btn-danger" name="id" value="<?= $linha['id'] ?>">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Botão + -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">+</button>

    <!-- Modal adicionar -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="adicionar_caracteristica.php" method="POST">

            <div class="modal-header">
                <h5 class="modal-title">Adicionar Característica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control mb-3" required>

                <label>Descrição</label>
                <textarea name="descricao" class="form-control" rows="3"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </div>

            </form>

        </div>
    </div>
</div>


    <div class="mt-3">
        <a href="<?php echo ADMIN_URL; ?>admin_index.php" class="btn btn-outline-primary">➤ Voltar</a>
    </div>

</div>

<script src="../../js/bootstrap.bundle.js"></script>

</body>
</html>
