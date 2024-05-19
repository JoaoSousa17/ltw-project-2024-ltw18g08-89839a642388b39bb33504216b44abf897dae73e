<?php
include_once(__DIR__ . '/../database/category.php');
include_once(__DIR__ . '/../utils/session.php');

$category_id = $_GET['id'] ?? null;

if (!$category_id) {
    echo "Category ID is missing.";
    exit;
}

if (deleteCategory($category_id)) {
    header('Location: ../pages/categories.php');
    exit;
} else {
    error_log('Failed to delete category with ID: ' . $category_id); // Adicionar log para depuração
    echo "Failed to delete category.";
}
