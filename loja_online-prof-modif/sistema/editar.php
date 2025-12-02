<?php
// editar.php
// Abre o formulário de edição de um produto
include 'conexao.php';

if (!isset($_POST['btnEditar'])) {
    die("Acesso inválido.");
}

$id = (int) $_POST['btnEditar'];

// Busca os dados do produto
$stmt = $pdo->prepare("SELECT * FROM Produto WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

// Busca uma linha de estoque relacionada a este produto (se houver)
$stmt2 = $pdo->prepare("SELECT * FROM Estoque WHERE id_produto = ? LIMIT 1");
$stmt2->execute([$id]);
$estoque = $stmt2->fetch(PDO::FETCH_ASSOC);

// Valores padrão caso não exista estoque ainda
$quantidade_disponivel = $estoque ? $estoque['quantidade_disponivel'] : '';
$id_loja_selecionada = $estoque ? $estoque['id_loja'] : null;

// Tenta obter opções do ENUM 'tipo' (se o seu DB/schema permitir)
$tipos = [];
try {
    // supondo que sua conexão/conexao.php define a variável $banco com o nome do schema
    $stmtTipo = $pdo->prepare("
        SELECT COLUMN_TYPE
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
        LIMIT 1
    ");
    $stmtTipo->execute([$banco ?? '', 'Produto', 'tipo']);
    $columnType = $stmtTipo->fetchColumn();
    if ($columnType) {
        preg_match_all("/'([^']+)'/", $columnType, $matches);
        $tipos = $matches[1];
    }
} catch (Exception $e) {
    // fallback se não conseguir ler do INFORMATION_SCHEMA
    $tipos = ['Novo','Usado','Promocao','Liquidacao','Outros'];
}

// Busca todas as lojas para popular o select
$stmtLoja = $pdo->query("SELECT * FROM Loja ORDER BY nome");
$lojas = $stmtLoja->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1>Editar produto: <?= htmlspecialchars($produto['nome']) ?></h1>

    <form action="atualizar.php" method="POST">
        <input type="hidden" name="id" value="<?= (int)$produto['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="txtNome" class="form-control" required value="<?= htmlspecialchars($produto['nome']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="txtDescricao" class="form-control" rows="3" required><?= htmlspecialchars($produto['descricao']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="number" step="0.01" name="txtPreco" class="form-control" required value="<?= htmlspecialchars($produto['preco']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="txtTipo" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= htmlspecialchars($t) ?>" <?= ($produto['tipo'] == $t) ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="txtCategoria" class="form-select" required>
                <option value="">Selecione...</option>
                <option value="Eletronico" <?= ($produto['categoria'] == 'Eletronico') ? 'selected' : '' ?>>Eletrônico</option>
                <option value="Telefonia" <?= ($produto['categoria'] == 'Telefonia') ? 'selected' : '' ?>>Telefonia</option>
                <option value="Informatica" <?= ($produto['categoria'] == 'Informatica') ? 'selected' : '' ?>>Informática</option>
                <option value="Eletrodomesticos" <?= ($produto['categoria'] == 'Eletrodomesticos') ? 'selected' : '' ?>>Eletrodomésticos</option>
                <option value="Acessorios" <?= ($produto['categoria'] == 'Acessorios') ? 'selected' : '' ?>>Acessórios</option>
                <option value="Outros" <?= ($produto['categoria'] == 'Outros') ? 'selected' : '' ?>>Outros</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Data de Lançamento</label>
            <input type="date" name="txtData" class="form-control" required value="<?= htmlspecialchars($produto['data_lancamento']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Desconto</label>
            <input type="number" step="0.01" name="txtDesconto" class="form-control" required value="<?= htmlspecialchars($produto['desconto_usados']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" name="txtQuantidade" class="form-control" required value="<?= htmlspecialchars($quantidade_disponivel) ?>">
            <div class="form-text">A quantidade é armazenada na tabela <code>Estoque</code>.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Loja (estoque)</label>
            <select name="txtLoja" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($lojas as $l): ?>
                    <option value="<?= (int)$l['id'] ?>" <?= ($id_loja_selecionada == $l['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($l['nome']).' - '.htmlspecialchars($l['cidade']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Selecione a loja que terá o estoque atualizado para este produto.</div>
        </div>

        <button type="submit" name="btnSalvar" class="btn btn-primary">Salvar</button>
        <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
