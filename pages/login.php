<?php
include_once(__DIR__ .'/../templates/tpl_basic.php');
include_once (__DIR__ . '/../templates/tpl_auth.php');
include_once(__DIR__ . '/../utils/session.php');

$username = NULL;

drawHeader($username);
drawLogin();
drawFooter();
?>
