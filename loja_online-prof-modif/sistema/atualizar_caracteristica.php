<?php
include 'conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];

$sql = $pdo->prepare("UPDATE Caracteristica SET nome = ?, descricao = ? WHERE id = ?");
$sql->execute([$nome, $descricao, $id]);

header("Location: caracteristicas.php");
exit;
?>
