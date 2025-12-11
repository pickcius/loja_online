

<?php
/**
 * Configuração centralizada de URLs do projeto
 * Use essas constantes em vez de caminhos relativos hardcoded
 */

// Detectar a raiz do projeto 
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$root = str_replace('/sistema', '', dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))));
define('BASE_URL', $protocol . '://' . $host . $root . '/');
define('SISTEMA_URL', BASE_URL . 'sistema/');

// URLs dos módulos
define('AUTH_URL', SISTEMA_URL . 'auth/');
define('ADMIN_URL', SISTEMA_URL . 'admin/');
define('HUB_URL', SISTEMA_URL . 'hub/');
define('CARRINHO_URL', SISTEMA_URL . 'carrinho/');
define('PRODUTOS_URL', SISTEMA_URL . 'produtos/');
define('LOJAS_URL', SISTEMA_URL . 'lojas/');
define('CARACTERISTICAS_URL', SISTEMA_URL . 'caracteristicas/');
define('RELATORIOS_URL', SISTEMA_URL . 'relatorios/');
define('CONFIG_URL', SISTEMA_URL . 'config/');

// URLs dos assets
define('CSS_URL', BASE_URL . 'css/');
define('JS_URL', BASE_URL . 'js/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Função auxiliar para obter caminho relativo correto
function getRelativePath($from, $to) {
    $from = explode('/', str_replace('\\', '/', dirname($from)));
    $to = explode('/', str_replace('\\', '/', $to));
    
    $relPath = array_values(array_diff_assoc($to, $from));
    $relPath = array_merge(array_fill(0, count(array_diff_assoc($from, $to)), '..'), $relPath);
    
    return implode('/', $relPath);
} 
?>
