<?php
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');
include_once(__DIR__ . '/../database/user.php');

$current_user = getCurrentUser();
$username = $current_user ? $current_user['username'] : null;

if (!$username) {
    header('Location: ../pages/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Item ID is missing.";
    exit;
}

$item_id = $_GET['id'];

echo "Current user: " . htmlspecialchars($username) . "<br>";
echo "Item ID: " . htmlspecialchars($item_id) . "<br>";

echo "Calling removeFromWishList function...<br>";

if (removeFromWishList($username, $item_id)) {
    echo "Item successfully removed from wishlist.<br>";
    header('Location: ../pages/wishlist.php');
    exit;
} else {
    echo "Failed to remove item from wishlist.<br>";
}