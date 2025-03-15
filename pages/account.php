<?php
session_start();
require '../handlers/handleConnection.php';
require_once '../vendor/autoload.php';
use Dotenv\Dotenv;

// If the user is not logged in, redirect them to the login page.
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// If the user clicks the logout button, unset the session and destroy it, then redirect them to the login page.
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Load the environment variables.
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Connect to the database.
$conn = ConnectDB($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

// If the user submits the form to change their account details, update the database with the new details.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accountForm'])) {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // If the username or password is empty, return an error message.
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Both fields are required']);
        exit;
    }

    // If the user ID is not set, return an error message.
    if (!isset($_SESSION['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User ID not found. Please log in again.']);
        exit;
    }

    // Check if the user exists in the database.
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user is not found, return an error message.
    if (!$result->num_rows) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

    // Update the session variables with the new username and password.
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;

    // Hash the password before storing it in the database.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's details in the database.
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $hashedPassword, $_SESSION['id']);

    // If the query is successful, return a success message.
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Account details updated successfully!']);
        // If the query fails, return an error message.
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating account details. Please try again later.']);
    }

    // Close the statement and the connection.
    $stmt->close();
    $conn->close();
    exit;
}

// If the user submits the form to delete their account, delete the account from the database.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-account'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);

    // If the query is successful, unset the session and destroy it, then return a success message.
    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        echo json_encode([
            'status' => 'success',
            'message' => 'Your account has been deleted successfully.'
        ]);
        // If the query fails, return an error message.
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting account. Please try again later.']);
    }
    // Close the statement and the connection.
    exit();
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
    <script type="module" src="../handlers/handleMusic.js"></script>
    <script type="module" src="../handlers/handleSearch.js"></script>
    <script src="../miscellaneous/background.js"></script>
    <script src="../miscellaneous/utils.js"></script>
    <script src="../handlers/handleChange.js"></script>
    <script src="../handlers/handleDeletion.js"></script>
</head>
<body>

<div class="top-panel">
    <div class="top-panel-container">
        <div class="logo-title logo-small">
            <img src="../media/logo.svg" alt="Logo" class="logo" onclick="logoOnClick()">
        </div>

        <div class="search-bar-container">
            <input type="text" id="search" placeholder="What would you like to play?" class="search-bar">
            <i class="fa fa-search search-icon"></i>
            <div id="no-results" style="display:none;">No results found.</div>
        </div>

        <div class="account-container">
            <form id="logoutForm" method="POST">
                <button type="submit" class="logout-btn" name="logout">Logout</button>
            </form>
        </div>
    </div>
</div>

<div class="color-block">

    <form id="accountForm" method="POST">
        <div class="input-container">
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
        </div>
        <div class="input-container">
            <input type="password" name="password" id="password" placeholder="Change Password.">
            <span id="eye-icon" class="fa fa-eye" onclick="togglePassword()"></span>
        </div>
        <input type="submit" name="accountForm" value="Change Details">
     </form>

    <form id="deleteAccountForm" method="POST">
        <input type="submit" name="delete-account" value="Delete Account" >
    </form>

</div>

<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>

</body>
</html>