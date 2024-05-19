<?php
include_once(__DIR__ . '/../utils/session.php'); // Include session utilities
include_once(__DIR__ . '/../database/item.php'); // Include item-related functions
include_once(__DIR__ . '/../database/user.php'); // Include transaction-related functions

// Get current user from session
$current_user = getCurrentUser();
$username = $current_user ? $current_user['username'] : null;

if (!$username) {
    header('Location: ../pages/login.php');
    exit;
}

$address = isset($_POST['address']) ? $_POST['address'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$zip = isset($_POST['zip-code']) ? $_POST['zip-code'] : '';
$country = isset($_POST['country']) ? $_POST['country'] : '';

$transaction_id = createTransaction($username, $address, $city, $zip, $country);

if (is_numeric($transaction_id)) {
    header('Location: ../pages/recipe.php?id=' . $transaction_id);
    exit;
} else {
    echo $transaction_id; // Exibir mensagem de erro
}
