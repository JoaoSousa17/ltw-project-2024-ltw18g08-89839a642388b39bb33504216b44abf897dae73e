<?php
include_once(__DIR__ . "/../database/user.php");
include_once(__DIR__ . "/../utils/session.php");

$current_search = trim($_GET['search']) ?? '';
$category = $_GET['category'] ?? '';
$priceRange = $_GET['price'] ?? '';

$url = '../pages/search.php?';
if ($current_search !== '') {
    $url .= 'search=' . urlencode($current_search) . '&';
}
if ($category !== '') {
    $url .= 'category=' . urlencode($category) . '&';
}
if ($priceRange !== '') {
    $url .= 'price=' . urlencode($priceRange) . '&';
}
header('Location: ' . rtrim($url, '&'));
?>