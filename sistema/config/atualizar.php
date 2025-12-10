<?php
// atualizar.php
include 'conexao.php';
include 'urls.php';

if (!isset($_POST['btnSalvar'])) {
    die("Acesso inválido.");
}

// Recebe e valida inputs (pode adicionar mais validações se quiser)
$id         = (int) $_POST['id'];
$nome       = trim($_POST['txtNome']);
$descricao  = trim($_POST['txtDescricao']);
$preco      = (float) $_POST['txtPreco'];
$tipo       = trim($_POST['txtTipo']);
$categoria  = trim($_POST['txtCategoria']);
$data       = trim($_POST['txtData']);
$desconto   = (float) $_POST['txtDesconto'];
$quantidade = (int) $_POST['txtQuantidade'];
$loja       = (int) $_POST['txtLoja'];

try {
    $pdo->beginTransaction();

    // Atualiza a tabela Produto
    $sql = $pdo->prepare("
        UPDATE Produto
        SET nome = ?, descricao = ?, preco = ?, tipo = ?, categoria = ?, data_lancamento = ?, desconto_usados = ?
        WHERE id = ?
    ");
    $sql->execute([
        $nome,
        $descricao,
        $preco,
        $tipo,
        $categoria,
        $data,
        $desconto,
        $id
    ]);

    // Verifica se já existe um registro de estoque para esse produto e loja
    $check = $pdo->prepare("SELECT id FROM Estoque WHERE id_produto = ? AND id_loja = ? LIMIT 1");
    $check->execute([$id, $loja]);
    $row = $check->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Atualiza a quantidade no estoque existente
        $upd = $pdo->prepare("UPDATE Estoque SET quantidade_disponivel = ? WHERE id = ?");
        $upd->execute([$quantidade, $row['id']]);
    } else {
        // Se não existir, insere um novo registro de estoque para esse produto/loja
        $ins = $pdo->prepare("INSERT INTO Estoque (id_produto, id_loja, quantidade_disponivel) VALUES (?, ?, ?)");
        $ins->execute([$id, $loja, $quantidade]);
    }

    $pdo->commit();
    header("Location: " . PRODUTOS_URL . "produtos.php?editado=1");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    // Em produção não exiba a mensagem completa; aqui mostramos para depuração.
    echo "Erro ao atualizar: " . $e->getMessage();
    exit;
}
