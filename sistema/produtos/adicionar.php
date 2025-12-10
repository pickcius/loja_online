<?php
include '../config/conexao.php';
include '../config/urls.php';

if (isset($_POST['txtNome']) && isset($_POST['txtDescricao']) && isset($_POST['txtPreco']) && isset($_POST['txtTipo']) && isset($_POST['txtCategoria']) && isset($_POST['txtData']) && isset($_POST['txtDesconto']) && isset($_POST['txtLoja'])) {

    $nome = $_POST['txtNome'];
    $descricao = $_POST['txtDescricao'];
    $preco = $_POST['txtPreco'];
    $tipo = $_POST['txtTipo'];
    $categoria = $_POST['txtCategoria'];
    $data = $_POST['txtData'];
    $desconto = $_POST['txtDesconto'];
    $idLoja = $_POST['txtLoja'];

    try {
        // Inserir o produto na tabela Produto
        $sqlProduto = $pdo->prepare("INSERT INTO Produto (nome, descricao, preco, tipo, categoria, data_lancamento, desconto_usados) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $sqlProduto->execute([$nome, $descricao, $preco, $tipo, $categoria, $data, $desconto]);

        // Pegar o ID do produto recém-adicionado
        $produtoId = $pdo->lastInsertId();

        // Inserir na tabela Estoque
        $quantidade = 10; // Quantidade padrão, pode ser alterada se necessário
        $sqlEstoque = $pdo->prepare("INSERT INTO Estoque (id_produto, id_loja, quantidade_disponivel) VALUES (?, ?, ?)");
        $sqlEstoque->execute([$produtoId, $idLoja, $quantidade]);

        // Redirecionar após o sucesso
        header("Location: " . PRODUTOS_URL . "produtos.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao inserir o produto: " . $e->getMessage();
    }
}
?>


<?php /* codigo antigo funcional 
include 'conexao.php';

$nome = $_POST['txtNome'];
$descricao = $_POST['txtDescricao'];
$preco = $_POST['txtPreco'];
$tipo = $_POST['txtTipo'];
$categoria = $_POST['txtCategoria'];
$data = $_POST['txtData'];
$desconto = $_POST['txtDesconto'];

try {
    // SQL corrigido com o número correto de placeholders (7 placeholders)
    $sql = $pdo->prepare("INSERT INTO Produto (nome, descricao, preco, tipo, categoria, data_lancamento, desconto_usados)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Executa a query passando os valores
    $sql->execute([$nome, $descricao, $preco, $tipo, $categoria, $data, $desconto]);

    // Redireciona para a página de produtos após o sucesso
    header("Location: produtos.php");
    exit;
} catch (PDOException $e) {
    // Captura erros de SQL e exibe uma mensagem amigável
    echo "Erro ao inserir o produto: " . $e->getMessage();
}*/
?>


<?php /* antigo codigo
    include 'conexao.php';

    $nome = $_POST['txtNome'];
    $descricao = $_POST['txtDescricao'];
    $preco = $_POST['txtPreco'];
    $tipo = $_POST['txtTipo'];
    $categoria = $_POST['txtCategoria'];
    $data = $_POST['txtData'];
    $desconto = $_POST['txtDesconto'];

    $sql = $pdo->prepare("INSERT INTO Produto (nome, descricao, 
    preco, tipo, categoria, data_lancamento, desconto_usados)
    VALUES (?, ?, ?)");

    $sql->execute([$nome, $descricao, $preco, $tipo, $categoria, $data, $desconto]);

    header("Location: produtos.php");
    exit;
    */
?>