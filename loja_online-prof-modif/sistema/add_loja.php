<?php
require_once "conexao.php";

$nome = $_POST["nome"];
$telefone = $_POST["telefone"];
$rua = $_POST["rua"];
$numero = $_POST["numero"];
$bairro = $_POST["bairro"];
$cep = $_POST["cep"];
$complemento = $_POST["complemento"];
$cidade = $_POST["cidade"];

$sql = "INSERT INTO Loja (nome, telefone, rua, numero, bairro, cep, complemento, cidade)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$nome, $telefone, $rua, $numero, $bairro, $cep, $complemento, $cidade]);

header("Location: lojas.php?sucesso=1");
exit;
