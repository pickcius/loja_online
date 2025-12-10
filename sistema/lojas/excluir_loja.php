<?php
require_once "../config/conexao.php";
require_once "../config/urls.php";

$id = $_POST["id"];

try {
    // Iniciar transação para garantir integridade dos dados
    $pdo->beginTransaction();

    // Primeiro, deletar todos os registros de Estoque ligados a esta loja
    $sql_estoque = $pdo->prepare("DELETE FROM Estoque WHERE id_loja = ?");
    $sql_estoque->execute([$id]);

    // Depois, deletar a loja
    $sql_loja = $pdo->prepare("DELETE FROM Loja WHERE id = ?");
    $sql_loja->execute([$id]);

    // Confirmar a transação
    $pdo->commit();

    header("Location: " . LOJAS_URL . "lojas.php?excluido=1");
    exit;
} catch (PDOException $e) {
    // Reverter a transação em caso de erro
    $pdo->rollBack();
    echo "Erro ao excluir a loja: " . $e->getMessage();
    exit;
}
