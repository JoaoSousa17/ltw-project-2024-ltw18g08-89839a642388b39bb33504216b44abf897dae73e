<?php
// pages/categories.php

include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_categories.php');
include_once(__DIR__ . '/../database/item.php');
include_once(__DIR__ . '/../database/category.php');
include_once(__DIR__ . '/../database/currency.php');

$username = $_SESSION['username'] ?? NULL;
$option = 0;

drawHeader($username);
drawPageTitle($option);
drawCategories();
drawFooter();