<?php
include_once(__DIR__ . '/../database/category.php');
include_once(__DIR__ . '/../utils/session.php');

$name = $_POST['name'] ?? null;
$icon = $_FILES['icon'] ?? null;

if (!$name || !$icon) {
    echo "Category name or icon is missing.";
    exit;
}

// Chama a função para adicionar a categoria e fazer o upload da imagem
if (addCategoryWithIcon($name, $icon)) {
    header('Location: ../pages/categories.php');
    exit;
} else {
    echo "Failed to add category.";
}
?>

