<?php
include_once(__DIR__ . "/../utils/session.php"); // Include session utilities
include_once(__DIR__ . "/../database/item.php"); // Include item-related functions
include_once(__DIR__ . '/../database/user.php'); // Include user-related functions

$currentUser = getCurrentUser();
$username = $currentUser['username']; // Obter o username diretamente

if (!$username) {
    header('Location: ../pages/login.php');
    exit;
}

$item_id = $_GET['id'];

if (addToShopCart($username, $item_id)) {
    header('Location: ../pages/shopCart.php');
} else {
    echo "Failed to add item to cart";
}
?>
