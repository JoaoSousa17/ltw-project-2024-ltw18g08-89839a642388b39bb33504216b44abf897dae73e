<?php
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_categories.php');
include_once(__DIR__ . '/../database/user.php');

$username = $_SESSION['username'] ?? NULL;
drawHeader($username);
drawCategoriesAddForm();
drawFooter();
?>
