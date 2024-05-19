<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function getCurrentUser() {
    if (isset($_SESSION['username']) && is_string($_SESSION['username'])) {
        $db = getDatabaseConnection();
        $stmt = $db->prepare('SELECT username, currency, is_admin FROM user WHERE username = ?');
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }
    return null;
}


function setCurrentUser($username){
    $_SESSION['username'] = $username;
}

/*function getCurrentUser() {
    if (isset($_SESSION['username'])) {
        return getUser($_SESSION['username']);
    }
    return null;
}*/

function addMessage(string $type, string $content){
    $_SESSION['messages'][] = array('type' => $type, 'content' => $content);

}
function getLatestMessage() {
    if (!empty($_SESSION['messages'])) {
        // Get the index of the last element in the 'messages' array
        $latestIndex = count($_SESSION['messages']) - 1;

        // Retrieve the latest message from the 'messages' array
        $latestMessage = $_SESSION['messages'][$latestIndex];

        // Remove the latest message from the 'messages' array
        unset($_SESSION['messages'][$latestIndex]);

        // Return the latest message
        return $latestMessage;
    } else {
        // If there are no messages, return null or any other appropriate value
        return null;
    }
}