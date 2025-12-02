<?php
include 'conexao.php';

if (!isset($_POST['nome'])) {
    die("Erro: acesso inválido!");
}

$nome = $_POST['nome'];

$sql = $pdo->prepare("INSERT INTO Caracteristica (nome) VALUES (?)");
$sql->execute([$nome]);

header("Location: caracteristicas.php");
exit;
?>
