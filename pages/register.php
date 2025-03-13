<?php
require '../handlers/handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$conn = ConnectDB($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Both fields are required']);
        exit;
    }

    $username = htmlspecialchars(trim($username));
    $password = htmlspecialchars(trim($password));

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error registering the user. Please try again later.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stolify</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
    <script src="../miscellaneous/background.js"></script>
    <script src="../handlers/handleRegister.js"></script>
    <script src="../miscellaneous/utils.js"></script>
</head>
<body>
<div class="color-block">
    <header>
        <div class="logo-title">
            <img src="../media/logo.svg" alt="Logo" class="logo">
            <h1>Stolify</h1>
        </div>
        <h2 class="subheading">We may get sued ;)</h2>
    </header>

    <form id="registrationForm" method="POST">
        <div class="input-container">
            <input type="email" name="email" placeholder="Email">
        </div>
        <div class="input-container">
            <input type="password" name="password" id="password" placeholder="Password">
            <span id="eye-icon" class="fa fa-eye" onclick="togglePassword()"></span>
        </div>
        <input type="submit" value="Register">
        <div class="account-links">
            <a href="forgotPassword.php" class="forgot-password">Forgot password?</a>
            <span class="delimiter">|</span>
            <a href="login.php" class="login">Sign In</a>
        </div>
    </form>
</div>

<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>
</body>
</html>
