<?php
require_once '../config/conexao.php';
require_once '../config/urls.php';
session_start();
session_destroy();
header("Location: " . AUTH_URL . "login.php");
exit;
?>
