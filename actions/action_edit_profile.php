<?php
include_once(__DIR__ . "/../database/user.php");
include_once(__DIR__ . "/../utils/session.php");

$current_user = getCurrentUser();
$username = $_SESSION['username'];
$location = $_POST['location'] ?? $current_user['location'];
$email = $_POST['email'] ?? $current_user['email'];
$address = $_POST['address'] ?? $current_user['address'];
$postal_code = $_POST['postal_code'] ?? $current_user['postal_code'];
$currency = $_POST['currency'] ?? $current_user['currency'];
$password = !empty($_POST['password']) ? $_POST['password'] : null;

$count = 0;
if (isset($_FILES['profile-pic'])) {
    $count = 1;
}

if (editProfile($username, $password, $location, $email, $address, $postal_code, $currency)) {
    if ($count > 0) {
        uploadProfileImage($username, $_FILES['profile-pic']);
    }
    setCurrentUser($username);
    header('Location: ../pages/mainPage.php');
} else {
    echo "Edit failed";
}
?>