<?php
include_once(__DIR__ . '/../database/category.php');
include_once(__DIR__ . '/../database/db_upload.php');
include_once(__DIR__ . '/../utils/session.php');

$current_user = getCurrentUser();
$username = $_SESSION['username'];
$name = $_POST['name'] ?? null;
$icon = $_FILES['icon'] ?? null;

if (!$name || !$icon) {
    echo "Category name or icon is missing.";
    exit;
}

$count = 0;
if (isset($_FILES['icon'])) {
    $count = 1;
}

// Chama a função para adicionar a categoria e fazer o upload da imagem
if (addCategory($name, $_FILES['icon'])) {
    setCurrentUser($username);
    header('Location: ../pages/categories.php');
    exit;
} else {
    echo "Failed to add category.";
}
?>

