<?php
require 'conexao.php';

$stmt = $pdo->query("SELECT idLoja, Nome FROM Loja ORDER BY Nome");
$lojas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($lojas);
?>
