<?php
include_once (__DIR__ . '/../database/connection.db.php');
include_once (__DIR__ . '/../database/user.php');
function uploadProfileImage($username, $tmp)
{
    $db = getDatabaseConnection();

    // Check if a file was uploaded
    if ($tmp) {
        // Fetch current profile picture filename
        $stmt = $db->prepare('SELECT Profile_Picture FROM user WHERE Username = ?');
        $stmt->execute(array($username));
        $img_name = $stmt->fetchColumn();

        // Delete old profile picture if it's not the default one
        if ($img_name != "default") {
            unlink(__DIR__ . "$img_name");
        }

        $stmt = $db->prepare('UPDATE user SET Profile_Picture = ? WHERE Username = ?');
        $stmt->execute(array($username, $username));

        // Upload and manipulate image
        $originalFileName = __DIR__ . "/../database/images/profiles/originals/$username.jpg"; // Corrected file path
        $mediumFileName = __DIR__ . "/../database/images/profiles/thumbnails_medium/$username.jpg"; // Corrected file path
        $smallFileName = __DIR__ . "/../database/images/profiles/thumbnails_small/$username.jpg"; // Corrected file path
        uploadImage($tmp['tmp_name'], $originalFileName, $mediumFileName, $smallFileName);
    }
    else
        return true;


    return false;
}

function uploadItemImage($itemId, $imageName, $tmpFile)
{
    if ($tmpFile) {
        // Database connection
        $db = getDatabaseConnection();

        try {
            // Prepare the SQL statement to update the item's image name
            $stmt = $db->prepare('UPDATE item SET item_pictures = ? WHERE item_id = ?');
            $stmt->execute(array($imageName, $itemId));

            // Fetch current item image filename, if exists
            $stmt = $db->prepare('SELECT item_pictures FROM item WHERE item_id = ?');
            $stmt->execute(array($itemId));
            $oldImageName = $stmt->fetchColumn();

            // Delete old item image if it exists
            if ($oldImageName) {
                unlink(__DIR__ . "/../database/images/items/originals/$oldImageName.jpg");
                unlink(__DIR__ . "/../database/images/items/thumbnails_medium/$oldImageName.jpg");
            }

            // File paths for original and medium-sized images
            $originalFilePath = __DIR__ . "/../database/images/items/originals/$imageName.jpg";
            $mediumFilePath = __DIR__ . "/../database/images/items/thumbnails_medium/$imageName.jpg";

            // Upload the image
            uploadImage($tmpFile['tmp_name'], $originalFilePath, $mediumFilePath, null);
        } catch (PDOException $e) {

            // Handle database errors
            // Log or display the error message
            // Redirect to an error page or display a friendly error message to the user
        }
    }

}

function deleteSetProfile($username){
    deleteIMG(__DIR__ . "/../database/images/profiles/originals/$username.jpg");
    deleteIMG(__DIR__ . "/../database/images/profiles/thumbnails_medium/$username.jpg");
    deleteIMG(__DIR__ . "/../database/images/profiles/thumbnails_small/$username.jpg");
}
function deleteSetItem($item){
    deleteIMG(__DIR__ . "/../database/images/items/originals/$item.jpg");
    deleteIMG(__DIR__ . "/../database/images/items/thumbnails_medium/$item.jpg");
}
function deleteIMG($img){
    unlink($img);
}

function uploadImage($tmpFile, $originalFileName, $mediumFileName, $smallFileName)
{
    // Move the uploaded file to the destination
    if (!move_uploaded_file($tmpFile, $originalFileName)) {
        return false;
    }

    // Create an image resource from the original file
    $originalImage = imagecreatefromstring(file_get_contents($originalFileName));
    if (!$originalImage) {
        return false;
    }

    // Get the dimensions of the original image
    $width = imagesx($originalImage);
    $height = imagesy($originalImage);

    // Create a small thumbnail if required
    if ($smallFileName) {
        $square = min($width, $height);
        $smallImage = imagecreatetruecolor(200, 200);
        imagecopyresized($smallImage, $originalImage, 0, 0, ($width > $square) ? ($width - $square) / 2 : 0, ($height > $square) ? ($height - $square) / 2 : 0, 200, 200, $square, $square);
        imagejpeg($smallImage, $smallFileName);
        imagedestroy($smallImage);
    }

    // Create a medium-sized image
    $mediumWidth = $width;
    $mediumHeight = $height;
    if ($mediumWidth > 400) {
        $mediumWidth = 400;
        $mediumHeight = $mediumHeight * ($mediumWidth / $width);
    }
    $mediumImage = imagecreatetruecolor($mediumWidth, $mediumHeight);
    imagecopyresized($mediumImage, $originalImage, 0, 0, 0, 0, $mediumWidth, $mediumHeight, $width, $height);
    imagejpeg($mediumImage, $mediumFileName);
    imagedestroy($mediumImage);

    // Clean up
    imagedestroy($originalImage);

    return true;
}
