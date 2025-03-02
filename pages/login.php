<?php
require '../handlers/handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');

$dotenv->load();

// Connect to database
$conn = ConnectDB($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

// Initialize response array
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

    // Create MySQLi connection (make sure to adjust your database connection details)
    $conn = new mysqli('localhost', 'root', '', 'stolify_db');  // Change these details as needed

    // Check for database connection error
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }

    // Escape special characters in the username and password
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Prepare and execute the SQL query
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password (assuming passwords are hashed)
        if (password_verify($password, $user['password'])) {
            // Login successful
            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
        } else {
            // Password incorrect
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        }
    } else {
        // User not found
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
    }

    // Close the connection
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
    <link rel="stylesheet" href="../style.css"> <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
    <script src="../handlers/handleLogin.js"></script>
    <script src="../miscellaneous/background.js"></script>
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
        <h2 class="subheading">We may get sued ;)</h2> <!-- Subheading below title -->
    </header>

    <form id="loginForm">
        <input type="text" id="username" name="username" placeholder="Username" required><br><br>
        <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span id="eye-icon" class="eye-icon" onclick="togglePassword()"><i class="fa fa-eye"></i></span> <!-- Eye icon -->
        </div>
        <br><br>
        <!-- Submit button -->
        <input type="submit" value="Login">

        <br><br>
        <!-- Forgot Password and Register Links -->
        <div class="account-links">
            <a href="forgotPassword.php" class="forgot-password">Forgot password?</a>
            <span class="delimiter">|</span> <!-- Delimiter between links -->
            <a href="register.php" class="register">Register for account</a>
        </div>
    </form>
</div>

<!-- Footer Section -->
<footer>
    <!-- Email Link in Footer (before the copyright) -->
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>

</body>
</html>
