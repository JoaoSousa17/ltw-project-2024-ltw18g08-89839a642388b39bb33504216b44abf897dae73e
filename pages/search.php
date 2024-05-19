<?php
include_once(__DIR__ .'/../templates/tpl_basic.php');
include_once (__DIR__ . '/../templates/tpl_mainPage.php');
include_once (__DIR__ . '/../templates/tpl_item.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');
include_once(__DIR__ . '/../database/currency.php');

$username = $_SESSION['username'] ?? NULL;

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? null;
$priceRange = $_GET['price'] ?? null;

drawHeader($username);
drawSearchResults($search, $category, $priceRange);
drawFooter();
?>
