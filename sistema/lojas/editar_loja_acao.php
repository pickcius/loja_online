<?php
require_once "../config/conexao.php";
require_once "../config/urls.php";

$id = $_POST["id"];
$nome = $_POST["nome"];
$telefone = $_POST["telefone"];
$rua = $_POST["rua"];
$numero = $_POST["numero"];
$bairro = $_POST["bairro"];
$cep = $_POST["cep"];
$complemento = $_POST["complemento"];
$cidade = $_POST["cidade"];

$sql = $pdo->prepare("
    UPDATE Loja SET
        nome = ?,
        telefone = ?,
        rua = ?,
        numero = ?,
        bairro = ?,
        cep = ?,
        complemento = ?,
        cidade = ?
    WHERE id = ?
");

if ($sql->execute([$nome, $telefone, $rua, $numero, $bairro, $cep, $complemento, $cidade, $id])) {
    header("Location: " . LOJAS_URL . "lojas.php?editado=1");
    exit;
} else {
    echo "Erro ao editar loja.";
}
