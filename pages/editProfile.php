<?php
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/user.php');
include_once (__DIR__ . '/../templates/tpl_auth.php');
include_once(__DIR__ . '/../templates/tpl_profile.php');

$username = $_SESSION['username'] ?? NULL;

drawHeader($username);
drawEditProfilePage($username);
drawFooter();
?>
