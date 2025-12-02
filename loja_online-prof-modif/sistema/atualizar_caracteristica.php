<?php
include 'conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];

$sql = $pdo->prepare("UPDATE Caracteristica SET nome = ? WHERE id = ?");
$sql->execute([$nome, $id]);

header("Location: caracteristicas.php");
exit;
?>
