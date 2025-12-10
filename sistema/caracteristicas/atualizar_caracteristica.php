<?php
include '../config/conexao.php';
include '../config/urls.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];

$sql = $pdo->prepare("UPDATE Caracteristica SET nome = ?, descricao = ? WHERE id = ?");
$sql->execute([$nome, $descricao, $id]);

header("Location: " . CARACTERISTICAS_URL . "caracteristicas.php");
exit;
?>
