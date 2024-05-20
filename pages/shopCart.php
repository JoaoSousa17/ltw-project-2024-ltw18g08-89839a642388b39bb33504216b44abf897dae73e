<?php
include_once(__DIR__ .'/../templates/tpl_basic.php');
include_once (__DIR__ . '/../templates/tpl_item.php');
include_once (__DIR__ . '/../templates/tpl_purchases.php');
include_once(__DIR__ . '/../utils/session.php');
include_once (__DIR__ . '/../database/item.php');
include_once (__DIR__ . '/../database/user.php');
include_once (__DIR__ . '/../database/currency.php');

$username = $_SESSION['username'] ?? NULL;

if (!$username) {
    header('Location: ../pages/login.php');
}

drawHeader($username);
drawShopCart($username);
drawFooter();
?>


