<?php
require_once "conexao.php";

$id = $_POST["id"];

$sql = $pdo->prepare("DELETE FROM Loja WHERE id = ?");
$sql->execute([$id]);

header("Location: lojas.php?excluido=1");
exit;
