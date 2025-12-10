<?php
/**
 * Teste de Conexão com Banco de Dados
 * Acesse este arquivo para verificar se o banco está conectado corretamente
 */

echo "<!DOCTYPE html>";
echo "<html lang='pt-BR'>";
echo "<head>";
echo "    <meta charset='UTF-8'>";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "    <title>Teste de Conexão</title>";
echo "    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "    <style>";
echo "        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }";
echo "        .container { max-width: 600px; }";
echo "    </style>";
echo "</head>";
echo "<body>";
echo "    <div class='container'>";

try {
    require_once 'sistema/conexao.php';
    
    echo "        <div class='alert alert-success' role='alert'>";
    echo "            <h4 class='alert-heading'>✅ Conexão com Banco de Dados OK</h4>";
    echo "            <hr>";
    echo "            <p>O banco de dados foi conectado com sucesso!</p>";
    
    // Testa se a tabela Administrador existe
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM Administrador");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $admin_count = $result['count'];
    
    echo "            <p><strong>Tabela Administrador:</strong> ✓ OK (" . $admin_count . " registros)</p>";
    
    // Testa se a tabela Cliente existe
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM Cliente");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $cliente_count = $result['count'];
    
    echo "            <p><strong>Tabela Cliente:</strong> ✓ OK (" . $cliente_count . " registros)</p>";
    
    echo "            <hr>";
    echo "            <p style='margin-bottom: 0;'>";
    echo "                <a href='sistema/login.php' class='btn btn-primary'>Ir para Login</a>";
    echo "            </p>";
    echo "        </div>";
    
} catch (PDOException $e) {
    echo "        <div class='alert alert-danger' role='alert'>";
    echo "            <h4 class='alert-heading'>❌ Erro na Conexão</h4>";
    echo "            <hr>";
    echo "            <p><strong>Erro:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "            <hr>";
    echo "            <h5>Dicas para resolver:</h5>";
    echo "            <ul>";
    echo "                <li>Verifique se o MySQL está rodando (XAMPP)</li>";
    echo "                <li>Verifique as credenciais em <code>sistema/conexao.php</code></li>";
    echo "                <li>Confirme se o banco de dados <code>loja_online</code> foi criado</li>";
    echo "                <li>Execute o arquivo SQL fornecido no início do projeto</li>";
    echo "            </ul>";
    echo "        </div>";
}

echo "    </div>";
echo "</body>";
echo "</html>";
?>
