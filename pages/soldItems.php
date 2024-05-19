<?php
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_sellingBoughtItems.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/user.php');
include_once(__DIR__ . '/../database/transaction.php');
include_once(__DIR__ . '/../database/item.php');

$username = getCurrentUser()['username'] ?? null;

if (!$username) {
    header('Location: login.php');
    exit;
}

drawHeader($username);
drawSoldItems($username);
drawFooter();
?>
