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
        $stmt = $db->prepare('INSERT INTO user (email, username, password, location, address, postal_code, currency) VALUES (?, ?, ?, ?, ?, ?, ?)');
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
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM user WHERE username = ?');
    $stmt->execute(array($username));
    return $stmt->fetch(PDO::FETCH_ASSOC);
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

function getShopCart($username)
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT shopcart FROM user WHERE username = ?');
        $stmt->execute(array($username));
        $result = $stmt->fetchColumn();
        if ($result === false || $result === null) {
            return array(); // Retorna um array vazio se não houver itens no carrinho
        }
        return explode(',', $result);
    } catch(PDOException $e) {
        return array(); // Retorna um array vazio em caso de erro
    }
}


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
    $db = getDatabaseConnection();
    try {
        $existingShopCart = getShopCart($username);
        
        if (!is_array($existingShopCart)) {
            $existingShopCart = array();
        }

        if (in_array($item_id, $existingShopCart)) {
            return true; // Item já está no carrinho, não precisa adicionar novamente
        }

        $existingShopCart[] = $item_id;
        $newShopCart = implode(',', $existingShopCart);

        // Depuração: Exibir nova lista de itens no carrinho
        echo "New shopcart: " . htmlspecialchars($newShopCart) . "<br>";

        // Atualizar o carrinho no banco de dados
        $stmt = $db->prepare('UPDATE user SET shopcart = ? WHERE username = ?');
        $stmt->execute(array($newShopCart, $username));

        // Depuração: Verificar resultado da atualização no banco de dados
        if ($stmt->rowCount() > 0) {
            echo "Shopcart updated successfully.<br>";
        } else {
            echo "No rows affected.<br>";
        }

        return true;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        return false;
    }
}

function createTransaction($buyer, $address, $city, $zip, $country)
{
    $db = getDatabaseConnection();
    $buyer_id = getUser($buyer)['user_id'];
    $sellers = joinSellers($buyer);
    $completeAddress=joinAddress($address,$city,$zip,$country);


    $items = getShopCart($buyer);
    $item = implode(',', $items);


    $totalPrice=calculateTotalPrice($items);

       if($sellers&&$buyer_id&&$items&&$totalPrice&&$completeAddress&&$buyer) {
           try {
               $stmt = $db->prepare('INSERT INTO transactions(buyer_id, seller_id, item_id, total_price,address,name) VALUES(?, ?, ?, ?,?,?)');
               $stmt->execute(array($buyer_id, $sellers, $item, $totalPrice, $completeAddress, $buyer));
               deleteShopCart($buyer);
               return $db->lastInsertId('transaction_id');

           } catch (PDOException $e) {
               return "Error creating database transaction";
           }

       }
         else return "Error creating transaction";
}

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
function separateItems($items): array
{
    return explode(',', $items);
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

function removeFromShopCart($username, $item_id): bool
{
    $db = getDatabaseConnection();
    try {
        $existingCart = getShopCart($username);
        $newCart = array();
        foreach ($existingCart as $item) {
            if ($item != $item_id) {
                $newCart[] = $item;
            }
        }
        $newCart = implode(',', $newCart);
        $stmt = $db->prepare('UPDATE user SET shopping_cart = ? WHERE username = ?');
        $stmt->execute(array($newCart, $username));
        return true;
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

function getWishlist($username)
{
    $db = getDatabaseConnection();
    try {
        $stmt = $db->prepare('SELECT wishlist FROM user WHERE username = ?');
        $stmt->execute(array($username));
        $result = $stmt->fetchColumn();
        if ($result !== false && $result !== null && !empty($result)) {
            return explode(',', $result);
        }
        return array(); // Return an empty array if no items found in the wishlist
    } catch(PDOException $e) {
        return false;
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

function removeFromWishList($username, $item_id): bool
{
    $db = getDatabaseConnection();
    try {
        $existingWishList = getWishList($username);
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
?>

