<?php
include 'conexao.php';

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];

$sql = $pdo->prepare("INSERT INTO Caracteristica (nome, descricao) VALUES (?, ?)");
$sql->execute([$nome, $descricao]);

header("Location: caracteristicas.php");
exit;
?>
