<?php
require 'conexao.php';

$lojaId = isset($_GET['loja']) ? intval($_GET['loja']) : 0;

$sql = "
    SELECT 
        p.idProdutos, p.Nome AS nome_produto, p.Preco,
        c.Nome AS nome_caracteristica,
        c.Descricao AS valor_caracteristica
    FROM Estoque e
    JOIN Produtos p ON e.Produtos = p.idProdutos
    LEFT JOIN Produtos_Caracteristica pc ON p.idProdutos = pc.Produtos
    LEFT JOIN Caracteristica c ON pc.Caracteristica = c.idCaracteristica
    WHERE e.Loja = :lojaId
    ORDER BY p.idProdutos
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['lojaId' => $lojaId]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar características por produto
$produtos = [];
foreach ($result as $linha) {
    $id = $linha['idProdutos'];
    if (!isset($produtos[$id])) {
        $produtos[$id] = [
            'nome' => $linha['nome_produto'],
            'preco' => $linha['Preco'],
            'caracteristicas' => [],
        ];
    }
    if ($linha['nome_caracteristica'] && $linha['valor_caracteristica']) {
        $produtos[$id]['caracteristicas'][] = [
            'nome' => $linha['nome_caracteristica'],
            'valor' => $linha['valor_caracteristica']
        ];
    }
}

echo json_encode(array_values($produtos));
?>
