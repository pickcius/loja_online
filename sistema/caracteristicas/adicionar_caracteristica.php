<?php
include '../config/conexao.php';
include '../config/urls.php';

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];

$sql = $pdo->prepare("INSERT INTO Caracteristica (nome, descricao) VALUES (?, ?)");
$sql->execute([$nome, $descricao]);

header("Location: " . CARACTERISTICAS_URL . "caracteristicas.php");
exit;
?>
