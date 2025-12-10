<?php
    include '../config/conexao.php';
    include '../config/urls.php';
    $id = $_POST['btnExcluir'];

    // APAGAR produto-caracteristica SE EXISTIR
    $sql = $pdo->prepare("DELETE FROM Produto_caracteristica WHERE id_produto = ?");
    $sql->execute([$id]);

    // APAGAR estoque SE EXISTIR
    $sql = $pdo->prepare("DELETE FROM Estoque WHERE id_produto = ?");
    $sql->execute([$id]);

    // APAGAR O Produto
    $sql = $pdo->prepare("DELETE FROM Produto WHERE id = ?");
    $sql->execute([$id]);

    header("Location: " . PRODUTOS_URL . "produtos.php");
    exit;
?>