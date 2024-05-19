<?php
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_sellingBoughtItems.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/user.php');
include_once(__DIR__ . '/../database/item.php');

$current_user = getCurrentUser();
$username = $current_user ? $current_user['username'] : null;

if (!$username) {
    header('Location: ../pages/login.php');
    exit;
}

drawHeader($username);
drawBoughtItems($username);
drawFooter();
?>
