<?php
require_once "../config/conexao.php";
require_once "../config/urls.php";

$id = $_POST["id"];

$sql = $pdo->prepare("DELETE FROM Loja WHERE id = ?");
$sql->execute([$id]);

header("Location: " . LOJAS_URL . "lojas.php?excluido=1");
exit;
