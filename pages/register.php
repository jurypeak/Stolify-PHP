<?php
require '../handlers/handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Connect to database
$conn = ConnectDB($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get username and password from the request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the input
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Both fields are required']);
        exit;
    }

    // Escape special characters to prevent SQL injection
    $username = htmlspecialchars(trim($username)); // Remove extra spaces and special characters
    $password = htmlspecialchars(trim($password));

    // Hash the password for storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert user into the database using a prepared statement
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword); // Bind the username and password

    if ($stmt->execute()) {
        // Registration successful
        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } else {
        // Error executing query
        echo json_encode(['status' => 'error', 'message' => 'Error registering the user. Please try again later.']);
    }

    // Close the statement and connection
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
    <script src="../miscellaneous/background.js"></script>
    <script src="../handlers/handleRegister.js"></script>
    <script src="../miscellaneous/utils.js"></script>
</head>
<body>
<!-- Color Block Layer (Wrapper) -->
<div class="color-block">
    <!-- Title and Logo Section -->
    <header>
        <div class="logo-title">
            <img src="../media/logo.svg" alt="Logo" class="logo">
            <h1>Stolify</h1>
        </div>
        <h2 class="subheading">We may get sued ;)</h2>
    </header>

    <form id="registrationForm" method="POST">
        <input type="text" id="username" name="username" placeholder="Username" required><br><br>
        <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span id="eye-icon" class="eye-icon" onclick="togglePassword()"><i class="fa fa-eye"></i></span>
        </div>
        <br><br>
        <input type="submit" value="Register">
        <br><br>
        <div class="account-links">
            <a href="forgotPassword.php" class="forgot-password">Forgot password?</a>
            <span class="delimiter">|</span>
            <a href="login.php" class="login">Sign In</a>
        </div>
    </form>
</div>

<!-- Footer Section -->
<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>
</body>
</html>
