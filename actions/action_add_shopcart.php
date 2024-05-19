<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/connection.db.php');
include_once(__DIR__ . '/../database/user.php');
include_once(__DIR__ . '/../database/item.php');

// Obter o usuário atual da sessão
$current_user = getCurrentUser();
$username = $current_user ? $current_user['username'] : null;

// Verificar se o usuário está logado
if (!$username || !is_string($username)) {
    echo "User is not logged in or invalid username.<br>";
    exit;
}

// Verificar se o ID do item está definido na URL
if (!isset($_GET['id'])) {
    echo "Item ID is missing.<br>";
    exit;
}

$item_id = $_GET['id'];

// Depuração: Exibir usuário atual e ID do item
echo "Current user: " . htmlspecialchars($username) . "<br>";
echo "Item ID: " . htmlspecialchars($item_id) . "<br>";

// Adicionar item ao carrinho de compras
if (addToShopCart($username, $item_id)) {
    echo "Item successfully added to shopcart.<br>";
    header('Location: ../pages/shopCart.php');
    exit;
} else {
    echo "Failed to add item to shopcart<br>";
}
?>