<?php 
    include 'conexao.php';

    // Verifica se a conexão existe
    if (!isset($pdo)) {
        die("Erro: conexão com banco de dados não estabelecida.");
    }

    // Se clicou no botão buscar
    if (isset($_POST['btnBuscar'])) {
        $cidade = $_POST['txtCidade'] ?? '';

        $sql = $pdo->prepare("SELECT * FROM viewProdutosLoja 
                              WHERE cidade LIKE ?");
        $sql->execute(['%' . $cidade . '%']);

    } else {
        $sql = $pdo->query("SELECT * FROM viewProdutosLoja");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>Página Principal</title>
</head>

<body>

    <div class="container mt-4">
        <h1>Página Principal</h1>

        <form action="produtosLoja.php" method="POST">

            <!-- Campo de busca (agora com NAME correto, se quiser buscar produtos depois) -->
            <input type="text" name="txtProduto" placeholder="Buscar produto..." class="form-control mb-3">

            <!-- Campo cidade -->
            <input type="text" name="txtCidade" placeholder="Buscar por cidade..." class="form-control mb-3">

            <input type="submit" value="Buscar" name="btnBuscar" class="btn btn-primary mb-3">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Tipo</th>
                        <th>Categoria</th>
                        <th>Data de Lançamento</th>
                        <th>Desconto</th>
                        <th>Quantidade</th>
                        <th>Cidade</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                        while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <tr>
                            <td><?= $linha['nomeprod'] ?></td>
                            <td><?= $linha['descricao'] ?></td>
                            <td><?= $linha['preco'] ?></td>
                            <td><?= $linha['tipo'] ?></td>
                            <td><?= $linha['categoria'] ?></td>

                            <!-- Ajuste da data com verificação -->
                            <td>
                                <?php 
                                    if (!empty($linha['data_lancamento'])) {
                                        $partes = explode('-', $linha['data_lancamento']);
                                        echo "{$partes[2]}/{$partes[1]}/{$partes[0]}";
                                    } else {
                                        echo "—";
                                    }
                                ?>
                            </td>

                            <td><?= $linha['desconto_usados'] ?></td>
                            <td><?= $linha['quantidade_disponivel'] ?></td>
                            <td><?= $linha['cidade'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </form>
    </div>

</body>
</html>
