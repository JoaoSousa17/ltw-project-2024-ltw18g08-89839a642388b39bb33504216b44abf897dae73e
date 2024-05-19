<?php
include_once(__DIR__ . "/../utils/session.php"); // Include session utilities
include_once (__DIR__ . '/../database/item.php'); // Include item-related functions
include_once (__DIR__ . '/../database/user.php'); // Include transaction-related functions

// Get current user from session
$username = getCurrentUser();

// Check if user is logged in
if (!$username) {
    header('Location: ../pages/login.php');
}
$address = $_POST['address'];
$city = $_POST['city'];
$zip_code = $_POST['zip-code'];
$country = $_POST['country'];
$cart = getShopCart($username);
$total = calculateTotalPrice($cart);

$count=createTransaction($username,$address,$city,$zip_code,$country);
if($count>0) {
    header('Location: ../pages/recipe.php?id='.$count);
} else {
    echo $count;
}