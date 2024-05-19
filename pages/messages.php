<?php
include_once(__DIR__ .'/../templates/tpl_basic.php');
include_once (__DIR__ . '/../templates/tpl_mainPage.php');
require_once(__DIR__ . '/../templates/tpl_messages.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');

$username = $_SESSION['username'] ?? NULL;

drawHeader($username);
drawMessages($username);
drawFooter();
?>