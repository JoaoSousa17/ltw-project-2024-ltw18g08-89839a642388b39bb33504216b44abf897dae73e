<?php
include_once (__DIR__ . '/../database/item.php');
include_once (__DIR__ . '/../database/user.php');
include_once (__DIR__ . '/../utils/session.php');
include_once (__DIR__ . '/../templates/tpl_basic.php');
include_once (__DIR__ . '/../templates/tpl_transaction.php');

$username = $_SESSION['username'] ?? NULL;
$transaction_id = $_GET['id'];

drawHeader($username);
drawRecipe($transaction_id);
drawFooter();
?>
