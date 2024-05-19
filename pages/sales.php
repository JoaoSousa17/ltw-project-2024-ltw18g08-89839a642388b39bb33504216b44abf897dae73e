<?php
include_once(__DIR__ .'/../templates/tpl_basic.php');
include_once(__DIR__ .'/../templates/tpl_sellingBoughtItems.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/user.php');
include_once(__DIR__ . '/../database/item.php');
include_once(__DIR__ . '/../database/currency.php');


// Obtém o usuário dos itens à venda a partir do parâmetro da URL
$user = urldecode($_GET['user']);
$username = $_SESSION['username'] ?? NULL;

// Log para depuração
error_log('Sales Page User: ' . $user);

drawHeader($username);
drawItemsOnSell($user);
drawFooter();
?>