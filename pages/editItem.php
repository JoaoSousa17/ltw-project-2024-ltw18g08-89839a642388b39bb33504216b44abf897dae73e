<?php
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_item.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');
include_once (__DIR__ . '/../database/user.php');

$username = $_SESSION['username'] ?? NULL;
$item_id = $_GET['id'];

drawHeader($username);
drawEditItemPage($item_id, $username);
drawFooter();
?>
