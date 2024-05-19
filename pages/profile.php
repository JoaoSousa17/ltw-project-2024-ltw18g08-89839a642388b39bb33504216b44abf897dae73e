<?php
include_once(__DIR__ .'/../templates/tpl_basic.php');
include_once(__DIR__ .'/../templates/tpl_profile.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/user.php');
include_once (__DIR__ . '/../database/item.php');

$profileUser = isset($_GET['id']) ? urldecode($_GET['id']) : null;
$current_user = getCurrentUser();
$loggedUser = $current_user ? $current_user['username'] : null;

error_log('Profile User: ' . $profileUser);
error_log('Logged User: ' . $loggedUser);

if (!$profileUser) {
    echo 'Erro: Usuário de perfil não especificado.';
    exit;
}

drawHeader($loggedUser);
drawProfilePage($profileUser, $loggedUser);
drawFooter();
?>
