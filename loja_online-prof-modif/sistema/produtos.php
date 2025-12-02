<?php
include 'conexao.php';
$sql = $pdo->query("SELECT * FROM Produto");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>Página Principal</title>
</head>

<body>

    <div class="container">
        <h1>Página Principal</h1>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Data de Lançamento</th>
                    <th scope="col">Desconto</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <th scope="row"><?php echo $linha['id'] ?></th>
                        <td><?php echo $linha['nome'] ?></td>
                        <td><?php echo $linha['descricao'] ?></td>
                        <td><?php echo $linha['preco'] ?></td>
                        <td><?php echo $linha['tipo'] ?></td>
                        <td><?php echo $linha['categoria'] ?></td>
                        <td><?php
                            $partes = explode('-', $linha['data_lancamento']);
                            $data = "" . $partes[2] . "/" . $partes[1] . "/" . $partes[0];
                            echo $data ?>
                        </td>
                        <td><?php echo $linha['desconto_usados'] ?></td>
                        <td>
                            <form action="editar.php" method="POST">
                                <button class="btn btn-primary" name="btnEditar"
                                    value="<?php echo $linha['id']; ?>">Editar</button>
                            </form>
                        </td>

                        <td>
                            <form action="excluir.php" method="POST">
                                <button class="btn btn-danger" name="btnExcluir"
                                    value="<?php echo $linha['id']; ?>">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Botão + -->
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProdutoModal">
            +
        </button>

        <!-- Modal Bootstrap -->
        <div class="modal fade" id="addProdutoModal" tabindex="-1" aria-labelledby="addProdutoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="adicionar.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProdutoLabel">Adicionar Produto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="txtNome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="txtNome" name="txtNome" required>
                            </div>
                            <div class="mb-3">
                                <label for="txtDescricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="txtDescricao" name="txtDescricao" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="txtPreco" class="form-label">Preço</label>
                                <input type="number" step="0.01" class="form-control" id="txtPreco" name="txtPreco" required>
                            </div>
                            <div class="mb-3">
                                <label for="txtTipo" class="form-label">Tipo</label>
                                <select class="form-select" id="txtTipo" name="txtTipo" required>
                                    <option value="">Selecione...</option>
                                    <option value="Novo">Novo</option>
                                    <option value="Usado">Usado</option>
                                    <option value="Promocao">Promoção</option>
                                    <option value="Liquidacao">Liquidação</option>
                                    <option value="Outros">Outros</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="txtCategoria" class="form-label">Categoria</label>
                                <select class="form-select" id="txtCategoria" name="txtCategoria" required>
                                    <option value="">Selecione...</option>
                                    <option value="Eletronico">Eletrônico</option>
                                    <option value="Telefonia">Telefonia</option>
                                    <option value="Informatica">Informática</option>
                                    <option value="Eletrodomesticos">Eletrodomésticos</option>
                                    <option value="Acessorios">Acessórios</option>
                                    <option value="Outros">Outros</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="txtData" class="form-label">Data de Lançamento</label>
                                <input type="date" class="form-control" id="txtData" name="txtData" required>
                            </div>
                            <div class="mb-3">
                                <label for="txtDesconto" class="form-label">Desconto</label>
                                <input type="number" step="0.01" class="form-control" id="txtDesconto" name="txtDesconto" required>
                            </div>
                            <!-- Novo Campo de Quantidade -->
                            <div class="mb-3">
                                <label for="txtQuantidade" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="txtQuantidade" name="txtQuantidade" required>
                            </div>
                            <div class="mb-3">
                                <label for="txtLoja" class="form-label">Loja</label>
                                <select class="form-select" id="txtLoja" name="txtLoja" required>
                                    <option value="">Selecione...</option>
                                    <?php
                                    // Conecte-se ao banco de dados e recupere as lojas
                                    $sqlLoja = $pdo->query("SELECT * FROM Loja");
                                    while ($loja = $sqlLoja->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$loja['id']}'>{$loja['nome']} - {$loja['cidade']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="btnAdicionar" class="btn btn-primary">Adicionar Produto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Scripts Bootstrap (caso não tenha incluído ainda) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>