<?php
/**
 * Arquivo para verificar autenticação
 * Inclua este arquivo no início de páginas que precisam de autenticação
 */

require_once '../config/conexao.php';
require_once '../config/urls.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . AUTH_URL . "login.php");
    exit;
}

// Função para verificar se é administrador
function verificar_admin() {
    if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] != 'administrador') {
        // Redireciona para uma página de acesso negado
        header("Location: " . AUTH_URL . "acesso_negado.php");
        exit;
    }
}

// Função para verificar se é cliente
function verificar_cliente() {
    if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] != 'cliente') {
        // Redireciona para uma página de acesso negado
        header("Location: " . AUTH_URL . "acesso_negado.php");
        exit;
    }
}

// Função para obter informações do usuário
function obter_usuario() {
    return [
        'id' => $_SESSION['usuario_id'],
        'nome' => $_SESSION['usuario_nome'],
        'tipo' => $_SESSION['usuario_tipo']
    ];
}

// Função para fazer logout
function fazer_logout() {
    session_destroy();
    header("Location: " . AUTH_URL . "login.php");
    exit;
}

?>
