<?php
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_categories.php');
include_once(__DIR__ . '/../database/item.php');

$username = $_SESSION['username'] ?? NULL;
$category_id = $_GET['id'];
$option = 1;

drawHeader($username);
drawPageTitle($option, $category_id);
drawSpecificCategory($category_id);
drawFooter();
?>
