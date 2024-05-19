<?php
include_once(__DIR__ .'/../templates/tpl_basic.php');
include_once (__DIR__ . '/../templates/tpl_mainPage.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');
include_once(__DIR__ . '/../database/currency.php');

$loggedUser = $_SESSION['username'] ?? NULL;

drawHeader($loggedUser);
drawMainPageBanner();
drawMainPageArticlesSection();
drawFooter();
?>
