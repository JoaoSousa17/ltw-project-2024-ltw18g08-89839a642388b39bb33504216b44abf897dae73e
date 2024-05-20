<?php
include_once(__DIR__ . "/../database/connection.db.php");

function addCategory($name, $icon) {
    $db = getDatabaseConnection();
    
    try {
        // Insere a nova categoria no banco de dados
        $stmt = $db->prepare('INSERT INTO category (name, icon_path) VALUES (?, ?)');
        $stmt->execute(array($name, 'images/categories_icon/default')); // Placeholder icon path
        $categoryId = $db->lastInsertId();

        // Chama a função para fazer o upload da imagem
        if ($icon) {
            if (uploadCategoryImage($categoryId, $icon)) {
                return true;
            } else {
                error_log('Failed to upload category image.');
                return false;
            }
        } else {
            return true;
        }
    } catch(PDOException $e) {
        error_log('Error adding category: ' . $e->getMessage());
        return false;
    }
}


function deleteCategory($category_id) {
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('DELETE FROM category WHERE category_id = ?');
        $stmt->execute(array($category_id));
        return true;
    } catch(PDOException $e) {
        error_log('Error deleting category: ' . $e->getMessage());
        return false;
    }
}

function getCategories() {
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT * FROM category');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Error fetching categories: ' . $e->getMessage());
        return [];
    }
}

function getCategoryById($categoryId) {
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT * FROM category WHERE category_id = ?');
        $stmt->execute(array($categoryId));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Error fetching category: ' . $e->getMessage());
        return false;
    }
}
?>
