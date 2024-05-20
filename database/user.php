<?php
include_once(__DIR__ . "/../database/connection.db.php");
include_once (__DIR__ . '/../database/db_upload.php');
include_once (__DIR__ . '/../database/item.php');

function checkPassword($username, $password): bool
{
    // Get the database connection
    $db = getDatabaseConnection();
    $hashed = hashPassword($password);
    try {
        $stmt = $db->prepare('SELECT * FROM user WHERE Username = ? AND Password = ?');
        $stmt->execute(array($username, $hashed));
        if($stmt->fetch() !== false) {
            return true;
        }
        else return false;
    } catch(PDOException $e) {
        return false;
    }
}
function hashPassword($password): string
{
    return hash('sha256', $password);
}

function createUser($email, $username, $password, $location, $address, $postal_code, $currency) {
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('INSERT INTO user (email, is_admin, username, password, location, address, postal_code, currency) VALUES (?, 1, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$email, $username, $password, $location, $address, $postal_code, $currency]);
        return true;
    } catch (PDOException $e) {
        // Adicionar mensagem de depuração para o log de erros
        error_log($e->getMessage());
        echo "Database error: " . $e->getMessage(); // Mensagem de depuração
        return false;
    }
}

function getUser($username) {
    if (!is_string($username)) {
        error_log('Error: username is not a string');
        return false;
    }

    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM user WHERE username = ?');
    $stmt->execute(array($username));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("User not found for username: " . $username);
        return false;
    }

    return $user;
}

function getUsernameById($userId) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT username FROM user WHERE user_id = ?');
    $stmt->execute(array($userId));
    return $stmt->fetchColumn();
}

function transData($data): string
{
    $year = substr($data,0,4);
    $month = substr($data,5,2);
    if($month == "01") $month = "January";
    if($month == "02") $month = "February";
    if($month == "03") $month = "March";
    if($month == "04") $month = "April";
    if($month == "05") $month = "May";
    if($month == "06") $month = "June";
    if($month == "07") $month = "July";
    if($month == "08") $month = "August";
    if($month == "09") $month = "September";
    if($month == "10") $month = "October";
    if($month == "11") $month = "November";
    if($month == "12") $month = "December";

    return $month . " of " . $year;
}

function editProfile($username, $newPassword, $location, $email, $address, $postal_code, $currency): bool
{
    $db = getDatabaseConnection();
    
    try {
        if ($newPassword == null) {
            $stmt = $db->prepare('UPDATE user SET Location = ?, Email = ?, Address = ?, Postal_Code = ?, Currency = ? WHERE Username = ?');
            $stmt->execute(array($location, $email, $address, $postal_code, $currency, $username));
        } else {
            $hashed = hashPassword($newPassword);
            $stmt = $db->prepare('UPDATE user SET Password = ?, Location = ?, Email = ?, Address = ?, Postal_Code = ?, Currency = ? WHERE Username = ?');
            $stmt->execute(array($hashed, $location, $email, $address, $postal_code, $currency, $username));
        }
        return true;
    } catch (PDOException $e) {
        return false;
    }
}


function deleteAccount($username):bool
{
    $db = getDatabaseConnection();
    try {
        deleteSetProfile($username);
        deleteAllUserItems(getUser($username)['user_id']);
        $stmt = $db->prepare('DELETE FROM user WHERE Username = ?');
        $stmt->execute(array($username));
        return true;
    } catch(PDOException $e) {
        return false;
    }
}
function isAdmin($username): bool
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT * FROM user WHERE Username = ? AND is_admin = 1');
        $stmt->execute(array($username));
        $admin = $stmt->fetch();
        return ($admin !== false);
    } catch(PDOException $e) {
        return false;
    }
}
function getMessages($username)
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT * FROM message WHERE Receiver = ?');
        $stmt->execute(array($username));
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        return false;
    }
}

/*function getShopCartDetails($username) {
    $db = getDatabaseConnection();
    $cartItems = [];

    try {
        $stmt = $db->prepare('SELECT * FROM item WHERE item_id IN (SELECT shopcart FROM user WHERE username = ?)');
        $stmt->execute(array($username));
        while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cartItems[] = $item;
        }
    } catch (PDOException $e) {
        error_log("Error fetching shopcart details for $username: " . $e->getMessage());
    }

    return $cartItems;
}*/

function deleteShopCart($username)
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('UPDATE user SET shopping_cart = ? WHERE username = ?');
        $stmt->execute(array('', $username));
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function addToShopCart($username, $item_id): bool
{
    error_log("addToShopCart called with username: $username and item_id: $item_id");

    // Ensure the inputs are valid
    if (!is_string($username) || !is_numeric($item_id)) {
        error_log('Invalid input: username should be a string and item_id should be a number.');
        return false;
    }

    $db = getDatabaseConnection();
    if (!$db) {
        error_log('Database connection failed.');
        return false;
    }

    // Verify that the user exists
    $userStmt = $db->prepare('SELECT COUNT(*) FROM user WHERE username = ?');
    $userStmt->execute([$username]);
    $userExists = $userStmt->fetchColumn() > 0;

    if (!$userExists) {
        error_log("User $username does not exist.");
        return false;
    }

    // Verify that the item exists
    $itemStmt = $db->prepare('SELECT COUNT(*) FROM item WHERE item_id = ?');
    $itemStmt->execute([$item_id]);
    $itemExists = $itemStmt->fetchColumn() > 0;

    if (!$itemExists) {
        error_log("Item $item_id does not exist.");
        return false;
    }

    try {
        // Retrieve existing shopping cart
        $existingShopCart = getShopCart($username);
        error_log("Existing shopping cart for user $username: " . implode(',', $existingShopCart));

        // If item is already in the shopping cart, no need to add again
        if (in_array($item_id, $existingShopCart)) {
            error_log("Item $item_id is already in the shopping cart for user $username.");
            return true;
        }

        // Add item to the shopping cart
        $existingShopCart[] = $item_id;
        $newShopCart = implode(',', $existingShopCart);
        error_log("New shopping cart for user $username: " . $newShopCart);

        // Update the shopping cart in the database
        $stmt = $db->prepare('UPDATE user SET shopping_cart = ? WHERE username = ?');
        $stmt->execute([$newShopCart, $username]);

        if ($stmt->rowCount() > 0) {
            error_log("Item $item_id successfully added to the shopping cart for user $username.");
            return true;
        } else {
            error_log("Failed to update the shopping cart for user $username.");
            return false;
        }
    } catch (PDOException $e) {
        error_log('PDOException: ' . $e->getMessage());
        return false;
    }
}

function getShopCart($username)
{
    $db = getDatabaseConnection();
    if (!$db) {
        error_log('Database connection failed.');
        return array();
    }

    try {
        $stmt = $db->prepare('SELECT shopping_cart FROM user WHERE username = ?');
        $stmt->execute([$username]);
        $result = $stmt->fetchColumn();
        if ($result !== false && $result !== null) {
            $items = array_filter(explode(',', $result));
            error_log("Shopping cart items for user $username: " . implode(',', $items));
            return $items;
        }
        return array(); // Return an empty array if no items found in the shopping cart
    } catch (PDOException $e) {
        error_log('Error fetching shopping cart: ' . $e->getMessage());
        return array();
    }
}

function removeFromShopCart($username, $item_id): bool
{
    $db = getDatabaseConnection();
    if (!$db) {
        error_log('Database connection failed.');
        return false;
    }

    try {
        $existingCart = getShopCart($username);
        error_log("Existing shopping cart before removal for user $username: " . implode(',', $existingCart));

        $newCart = array_filter($existingCart, function ($item) use ($item_id) {
            return $item != $item_id;
        });

        $newCartString = implode(',', $newCart);
        error_log("New shopping cart after removal for user $username: " . $newCartString);

        $stmt = $db->prepare('UPDATE user SET shopping_cart = ? WHERE username = ?');
        $stmt->execute([$newCartString, $username]);

        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        error_log('Error removing from shop cart: ' . $e->getMessage());
        return false;
    }
}

function createTransaction($buyer, $address, $city, $zip, $country) {
    error_log("createTransaction called with parameters: buyer=$buyer, address=$address, city=$city, zip=$zip, country=$country");

    if (!is_string($buyer) || !is_string($address) || !is_string($city) || !is_string($zip) || !is_string($country)) {
        error_log("Error: one or more parameters are not strings");
        return "Error creating transaction";
    }

    $db = getDatabaseConnection();
    $buyer_info = getUser($buyer);

    if (!$buyer_info) {
        error_log("Buyer not found for username: " . $buyer);
        return "Buyer not found";
    }

    $buyer_id = $buyer_info['user_id'];
    $sellers = joinSellers($buyer);
    $completeAddress = joinAddress($address, $city, $zip, $country);
    $items = getShopCart($buyer);
    $item = implode(',', $items);
    $totalPrice = calculateTotalPrice($items);

    error_log('Creating transaction for buyer_id: ' . $buyer_id); // Depuração
    error_log('Sellers: ' . $sellers); // Depuração
    error_log('Complete Address: ' . $completeAddress); // Depuração
    error_log('Items: ' . $item); // Depuração
    error_log('Total Price: ' . $totalPrice); // Depuração

    if ($sellers && $buyer_id && $items && $totalPrice && $completeAddress && $buyer) {
        try {
            $stmt = $db->prepare('INSERT INTO transactions (buyer_id, seller_id, item_id, total_price, address, name) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute(array($buyer_id, $sellers, $item, $totalPrice, $completeAddress, $buyer));
            deleteShopCart($buyer);
            $transaction_id = $db->lastInsertId('transaction_id');

            error_log('Transaction created with ID: ' . $transaction_id); // Depuração
            return $transaction_id;
        } catch (PDOException $e) {
            error_log('PDOException: ' . $e->getMessage()); // Depuração
            return "Error creating database transaction";
        }
    } else {
        error_log("Error creating transaction: Missing data or invalid values");
        error_log("sellers: $sellers, buyer_id: $buyer_id, items: " . print_r($items, true) . ", totalPrice: $totalPrice, completeAddress: $completeAddress, buyer: $buyer");
        return "Error creating transaction";
    }
}

/*function createTransaction($buyer, $address, $city, $zip, $country) {
    error_log("createTransaction called with parameters: buyer=$buyer, address=$address, city=$city, zip=$zip, country=$country");

    if (!is_string($buyer) || !is_string($address) || !is_string($city) || !is_string($zip) || !is_string($country)) {
        error_log("Error: one or more parameters are not strings");
        return "Error creating transaction";
    }

    $db = getDatabaseConnection();
    $buyer_info = getUser($buyer);

    if (!$buyer_info) {
        error_log("Buyer not found for username: " . $buyer);
        return "Buyer not found";
    }

    $buyer_id = $buyer_info['user_id'];
    $sellers = joinSellers($buyer);
    $completeAddress = joinAddress($address, $city, $zip, $country);
    $items = getShopCart($buyer);
    $item = implode(',', $items);
    $totalPrice = calculateTotalPrice($items);

    error_log('Creating transaction for buyer_id: ' . $buyer_id); // Depuração
    error_log('Sellers: ' . $sellers); // Depuração
    error_log('Complete Address: ' . $completeAddress); // Depuração
    error_log('Items: ' . $item); // Depuração
    error_log('Total Price: ' . $totalPrice); // Depuração

    if ($sellers && $buyer_id && $items && $totalPrice && $completeAddress && $buyer) {
        try {
            $stmt = $db->prepare('INSERT INTO transactions (buyer_id, seller_id, item_id, total_price, address, name) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute(array($buyer_id, $sellers, $item, $totalPrice, $completeAddress, $buyer));
            
            // Update status of each item to 'sold'
            foreach ($items as $item_id) {
                $updateStmt = $db->prepare('UPDATE item SET status = ? WHERE item_id = ?');
                $updateStmt->execute(['sold', $item_id]);
            }

            deleteShopCart($buyer);
            $transaction_id = $db->lastInsertId('transaction_id');

            error_log('Transaction created with ID: ' . $transaction_id); // Depuração
            return $transaction_id;
        } catch (PDOException $e) {
            error_log('PDOException: ' . $e->getMessage()); // Depuração
            return "Error creating database transaction";
        }
    } else {
        error_log("Error creating transaction: Missing data or invalid values");
        error_log("sellers: $sellers, buyer_id: $buyer_id, items: " . print_r($items, true) . ", totalPrice: $totalPrice, completeAddress: $completeAddress, buyer: $buyer");
        return "Error creating transaction";
    }
}*/



function joinSellers($username): string
{
    $items = getShopCart($username);
    $sellers = [];
    foreach ($items as $item) {
        if(!$item) continue;
        $seller = getItemById($item)['seller_id'];
        $sellers[] = $seller;
    }
    $sellers = array_unique($sellers);
    return implode(',', $sellers);
}

function separateSellers($sellers): array
{
    return explode(',', $sellers);
}

function separateItems($itemString)
{
    if ($itemString === null || $itemString === '') {
        return array(); // Return an empty array if the string is null or empty
    }
    return explode(',', $itemString);
}

function joinAddress($address,$city,$zip,$country): string
{
    return $address . ", " . $city . ", " . $country . ", " . $zip;
}
function getTransaction($transaction_id)
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT * FROM transactions WHERE transaction_id = ?');
        $stmt->execute(array($transaction_id));
        return $stmt->fetch();
    } catch(PDOException $e) {
        return false;
    }
}

/*function getWishlist($username)
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT wishlist FROM user WHERE username = ?');
        $stmt->execute(array($username));
        $result = $stmt->fetchColumn();
        if ($result !== false && $result !== null) {
            return explode(',', $result);
        }
        return array(); // Return an empty array if no items found in the wishlist
    } catch(PDOException $e) {
        return false;
    }
}*/

function getWishlist($username) {
    error_log("getWishlist called with username: $username");

    if (!is_string($username)) {
        error_log('Error: username is not a string');
        return array();
    }

    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT wishlist FROM user WHERE username = ?');
        $stmt->execute(array($username));
        $result = $stmt->fetchColumn();

        if ($result !== false && $result !== null && !empty($result)) {
            $wishlistItems = explode(',', $result);
            error_log("Wishlist Items for $username: " . print_r($wishlistItems, true)); // Adicionar log para depuração
            return $wishlistItems;
        }

        return array(); // Retorna um array vazio se não houver itens na lista de desejos
    } catch(PDOException $e) {
        error_log("Error fetching wishlist for $username: " . $e->getMessage()); // Adicionar log para depuração
        return array(); // Retorna um array vazio em caso de erro
    }
}


/*function addToWishList($username, $item_id): bool
{
    $db = getDatabaseConnection();
    try {
        $existingWishList = getWishList($username);
        $existingWishList[] = $item_id;
        $newWishList = implode(',', $existingWishList);
        $stmt = $db->prepare('UPDATE user SET wishlist = ? WHERE username = ?');
        $stmt->execute(array($newWishList, $username));
        return true;
    } catch(PDOException $e) {
        return false;
    }
}*/

function addToWishList($username, $item_id): bool
{
    $db = getDatabaseConnection();
    try {
        // Retrieve existing wishlist
        $existingWishList = getWishlist($username);

        // Depuração: Exibir conteúdo da wishlist existente
        var_dump($existingWishList);

        // Check if item is already in the wishlist
        if (in_array($item_id, $existingWishList)) {
            return true; // Item already in wishlist, no need to add again
        }

        // Add item to wishlist
        $existingWishList[] = $item_id;
        $newWishList = implode(',', $existingWishList);

        // Depuração: Exibir nova wishlist
        echo "New wishlist: " . $newWishList . "<br>";

        // Update wishlist in database
        $stmt = $db->prepare('UPDATE user SET wishlist = ? WHERE username = ?');
        $stmt->execute(array($newWishList, $username));

        // Depuração: Verificar resultado da atualização no banco de dados
        if ($stmt->rowCount() > 0) {
            echo "Wishlist updated successfully.<br>";
        } else {
            echo "No rows affected.<br>";
        }

        return true;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        return false;
    }
}

function removeFromWishList($username, $item_id): bool {
    error_log("removeFromWishList called with username: $username and item_id: $item_id");

    if (!is_string($username)) {
        error_log('Error: username is not a string');
        return false;
    }

    $db = getDatabaseConnection();
    try {
        $existingWishList = getWishlist($username);
        $newWishList = array();

        foreach ($existingWishList as $item) {
            if ($item != $item_id) {
                $newWishList[] = $item;
            }
        }

        $newWishList = implode(',', $newWishList);
        $stmt = $db->prepare('UPDATE user SET wishlist = ? WHERE username = ?');
        $stmt->execute(array($newWishList, $username));

        return true;
    } catch(PDOException $e) {
        error_log("Error removing item from wishlist for $username: " . $e->getMessage()); // Adicionar log para depuração
        return false;
    }
}

function getUserById($user_id)
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT * FROM user WHERE user_id = ?');
        $stmt->execute(array($user_id));
        return $stmt->fetch();
    } catch(PDOException $e) {
        return false;
    }
}

function getUserLocation($username) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT location FROM user WHERE username = ?');
    $stmt->execute(array($username));
    return $stmt->fetchColumn();
}

function registerUser($email, $username, $hashed_password, $location, $address, $postal_code, $currency): bool
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('INSERT INTO user (Email, Username, Password, Location, Address, Postal_Code, Currency) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute(array($email, $username, $hashed_password, $location, $address, $postal_code, $currency));
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function getAllUsers() {
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT * FROM user');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Error fetching users: ' . $e->getMessage());
        return [];
    }
}

function promoteUserToAdmin($user_id) {
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('UPDATE user SET is_admin = 1 WHERE user_id = ?');
        $stmt->execute(array($user_id));
        return true;
    } catch (PDOException $e) {
        error_log('Error promoting user: ' . $e->getMessage());
        return false;
    }
}

function deleteUser($user_id) {
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('DELETE FROM user WHERE user_id = ?');
        $stmt->execute(array($user_id));
        return true;
    } catch (PDOException $e) {
        error_log('Error deleting user: ' . $e->getMessage());
        return false;
    }
}
?>

