<?php
include 'conexao.php';

// Verifica se veio o POST
if (!isset($_POST['btnSalvar'])) {
    die("Acesso inválido!");
}

// Recebe os campos
$id         = $_POST['id'];
$nome       = $_POST['txtNome'];
$descricao  = $_POST['txtDescricao'];
$preco      = $_POST['txtPreco'];
$tipo       = $_POST['txtTipo'];
$categoria  = $_POST['txtCategoria'];
$data       = $_POST['txtData'];
$desconto   = $_POST['txtDesconto'];
$quantidade = $_POST['txtQuantidade'];
$loja       = $_POST['txtLoja'];

// Query de atualização
$sql = $pdo->prepare("
    UPDATE Produto 
    SET 
        nome = ?, 
        descricao = ?, 
        preco = ?, 
        tipo = ?, 
        categoria = ?, 
        data_lancamento = ?, 
        desconto_usados = ?, 
        quantidade_disponivel = ?, 
        id_loja = ?
    WHERE id = ?
");

$sql->execute([
    $nome,
    $descricao,
    $preco,
    $tipo,
    $categoria,
    $data,
    $desconto,
    $quantidade,
    $loja,
    $id
]);

// Redireciona de volta para a página principal
header("Location: produtos.php");  // OU principal.php, index.php — use a sua página correta!
exit;
?>
