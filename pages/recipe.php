<?php
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');
include_once(__DIR__ . '/../database/user.php');
include_once(__DIR__ . '/../database/currency.php');
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_profile.php');
include_once(__DIR__ . '/../templates/tpl_transaction.php');

$transaction_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$transaction_id) {
    echo 'Transaction ID not specified.';
    exit;
}

$transaction = getTransaction($transaction_id);

if (!$transaction) {
    echo 'Transaction not found.';
    exit;
}

// Supondo que as funções separateSellers e separateItems já estão definidas
$sellers = separateSellers($transaction['seller_id']);
$items = separateItems($transaction['item_id']);

// Debug: Exibir dados da transação
error_log('Transaction Data: ' . print_r($transaction, true));
error_log('Sellers: ' . print_r($sellers, true));
error_log('Items: ' . print_r($items, true));

drawHeader(getCurrentUser()['username']);
drawRecipe($transaction_id);
drawFooter();
?>
