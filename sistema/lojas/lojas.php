<?php
include '../config/conexao.php';
include '../config/urls.php';
$sql = $pdo->query("SELECT * FROM Loja ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>bootstrap.css">
    <title>Lojas</title>
</head>

<body>

<div class="container mt-4">
    <h1>Lojas Cadastradas</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Cidade</th>
                <th>Endereço</th>
                <th>Editar</th>
                <th>Excluir</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?= $linha['id'] ?></td>
                    <td><?= $linha['nome'] ?></td>
                    <td><?= $linha['telefone'] ?></td>
                    <td><?= $linha['cidade'] ?></td>
                    <td>
                        <?= $linha['rua'] ?>, <?= $linha['numero'] ?> - <?= $linha['bairro'] ?>  
                    </td>

                    <td>
                        <form action="editar_loja.php" method="POST">
                            <button class="btn btn-primary" name="id" value="<?= $linha['id'] ?>">Editar</button>
                        </form>
                    </td>

                    <td>
                        <form action="excluir_loja.php" method="POST">
                            <button class="btn btn-danger" name="id" value="<?= $linha['id'] ?>">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Botão + -->
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addLojaModal">
        +
    </button>

    <!-- Modal -->
    <div class="modal fade" id="addLojaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="add_loja.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Loja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nome da Loja</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Telefone</label>
                                <input type="text" name="telefone" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Rua</label>
                                <input type="text" name="rua" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Número</label>
                                <input type="number" name="numero" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Bairro</label>
                                <input type="text" name="bairro" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>CEP</label>
                                <input type="text" name="cep" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Complemento</label>
                                <input type="text" name="complemento" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Cidade</label>
                                <input type="text" name="cidade" class="form-control" required>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="mt-3">
    <a href="<?php echo ADMIN_URL; ?>admin_index.php" class="btn btn-outline-primary">
        ⬅ Voltar
    </a>
</div>

</div>



<script src="../../js/bootstrap.bundle.js"></script>

</body>
</html>
