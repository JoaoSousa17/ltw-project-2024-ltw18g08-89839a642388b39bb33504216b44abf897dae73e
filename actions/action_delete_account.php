<?php
include_once (__DIR__ . '/../database/user.php');
include_once (__DIR__ . '/../utils/session.php');

$username = getCurrentUser();

if (deleteAccount($username)) {
    session_destroy();
    session_start();
    header('Location: ../pages/mainPage.php');
} else {
    echo "Delete failed";
}