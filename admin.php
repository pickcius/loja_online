<?php
require 'conexao.php';

// Função para buscar lojas
function getLojas($pdo) {
    return $pdo->query("SELECT * FROM Loja ORDER BY Nome")->fetchAll(PDO::FETCH_ASSOC);
}

// Inserir produto
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $data_lanc = $_POST['data_lanc'];
    $desconto = $_POST['desconto'];
    $lojas = $_POST['lojas'] ?? [];

    $stmt = $pdo->prepare("INSERT INTO Produtos (Nome, Preco, Descricao, Categoria, Data_de_Lancamento, Desconto) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $preco, $descricao, $categoria, $data_lanc, $desconto]);

    $prod_id = $pdo->lastInsertId();

    $stmtEstoque = $pdo->prepare("INSERT INTO Estoque (Loja, Produtos) VALUES (?, ?)");
    foreach ($lojas as $loja_id) {
        $stmtEstoque->execute([$loja_id, $prod_id]);
    }

    header('Location: index.php');
    exit;
}

// Editar produto
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $data_lanc = $_POST['data_lanc'];
    $desconto = $_POST['desconto'];
    $lojas = $_POST['lojas'] ?? [];

    $stmt = $pdo->prepare("UPDATE Produtos SET Nome=?, Preco=?, Descricao=?, Categoria=?, Data_de_Lancamento=?, Desconto=? WHERE idProdutos=?");
    $stmt->execute([$nome, $preco, $descricao, $categoria, $data_lanc, $desconto, $id]);

    // Atualizar vínculos Estoque
    $pdo->prepare("DELETE FROM Estoque WHERE Produtos = ?")->execute([$id]);
    $stmtEstoque = $pdo->prepare("INSERT INTO Estoque (Loja, Produtos) VALUES (?, ?)");
    foreach ($lojas as $loja_id) {
        $stmtEstoque->execute([$loja_id, $id]);
    }

    header('Location: index.php');
    exit;
}

// Excluir produto
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM Estoque WHERE Produtos = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM Produtos WHERE idProdutos = ?")->execute([$id]);
    header('Location: index.php');
    exit;
}

// Buscar produtos com lojas vinculadas
$produtos = $pdo->query("
  SELECT p.*, GROUP_CONCAT(l.Nome SEPARATOR ', ') AS Lojas
  FROM Produtos p
  LEFT JOIN Estoque e ON e.Produtos = p.idProdutos
  LEFT JOIN Loja l ON l.idLoja = e.Loja
  GROUP BY p.idProdutos
  ORDER BY p.idProdutos DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Buscar todas as lojas para o select
$lojas = getLojas($pdo);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Produtos e Lojas</title>
  <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
  <h1 class="mb-4">Painel Administrativo</h1>

  <!-- Botão para adicionar -->
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAdd">Adicionar Produto</button>

  <table class="table table-striped table-bordered align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Preço</th>
        <th>Descrição</th>
        <th>Categoria</th>
        <th>Data de Lançamento</th>
        <th>Desconto</th>
        <th>Lojas</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($produtos as $produto): ?>
        <tr>
          <td><?= $produto['idProdutos'] ?></td>
          <td><?= htmlspecialchars($produto['Nome']) ?></td>
          <td>R$ <?= number_format($produto['Preco'], 2, ',', '.') ?></td>
          <td><?= $produto['Descricao'] ?></td>
          <td><?= $produto['Categoria'] ?></td>
          <td><?= $produto['Data_de_Lancamento'] ?></td>
          <td><?= $produto['Desconto'] ?>%</td>
          <td><?= htmlspecialchars($produto['Lojas'] ?: 'Nenhuma') ?></td>
          <td>
            <button 
              class="btn btn-sm btn-warning" 
              data-bs-toggle="modal" 
              data-bs-target="#modalEdit" 
              data-id="<?= $produto['idProdutos'] ?>"
              data-nome="<?= htmlspecialchars($produto['Nome']) ?>"
              data-preco="<?= $produto['Preco'] ?>"
              data-descricao="<?= $produto['Descricao'] ?>"
              data-categoria="<?= $produto['Categoria'] ?>"
              data-data_lanc="<?= $produto['Data_de_Lancamento'] ?>"
              data-desconto="<?= $produto['Desconto'] ?>"
            >Editar</button>
            <a href="?delete=<?= $produto['idProdutos'] ?>" onclick="return confirm('Excluir este produto?');" class="btn btn-sm btn-danger">Excluir</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Modal Adicionar -->
<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <input type="hidden" name="action" value="add" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddLabel">Adicionar Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php include 'form_produto.php'; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" class="modal-content" id="formEdit">
      <input type="hidden" name="action" value="edit" />
      <input type="hidden" name="id" id="editId" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditLabel">Editar Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php include 'form_produto.php'; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script src="js/bootstrap.bundle.js"></script>
<script>
// Passar dados para o modal editar
var modalEdit = document.getElementById('modalEdit');
modalEdit.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;

  var id = button.getAttribute('data-id');
  var nome = button.getAttribute('data-nome');
  var preco = button.getAttribute('data-preco');
  var descricao = button.getAttribute('data-descricao');
  var categoria
