<?php
session_start();
require '../handlers/handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$conn = ConnectDB($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

$response = array();

// If the user submits the login form, check if the username and password are correct.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the username and password from the form.
    // Trim and sanitize the username and password.
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // If the username or password is empty, return an error message.
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Both fields are required']);
        exit;
    }

    // Check if the user exists in the database.
    // Prepare the SQL statement.
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user is found, check if the password is correct.
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['password'] = $user['password'];

            // Send a success message if the login is successful.
            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
            // If the user is not found, return an error message.
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        }
        // Any other error, return an error message.
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
    }

    // Close the connection.
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
    <script src="../handlers/handleLogin.js"></script>
    <script src="../miscellaneous/background.js"></script>
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

    <form id="loginForm">
        <div class="input-container">
            <input type="email" name="email" placeholder="Email">
        </div>
        <div class="input-container">
            <input type="password" name="password" id="password" placeholder="Password">
            <span id="eye-icon" class="fa fa-eye" onclick="togglePassword()"></span>
        </div>
        <input type="submit" value="Login">

        <div class="account-links">
            <a href="forgotPassword.php" class="forgot-password">Forgot password?</a>
            <span class="delimiter">|</span> <!-- Delimiter between links -->
            <a href="register.php" class="register">Register for account</a>
        </div>
    </form>
</div>

<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>

</body>
</html>
