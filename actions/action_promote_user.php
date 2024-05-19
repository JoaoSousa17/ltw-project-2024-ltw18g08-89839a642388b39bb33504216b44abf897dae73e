<?php
include_once(__DIR__ . '/../database/user.php');
include_once(__DIR__ . '/../utils/session.php');

$currentUser = getCurrentUser();

if (!$currentUser || !$currentUser['is_admin']) {
    header('Location: ../pages/mainPage.php');
    exit;
}

$user_id = $_GET['id'] ?? null;

if ($user_id && promoteUserToAdmin($user_id)) {
    header('Location: ../pages/allUsers.php');
    exit;
} else {
    echo "Failed to promote user.";
}
?>
