<?php/*
include_once(__DIR__ . "/../utils/session.php"); // Include session utilities
include_once (__DIR__ . '/../database/item.php'); // Include item-related functions

// Get current user from session
$username = getCurrentUser();

// Check if user is logged in
if (!$username) {
    header('Location: ../pages/login.php');
}

// Check if item ID is set in the URL
if (!isset($_GET['id'])) {
    echo "Item ID is missing.";
}

$item_id = $_GET['id'];

// Attempt to add item to shopcart
if (addToWishList($username, $item_id)) {
    header('Location: ../pages/wishlist.php');
} else {
    echo "Failed to add item to shopcart";
}*/?>
<?php
include_once(__DIR__ . '/../utils/session.php'); // Include session utilities
include_once(__DIR__ . '/../database/item.php'); // Include item-related functions
include_once(__DIR__ . '/../database/user.php'); // Include user-related functions

// Get current user from session
$username = getCurrentUser();

// Check if user is logged in
if (!$username) {
    header('Location: ../pages/login.php');
    exit;
}

// Check if item ID is set in the URL
if (!isset($_GET['id'])) {
    echo "Item ID is missing.";
    exit;
}

$item_id = $_GET['id'];

// Depuração: Exibir usuário atual e ID do item
echo "Current user: " . $username . "<br>";
echo "Item ID: " . $item_id . "<br>";

// Depuração: Verificar chamada da função addToWishList
echo "Calling addToWishList function...<br>";

if (addToWishList($username, $item_id)) {
    echo "Item successfully added to wishlist.<br>";
    header('Location: ../pages/wishlist.php');
    exit;
} else {
    echo "Failed to add item to wishlist.<br>";
}
?>