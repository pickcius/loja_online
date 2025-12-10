<?php
// Conteúdo para relatórios
include 'conexao.php';

// 1. Média dos precos de todos os produtos
$sql = $pdo->query("SELECT TRUNCATE(AVG(preco), 2) as media FROM Produto");
$mediaPrecoProdutos = $sql->fetch(PDO::FETCH_ASSOC)['media'];

// 2. Média dos preços com desconto
$sql = "SELECT AVG(preco) AS Media_Precos_Com_Desconto FROM Produto WHERE desconto_usados > 0";
$stmt = $pdo->query($sql);
$dado = $stmt->fetch(PDO::FETCH_ASSOC);
$mediaDesconto = $dado['Media_Precos_Com_Desconto'] ?? 0;

// 3. Produto mais caro e mais barato
$sql = "SELECT nome, preco, 'Mais Barato' AS Tipo FROM Produto WHERE preco = (SELECT MIN(preco) FROM Produto)
UNION
SELECT nome, preco, 'Mais Caro' AS Tipo FROM Produto WHERE preco = (SELECT MAX(preco) FROM Produto)";

$stmt = $pdo->query($sql);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tabelaMaisBaratoCaro = ""; // variável que vai guardar a tabela

if (count($dados) > 0) {

    $tabelaMaisBaratoCaro .= "<table class='table table-bordered table-striped'>";
    $tabelaMaisBaratoCaro .= "<thead><tr><th>Nome</th><th>Preço</th><th>Tipo</th></tr></thead><tbody>";

    foreach ($dados as $row) {
        $tabelaMaisBaratoCaro .= "<tr>
                                    <td>{$row['nome']}</td>
                                    <td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>
                                    <td>{$row['Tipo']}</td>
                                  </tr>";
    }

    $tabelaMaisBaratoCaro .= "</tbody></table>";
} else {
    $tabelaMaisBaratoCaro = "<p>Nenhum resultado encontrado.</p>";
}

// 4. Preço máximo entre eletrônicos
$sql = "SELECT MAX(preco) AS Preco_Maximo_Eletronico 
        FROM Produto 
        WHERE FIND_IN_SET('Eletronico', categoria)";

$stmt = $pdo->query($sql);
$dado = $stmt->fetch(PDO::FETCH_ASSOC);

$precoMaxEletronico = "<p><strong>Preço máximo:</strong> R$ " .
    number_format($dado['Preco_Maximo_Eletronico'], 2, ',', '.') .
    "</p>";

// 5. Produtos lançados há mais de 6 meses
$sql = "SELECT * FROM Produto 
        WHERE data_lancamento < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";

$stmt = $pdo->query($sql);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tabelaProdutosAntigos = "";

if (count($dados) > 0) {

    $tabelaProdutosAntigos .= "<table class='table table-bordered table-striped'>";
    $tabelaProdutosAntigos .= "<thead>
                                  <tr>
                                      <th>ID</th>
                                      <th>Nome</th>
                                      <th>Preço</th>
                                      <th>Data Lançamento</th>
                                  </tr>
                               </thead><tbody>";

    foreach ($dados as $row) {
        $tabelaProdutosAntigos .= "<tr>
                                      <td>{$row['id']}</td>
                                      <td>{$row['nome']}</td>
                                      <td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>
                                      <td>{$row['data_lancamento']}</td>
                                   </tr>";
    }

    $tabelaProdutosAntigos .= "</tbody></table>";
} else {
    $tabelaProdutosAntigos = "<p>Nenhum resultado.</p>";
}

// 6. Listar características resumidas
$sql = "SELECT nome AS Nome_Caracteristica, 
               LEFT(descricao, 30) AS Descricao_Resumida 
        FROM Caracteristica";

$stmt = $pdo->query($sql);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tabelaCaracteristicas = "";

if (count($dados) > 0) {

    $tabelaCaracteristicas .= "<table class='table table-bordered table-striped'>";
    $tabelaCaracteristicas .= "<thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrição Resumida</th>
                                </tr>
                               </thead><tbody>";

    foreach ($dados as $row) {
        $tabelaCaracteristicas .= "<tr>
                                      <td>{$row['Nome_Caracteristica']}</td>
                                      <td>{$row['Descricao_Resumida']}</td>
                                   </tr>";
    }

    $tabelaCaracteristicas .= "</tbody></table>";
} else {
    $tabelaCaracteristicas = "<p>Nenhum resultado.</p>";
}

// 7. Contar caracteres de descrição
$sql = "SELECT nome AS Nome_Produto, 
               CHAR_LENGTH(descricao) AS Qtde_Caracteres_Descricao 
        FROM Produto";

$stmt = $pdo->query($sql);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tabelaCaracteresDescricao = "";

if (count($dados) > 0) {

    $tabelaCaracteresDescricao .= "<table class='table table-bordered table-striped'>";
    $tabelaCaracteresDescricao .= "<thead>
                                    <tr>
                                        <th>Nome do Produto</th>
                                        <th>Qtde Caracteres</th>
                                    </tr>
                                   </thead><tbody>";

    foreach ($dados as $row) {
        $tabelaCaracteresDescricao .= "<tr>
                                          <td>{$row['Nome_Produto']}</td>
                                          <td>{$row['Qtde_Caracteres_Descricao']}</td>
                                       </tr>";
    }

    $tabelaCaracteresDescricao .= "</tbody></table>";
} else {
    $tabelaCaracteresDescricao = "<p>Nenhum resultado.</p>";
}
?>