<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . '/../utils/session.php'); // Incluir utilitários de sessão
include_once(__DIR__ . '/../database/item.php'); // Incluir funções relacionadas a itens
include_once(__DIR__ . '/../database/user.php'); // Incluir funções relacionadas a usuários

// Obter o usuário atual da sessão
$username = getCurrentUser();

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

// Depuração: Verificar chamada da função addToShopCart
echo "Calling addToShopCart function...<br>";

if (addToShopCart($username, $item_id)) {
    echo "Item successfully added to shopcart.<br>";
    header('Location: ../pages/shopCart.php'); // Comente esta linha temporariamente
    // exit; // Comente esta linha temporariamente
} else {
    echo "Failed to add item to shopcart<br>";
}
?>