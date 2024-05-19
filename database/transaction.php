<?php
function getSoldItems($user_id) {
    $db = getDatabaseConnection();
    try {
        // Usar LIKE para procurar o user_id dentro da string seller_id
        $stmt = $db->prepare('SELECT * FROM transactions WHERE seller_id LIKE ?');
        $stmt->execute(array('%'.$user_id.'%'));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log('Error fetching sold items: ' . $e->getMessage());
        return [];
    }
}