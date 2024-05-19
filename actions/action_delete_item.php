<?php
include_once(__DIR__ . "/../utils/session.php");
include_once (__DIR__ . '/../database/item.php');

$item_id = $_GET['id'];

if(deleteItem($item_id)){
    header('Location: ../pages/mainPage.php');
}
else{
    echo "Delete failed";
}