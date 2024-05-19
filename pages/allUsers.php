<?php
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_users.php');
include_once(__DIR__ . '/../database/user.php');

$currentUser = getCurrentUser();
$username = $_SESSION['username'] ?? NULL;

// Verifica se o usuário logado é um administrador
if (!$currentUser || !$currentUser['is_admin']) {
    header('Location: ../pages/mainPage.php');
    exit;
}

drawHeader($username);
drawAllUsers();
drawFooter();
?>
