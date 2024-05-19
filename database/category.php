<<?php
include_once(__DIR__ . "/../database/connection.db.php");

function addCategoryWithIcon($name, $icon) {
    $db = getDatabaseConnection();
    
    // Verifica se um arquivo foi enviado
    if ($icon) {
        // Cria o diretório se não existir
        $targetDir = __DIR__ . '/../images/categories_icon/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Define o caminho do arquivo
        $targetFile = $targetDir . basename($icon['name']);
        $iconPath = '/database/images/categories_icon/' . basename($icon['name']);
        
        // Move o arquivo para o diretório correto
        if (move_uploaded_file($icon['tmp_name'], $targetFile)) {
            try {
                // Insere a nova categoria no banco de dados
                $stmt = $db->prepare('INSERT INTO category (name, icon_path) VALUES (?, ?)');
                $stmt->execute(array($name, $iconPath));
                return true;
            } catch(PDOException $e) {
                error_log('Error adding category: ' . $e->getMessage());
                return false;
            }
        } else {
            error_log('Failed to move file to: ' . $targetFile);
            return false;
        }
    } else {
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
