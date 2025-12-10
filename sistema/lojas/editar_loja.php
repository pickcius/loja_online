<?php
require_once "../config/conexao.php";
require_once "../config/urls.php";

$id = $_POST["id"];

$sql = $pdo->prepare("SELECT * FROM Loja WHERE id = ?");
$sql->execute([$id]);
$loja = $sql->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>bootstrap.css">
    <title>Editar Loja</title>
</head>
<body>

<div class="container mt-4">
    <h2>Editar Loja</h2>

    <form action="editar_loja_acao.php" method="POST">

        <input type="hidden" name="id" value="<?= $loja['id'] ?>">

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="<?= $loja['nome'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?= $loja['telefone'] ?>">
        </div>

        <div class="mb-3">
            <label>Rua</label>
            <input type="text" name="rua" class="form-control" value="<?= $loja['rua'] ?>">
        </div>

        <div class="mb-3">
            <label>Número</label>
            <input type="text" name="numero" class="form-control" value="<?= $loja['numero'] ?>">
        </div>

        <div class="mb-3">
            <label>Bairro</label>
            <input type="text" name="bairro" class="form-control" value="<?= $loja['bairro'] ?>">
        </div>

        <div class="mb-3">
            <label>CEP</label>
            <input type="text" name="cep" class="form-control" value="<?= $loja['cep'] ?>">
        </div>

        <div class="mb-3">
            <label>Complemento</label>
            <input type="text" name="complemento" class="form-control" value="<?= $loja['complemento'] ?>">
        </div>

        <div class="mb-3">
            <label>Cidade</label>
            <input type="text" name="cidade" class="form-control" value="<?= $loja['cidade'] ?>">
        </div>

        <button class="btn btn-primary">Salvar</button>
        <a href="<?php echo LOJAS_URL; ?>lojas.php" class="btn btn-secondary">Cancelar</a>

    </form>
</div>

</body>
</html>
