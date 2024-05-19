<?php
include_once(__DIR__ . "/../database/user.php");
include_once(__DIR__ . "/../utils/session.php");
include_once (__DIR__ . '/../database/db_upload.php');
include_once (__DIR__ . '/../database/item.php');

$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];
$category = $_POST['category'];
$username = $_SESSION['username'];
$userId=getUser($username)['user_id'];

$item_id = createNewItem($title, $description, $price, $category, $userId);

if($item_id){
    if ($_FILES['product-pic']['error'] === UPLOAD_ERR_OK) {
        // File was uploaded successfully, proceed with uploading and processing
        uploadItemImage($item_id,$title, $_FILES['product-pic']);
    }

    header('Location: ../pages/mainPage.php');
}
else{
    echo "Create failed";
}