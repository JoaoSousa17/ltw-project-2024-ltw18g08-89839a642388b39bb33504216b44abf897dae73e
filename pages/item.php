<?php
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_item.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');
include_once (__DIR__ . '/../database/user.php');
include_once (__DIR__ . '/../database/currency.php');

$username = $_SESSION['username'] ?? NULL;
$item_id = $_GET['id'];

drawHeader($username);
drawItemPage($item_id);
drawFooter();
?>
