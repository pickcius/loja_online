<?php
include 'conexao.php';
$id = $_POST['btnEditar']; // Pega o ID do produto que será editado

// Consulta para pegar os dados do produto
$sql = $pdo->prepare("SELECT * FROM Produto WHERE id = ?");
$sql->execute([$id]);
$linha = $sql->fetch(PDO::FETCH_ASSOC);

// Consulta para obter os valores possíveis para o campo 'tipo' do ENUM
$sqlTipo = $pdo->prepare("SELECT COLUMN_TYPE 
                          FROM INFORMATION_SCHEMA.COLUMNS 
                          WHERE TABLE_SCHEMA = ? 
                          AND TABLE_NAME = ? 
                          AND COLUMN_NAME = ?");
$sqlTipo->execute([$banco, 'produto', 'tipo']);
$columnType = $sqlTipo->fetchColumn();

// Extrair as opções do ENUM
preg_match_all("/'([^']+)'/", $columnType, $matches);
$tipos = $matches[1]; // Extrai os valores do ENUM
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>Editar Produto</title>
</head>
<body>
    <div class="container my-4">
        <h1>Editar o produto: <?php echo $linha['nome']?></h1>
        <!-- Formulário de Edição com Modal -->
        <form action="atualizar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $linha['id']?>" class="form-control">

            <div class="mb-3">
                <label for="txtNome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="txtNome" name="txtNome" value="<?php echo $linha['nome']?>" required />
            </div>

            <div class="mb-3">
                <label for="txtDescricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="txtDescricao" name="txtDescricao" rows="3" required><?php echo $linha['descricao']?></textarea>
            </div>

            <div class="mb-3">
                <label for="txtPreco" class="form-label">Preço</label>
                <input type="number" step="0.01" class="form-control" id="txtPreco" name="txtPreco" value="<?php echo $linha['preco']?>" required />
            </div>

            <!-- Campo Tipo com as opções do ENUM -->
            <div class="mb-3">
                <label for="txtTipo" class="form-label">Tipo</label>
                <select class="form-select" id="txtTipo" name="txtTipo" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?php echo $tipo; ?>" <?php if($linha['tipo'] == $tipo) echo 'selected'; ?>>
                            <?php echo $tipo; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="txtCategoria" class="form-label">Categoria</label>
                <select class="form-select" id="txtCategoria" name="txtCategoria" required>
                    <option value="">Selecione...</option>
                    <option value="Eletronico" <?php if($linha['categoria'] == 'Eletronico') echo 'selected'; ?>>Eletrônico</option>
                    <option value="Telefonia" <?php if($linha['categoria'] == 'Telefonia') echo 'selected'; ?>>Telefonia</option>
                    <option value="Informatica" <?php if($linha['categoria'] == 'Informatica') echo 'selected'; ?>>Informática</option>
                    <option value="Eletrodomesticos" <?php if($linha['categoria'] == 'Eletrodomesticos') echo 'selected'; ?>>Eletrodomésticos</option>
                    <option value="Acessorios" <?php if($linha['categoria'] == 'Acessorios') echo 'selected'; ?>>Acessórios</option>
                    <option value="Outros" <?php if($linha['categoria'] == 'Outros') echo 'selected'; ?>>Outros</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="txtData" class="form-label">Data de Lançamento</label>
                <input type="date" class="form-control" id="txtData" name="txtData" value="<?php echo $linha['data_lancamento']?>" required />
            </div>

            <div class="mb-3">
                <label for="txtDesconto" class="form-label">Desconto</label>
                <input type="number" step="0.01" class="form-control" id="txtDesconto" name="txtDesconto" value="<?php echo $linha['desconto_usados']?>" required />
            </div>

            <!-- Novo Campo de Quantidade -->
            <div class="mb-3">
                <label for="txtQuantidade" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="txtQuantidade" name="txtQuantidade" value="<?php echo $linha['quantidade_disponivel']?>" required />
            </div>

            <!-- Loja Selecionada -->
            <div class="mb-3">
                <label for="txtLoja" class="form-label">Loja</label>
                <select class="form-select" id="txtLoja" name="txtLoja" required>
                    <option value="">Selecione...</option>
                    <?php
                    // Conecte-se ao banco de dados e recupere as lojas
                    $sqlLoja = $pdo->query("SELECT * FROM Loja");
                    while ($loja = $sqlLoja->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($linha['id_loja'] == $loja['id']) ? 'selected' : '';
                        echo "<option value='{$loja['id']}' $selected>{$loja['nome']} - {$loja['cidade']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" name="btnSalvar" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</body>
</html>


<?php /* codigo antigo
include 'conexao.php';
$id = $_POST['btnEditar'];
$sql = $pdo->prepare("SELECT * FROM Produto WHERE id = ?");
$sql->execute([$id]);
$linha = $sql->fetch(PDO::FETCH_ASSOC);


$sql = $pdo->prepare("SELECT COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = ?
  AND TABLE_NAME = ?
  AND COLUMN_NAME = ?;");

$sql->execute([$banco, 'produto', 'tipo']);
$colunaTipo = $sql->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>Editar Aluno</title>
</head>
<body>
    <div class="container my-4">
        <h1>Editar o produto: <?php echo $linha['nome']?></h1>
        <form action="atualizar.php" method="POST">
            <input type="hidden" name="id"
            value="<?php echo $linha['id']?>" class="form-control">

            <input type="text" name="nome" 
            value="<?php echo $linha['nome']?>" class="form-control">

            <input type="text" name="descricao"
            value="<?php echo $linha['descricao']?>" class="form-control">

            <input type="text" name="preco"
            value="<?php echo $linha['preco']?>" class="form-control">

            <?php 
                while($linha['tipo'] = $selectTipo->fetch(PDO::FETCH_ASSOC)){
            ?>
            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                <option selected>Selecione um tipo...</option>
                <option value="1">One</option>
            </select>
            <?php } ?>

            <input type="text" name="tipo"
            value="<?php echo $linha['tipo']?>" class="form-control">
            
            <input type="text" name="categoria"
            value="<?php echo $linha['categoria']?>" class="form-control">

            <input type="date" name="data"
            value="<?php echo $linha['data_lancamento']?>" class="form-control">

            <input type="text" name="desconto"
            value="<?php echo $linha['desconto_usados']?>" class="form-control">

            <input type="submit" name="btnSalvar" value="Salvar"
            class="btn btn-primary">
        </form>
    </div>

</body>
</html>
*/
