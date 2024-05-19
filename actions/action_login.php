<?php
include_once('../database/user.php');
include_once('../utils/session.php');

$username = $_POST['username'];
$password = $_POST['password'];

$user = getUser($username);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $username;
    header('Location: ../pages/mainPage.php');
} else {
    header('Location: ../pages/login.php?error=invalid_credentials');
}
?>