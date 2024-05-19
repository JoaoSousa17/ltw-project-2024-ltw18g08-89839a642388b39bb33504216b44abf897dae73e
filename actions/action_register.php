<?php/*
include_once('../database/connection.db.php');
include_once('../database/user.php');
include_once('../utils/password.php');

if (!isset($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['location'], $_POST['address'], $_POST['postal_code'], $_POST['currency'])) {
    die(header('Location: ../pages/register.php'));
}

$email = trim($_POST['email']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);
$location = trim($_POST['location']);
$address = trim($_POST['address']);
$postal_code = trim($_POST['postal_code']);
$currency = trim($_POST['currency']);

if ($password !== $confirm_password) {
    die(header('Location: ../pages/register.php'));
}

$hashed_password = hashPassword($password);

if (registerUser($email, $username, $hashed_password, $location, $address, $postal_code, $currency)) {
    header('Location: ../pages/login.php');
} else {
    header('Location: ../pages/register.php');
}*/
?>
<?php
include_once(__DIR__ . '/../database/connection.db.php'); // Ajuste o caminho conforme necessário
include_once(__DIR__ . '/../database/user.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $location = $_POST['location'];
    $address = $_POST['address'];
    $postal_code = $_POST['postal_code'];
    $currency = $_POST['currency'];

    // Verificar se as senhas correspondem
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Hash da senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Chamar a função para criar um novo usuário
    if (createUser($email, $username, $hashed_password, $location, $address, $postal_code, $currency)) {
        echo "User registered successfully.";
        header('Location: ../pages/login.php');
        exit();
    } else {
        echo "Error registering user.";
    }
}
?>