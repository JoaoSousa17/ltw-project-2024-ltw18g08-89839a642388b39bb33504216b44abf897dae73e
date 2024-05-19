<?php
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');

session_start();
$current_user = getCurrentUser();

if (!$current_user) {
    header('Location: ../pages/login.php');
    exit();
}

$item_id = $_GET['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];
$category = $_POST['category'];
$product_pic = $_FILES['product-pic'];

// Verificar se o usuário logado é o vendedor do item ou um administrador
$item = getItemById($item_id);
if ($item['seller_id'] != $current_user['user_id'] && !isAdmin($current_user['username'])) {
    header('Location: ../pages/mainPage.php');
    exit();
}

// Atualizar detalhes do item
updateItem($item_id, $title, $description, $price, $category);

// Se uma nova imagem foi carregada, atualizá-la
if ($product_pic['tmp_name']) {
    uploadItemImage($item_id, $item_id, $product_pic);
}

header('Location: ../pages/item.php?id=' . $item_id);
exit();
?>