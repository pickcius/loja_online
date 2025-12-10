<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container-acesso {
            text-align: center;
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .icon-acesso {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 15px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .btn-voltar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 30px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-voltar:hover {
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container-acesso">
        <div class="icon-acesso">🔒</div>
        <h1>Acesso Negado</h1>
        <p>Você não tem permissão para acessar esta página.</p>
        <p>Apenas administradores podem acessar esta área.</p>
        
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="logout.php" class="btn btn-voltar">Fazer Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-voltar">Voltar ao Login</a>
        <?php endif; ?>
    </div>
</body>

</html>
