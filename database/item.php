<?php
include_once(__DIR__ . "/../database/connection.db.php");
include_once (__DIR__ . '/../database/db_upload.php');
include_once (__DIR__ . '/../database/user.php');

function getItems($count) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM item WHERE status != ? ORDER BY item_id DESC LIMIT ?');
    $stmt->execute(['sold', $count]);
    return $stmt->fetchAll();
}


function getCategories() {
    $db = getDatabaseConnection();

    try {
        $stmt = $db->prepare('SELECT * FROM category');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao buscar categorias: ' . $e->getMessage());
        return [];
    }
}

function createNewItem($title, $description, $price, $category_id, $seller_id){
    $db = getDatabaseConnection();

    // Prepare the SQL statement
    $stmt = $db->prepare('INSERT INTO item (title, description, price, category_id, seller_id) VALUES (?, ?, ?, ?, ?)');

    // Execute the statement with an array of values
    $stmt->execute([$title, $description, $price, $category_id, $seller_id]);

    // Return the ID of the inserted item
    return $db->lastInsertId();
}

function deleteAllUserItems($user_id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT item_pictures FROM item WHERE seller_id = ?');
    $stmt->execute(array($user_id));
    $items = $stmt->fetchAll();
    foreach ($items as $item){
        deleteSetItem($item['item_pictures']);
    }
    $stmt = $db->prepare('DELETE FROM item WHERE seller_id = ?');
    $stmt->execute(array($user_id));
}

function deleteItem($item_id): bool{
    try{
        $db = getDatabaseConnection();

        $item=getItemById($item_id);
        deleteSetItem($item['title']);

        $stmt = $db->prepare('DELETE FROM item WHERE item_id = ?');
        $stmt->execute(array($item_id));
        return true;
    }
    catch (PDOException $e){
        return false;
    }

}

function updateItem($item_id, $title, $description, $price, $category) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('UPDATE item SET title = ?, description = ?, price = ?, category_id = ? WHERE item_id = ?');
    $stmt->execute(array($title, $description, $price, $category, $item_id));
}

function getItemById($item_id) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM item WHERE item_id = ?');
    $stmt->execute(array($item_id));
    return $stmt->fetch(PDO::FETCH_ASSOC); // Certifique-se de que retorna um array associativo
}

function getItemByTitle($search = null, $category = null, $priceRange = null) {
    $db = getDatabaseConnection();

    $query = 'SELECT * FROM item WHERE 1=1';
    $params = [];

    if ($search) {
        $query .= ' AND title LIKE ?';
        $params[] = '%' . $search . '%';
    }

    if ($category) {
        $query .= ' AND category_id = ?';
        $params[] = $category;
    }

    if ($priceRange) {
        switch ($priceRange) {
            case '0-50':
                $query .= ' AND price BETWEEN 0 AND 50';
                break;
            case '50-100':
                $query .= ' AND price BETWEEN 50 AND 100';
                break;
            case '100-200':
                $query .= ' AND price BETWEEN 100 AND 200';
                break;
            case '200+':
                $query .= ' AND price > 200';
                break;
        }
    }

    error_log('Query: ' . $query);
    error_log('Params: ' . print_r($params, true));

    try {
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao buscar itens: ' . $e->getMessage());
        return [];
    }
}

function getItemsByCategory($category_id) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM item WHERE category_id = ? AND status != ?');
    $stmt->execute(array($category_id, 'sold'));
    return $stmt->fetchAll();
}


function getItemsByUserId($user_id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM item WHERE seller_id = ?');
    $stmt->execute(array($user_id));
    return $stmt->fetchAll();
}

function getCategoryById($category_id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM category WHERE category_id = ?');
    $stmt->execute(array($category_id));
    return $stmt->fetch();
}

function calculateTotalPrice($items){
    $total = 0;
    foreach ($items as $item) {
        $price = getItemPriceById($item);
        if ($price !== false) {
            $total += $price;
        } else {
            return false;
        }
    }
    return $total;
}

function calculateShippingCost($sellerLocation, $buyerLocation) {
    // Definindo as regras de custo de entrega
    $costs = [
        'Portugal-Espanha' => 4,
        'Açores-Madeira' => 1,
        'Portugal-Madeira' => 3,
        'Portugal-Açores' => 4,
        'Espanha-Açores' => 5,
        'Espanha-Madeira' => 4,
        'Portugal-Portugal' => 1,
        'Espanha-Espanha' => 1,
        'Madeira-Madeira' => 0,
        'Açores-Açores' => 0
    ];

    // Determinar regiões
    $regions = [
        'Portugal' => ['Aveiro', 'Beja', 'Braga', 'Bragança', 'Castelo Branco', 'Coimbra', 'Évora', 'Faro', 'Guarda', 'Leiria', 'Lisboa', 'Portalegre', 'Porto', 'Santarém', 'Setúbal', 'Viana do Castelo', 'Vila Real', 'Viseu'],
        'Açores' => ['Corvo', 'Faial', 'Flores', 'Graciosa', 'Pico', 'Santa Maria', 'São Jorge', 'São Miguel', 'Terceira'],
        'Madeira' => ['Calheta', 'Câmara de Lobos', 'Funchal', 'Machico', 'Ponta do Sol', 'Porto Moniz', 'Porto Santo', 'Ribeira Brava', 'Santa Cruz', 'Santana', 'São Vicente'],
        'Espanha' => ['A Coruña', 'Álava', 'Albacete', 'Alicante', 'Almería', 'Asturias', 'Ávila', 'Badajoz', 'Barcelona', 'Burgos', 'Cáceres', 'Cádiz', 'Cantabria', 'Castellón', 'Ciudad Real', 'Córdoba', 'Cuenca', 'Girona', 'Granada', 'Guadalajara', 'Guipúzcoa', 'Huelva', 'Huesca', 'Illes Balears', 'Jaén', 'La Rioja', 'Las Palmas', 'León', 'Lleida', 'Lugo', 'Madrid', 'Málaga', 'Murcia', 'Navarra', 'Ourense', 'Palencia', 'Pontevedra', 'Salamanca', 'Santa Cruz de Tenerife', 'Segovia', 'Sevilla', 'Soria', 'Tarragona', 'Teruel', 'Toledo', 'Valencia', 'Valladolid', 'Vizcaya', 'Zamora', 'Zaragoza']
    ];

    // Determinar regiões do vendedor e do comprador
    $sellerRegion = null;
    $buyerRegion = null;

    foreach ($regions as $region => $locations) {
        if (in_array($sellerLocation, $locations)) {
            $sellerRegion = $region;
        }
        if (in_array($buyerLocation, $locations)) {
            $buyerRegion = $region;
        }
    }

    if ($sellerRegion && $buyerRegion) {
        $key = $sellerRegion . '-' . $buyerRegion;
        if (isset($costs[$key])) {
            return $costs[$key];
        } else {
            return $costs[$buyerRegion . '-' . $sellerRegion] ?? 0;
        }
    }

    return 0;
}

function getItemPriceById($item_id){
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT price FROM item WHERE item_id = ?');
        $stmt->execute(array($item_id));
        $price = $stmt->fetchColumn();
        return $price !== false ? $price : 0; // Return 0 if item not found
    } catch(PDOException $e) {
        // Handle database error
        return false;
    }
}

function getSellerByItemId($item_id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT seller_id FROM item WHERE item_id = ?');
    $stmt->execute(array($item_id));
    return getUserById($stmt->fetchColumn());
}

function getUserIdByUsername($username) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT user_id FROM user WHERE username = ?');
    $stmt->execute(array($username));
    return $stmt->fetchColumn();
}

function getBoughtItems($user_id) {
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT * FROM transactions WHERE buyer_id = ?');
        $stmt->execute(array($user_id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log('Erro ao buscar transações: ' . $e->getMessage());
        return [];
    }
}



?>