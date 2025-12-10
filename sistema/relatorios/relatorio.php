<?php require_once __DIR__ . "/../config/conexao.php"; 
require_once __DIR__ . "/../config/urls.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatórios de Produtos</title>

    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo CSS_URL; ?>bootstrap.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4">Relatórios de Produtos</h1>

        <!-- 1️⃣ Contar caracteres de descrição -->
        <h3>1. Quantidade de caracteres nas descrições</h3>
        <?php
        $sql = "SELECT nome AS Nome_Produto, CHAR_LENGTH(descricao) AS Qtde_Caracteres_Descricao FROM Produto";
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($dados) > 0) {
            echo "<table class='table table-bordered table-striped'><thead><tr><th>Nome do Produto</th><th>Qtde Caracteres</th></tr></thead><tbody>";
            foreach ($dados as $row) {
                echo "<tr><td>{$row['Nome_Produto']}</td><td>{$row['Qtde_Caracteres_Descricao']}</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Nenhum resultado.</p>";
        }
        ?>

        <!-- 2️⃣ Produto mais caro e mais barato -->
        <h3>2. Produto mais caro e mais barato</h3>
        <?php
        $sql = "SELECT nome, preco, 'Mais Barato' AS Tipo FROM Produto WHERE preco = (SELECT MIN(preco) FROM Produto)
            UNION
            SELECT nome, preco, 'Mais Caro' AS Tipo FROM Produto WHERE preco = (SELECT MAX(preco) FROM Produto)";
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($dados) > 0) {
            echo "<table class='table table-bordered table-striped'><thead><tr><th>Nome</th><th>Preço</th><th>Tipo</th></tr></thead><tbody>";
            foreach ($dados as $row) {
                echo "<tr><td>{$row['nome']}</td><td>R$ {$row['preco']}</td><td>{$row['Tipo']}</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Nenhum resultado.</p>";
        }
        ?>

        <!-- 3️⃣ Preço máximo entre eletrônicos -->
        <h3>3. Maior preço entre produtos eletrônicos</h3>
        <?php
        $sql = "SELECT MAX(preco) AS Preco_Maximo_Eletronico FROM Produto WHERE FIND_IN_SET('Eletronico', categoria)";
        $stmt = $pdo->query($sql);
        $dado = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p><strong>Preço máximo:</strong> R$ {$dado['Preco_Maximo_Eletronico']}</p>";
        ?>

        <!-- 4️⃣ Listar características resumidas -->
        <h3>4. Características resumidas (30 caracteres)</h3>
        <?php
        $sql = "SELECT nome AS Nome_Caracteristica, LEFT(descricao, 30) AS Descricao_Resumida FROM Caracteristica";
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($dados) > 0) {
            echo "<table class='table table-bordered table-striped'><thead><tr><th>Nome</th><th>Descrição Resumida</th></tr></thead><tbody>";
            foreach ($dados as $row) {
                echo "<tr><td>{$row['Nome_Caracteristica']}</td><td>{$row['Descricao_Resumida']}</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Nenhum resultado.</p>";
        }
        ?>

        <!-- 5️⃣ Produtos lançados há mais de 6 meses -->
        <h3>5. Produtos lançados há mais de 6 meses</h3>
        <?php
        $sql = "SELECT * FROM Produto WHERE data_lancamento < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($dados) > 0) {
            echo "<table class='table table-bordered table-striped'><thead><tr><th>ID</th><th>Nome</th><th>Preço</th><th>Data Lançamento</th></tr></thead><tbody>";
            foreach ($dados as $row) {
                echo "<tr><td>{$row['id']}</td><td>{$row['nome']}</td><td>R$ {$row['preco']}</td><td>{$row['data_lancamento']}</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Nenhum resultado.</p>";
        }
        ?>

        <!-- 6️⃣ Média de preços -->
        <h3>6. Média dos preços de todos os produtos</h3>
        <?php
        $sql = "SELECT AVG(preco) AS Media_Geral_Produtos FROM Produto";
        $stmt = $pdo->query($sql);
        $dado = $stmt->fetch(PDO::FETCH_ASSOC);
        $mediaGeral = $dado['Media_Geral_Produtos'] ?? 0;
        echo "<p><strong>Média Geral:</strong> R$ " . number_format($mediaGeral, 2, ',', '.') . "</p>";
        ?>

        <!-- 7️⃣ Média dos preços com desconto -->
        <h3>7. Média de preços dos produtos com desconto</h3>
        <?php
        $sql = "SELECT AVG(preco) AS Media_Precos_Com_Desconto FROM Produto WHERE desconto_usados > 0";
        $stmt = $pdo->query($sql);
        $dado = $stmt->fetch(PDO::FETCH_ASSOC);
        $mediaDesconto = $dado['Media_Precos_Com_Desconto'] ?? 0;
        echo "<p><strong>Média com desconto:</strong> R$ " . number_format($mediaDesconto, 2, ',', '.') . "</p>";
        ?>

    </div>

    <!-- Bootstrap JS -->
    <script src="<?php echo JS_URL; ?>bootstrap.bundle.js"></script>
</body>

</html>