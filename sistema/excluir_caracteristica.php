<?php
include 'conexao.php';

$id = $_POST['id'];

$sql = $pdo->prepare("DELETE FROM Caracteristica WHERE id = ?");
$sql->execute([$id]);

header("Location: caracteristicas.php");
exit;
?>
