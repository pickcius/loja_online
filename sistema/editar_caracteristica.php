<?php
include 'conexao.php';

$id = $_POST['id'];
$sql = $pdo->prepare("SELECT * FROM Caracteristica WHERE id = ?");
$sql->execute([$id]);
$linha = $sql->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>Editar Característica</title>
</head>
<body>

<div class="container mt-4">

<h1>Editar Característica</h1>

<form action="atualizar_caracteristica.php" method="POST">

    <input type="hidden" name="id" value="<?= $linha['id'] ?>">

    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control" value="<?= $linha['nome'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Descrição</label>
        <textarea name="descricao" class="form-control" rows="3"><?= $linha['descricao'] ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="caracteristicas.php" class="btn btn-secondary">Cancelar</a>

</form>

</div>

</body>
</html>
