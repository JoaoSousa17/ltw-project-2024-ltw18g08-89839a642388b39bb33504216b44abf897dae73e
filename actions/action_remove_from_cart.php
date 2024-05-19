<?php
include_once(__DIR__ . "/../utils/session.php"); // Include session utilities
include_once (__DIR__ . '/../database/item.php'); // Include item-related functions
include_once (__DIR__ . '/../database/user.php'); // Include user-related functions

$username = getCurrentUser();

$item_id = $_GET['id'];

if (removeFromShopCart($username, $item_id)) {
    header('Location: ../pages/shopCart.php');

} else {
    echo "Failed to remove item from cart";
}