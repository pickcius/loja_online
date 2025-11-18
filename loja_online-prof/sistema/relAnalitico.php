<?php 
    include 'conexao.php';

    // 1. Média dos precos de todos os produtos
    $sql = $pdo->query("SELECT TRUNCATE(AVG(preco), 2) as media FROM Produto");
    $mediaPrecoProdutos = $sql->fetch(PDO::FETCH_ASSOC)['media'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Produtos</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .icon-placeholder {
            font-size: 2.5rem;
            color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 class="text-center mb-5">Relatório Analítico</h1>

    <div class="row g-4 mb-5">

        <div class="card">
            <div class="card-header">
                Média dos preços de todos os produtos
            </div>
            <div class="card-body">
                <h5 class="card-title">R$ <?= $mediaPrecoProdutos ?></h5>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Média dos preços de todos os produtos
            </div>
            <div class="card-body">
                <h5 class="card-title">R$ <?= $mediaPrecoProdutos ?></h5>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Média dos preços de todos os produtos
            </div>
            <div class="card-body">
                <h5 class="card-title">R$ <?= $mediaPrecoProdutos ?></h5>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>