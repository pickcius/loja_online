<?php
include '../config/conexao.php';
include '../config/urls.php';

$id = $_POST['id'];

$sql = $pdo->prepare("DELETE FROM Caracteristica WHERE id = ?");
$sql->execute([$id]);

header("Location: " . CARACTERISTICAS_URL . "caracteristicas.php");
exit;
?>
