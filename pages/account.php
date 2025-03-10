<?php
session_start();

// Check if the user is already logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    session_unset();  // Unset all session variables
    session_destroy();  // Destroy the session
    header("Location: login.php");  // Redirect to login page
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module" src="../handlers/handleMusic.js"></script>
    <script type="module" src="../handlers/handleSearch.js"></script>
    <script src="../miscellaneous/background.js"></script>
    <script src="../miscellaneous/utils.js"></script>
</head>
<body>
<!-- Top Panel for Logo, Search Bar, and Account -->
<div class="top-panel">
    <div class="logo-search-container">
        <div class="logo-title logo-small">
            <img src="../media/logo.svg" alt="Logo" class="logo" onclick="logoOnClick()">
        </div>

        <div class="search-bar-container">
            <input type="text" id="search" placeholder="What would you like to play?" class="search-bar">
            <i class="fa fa-search search-icon"></i>
            <div id="no-results" style="display:none;">No results found.</div>
        </div>

        <div class="account-container">
            <button onclick="accountOnClick()" class="account-btn">
                <i class="fa fa-user"></i>
                <span class="account-text">Account</span>
            </button>
        </div>
    </div>
</div>

<!-- Color Block Layer (Wrapper) -->
<div class="color-block">

    <!-- User Account Information -->
    <form id="loginForm" class="account-form" method="POST">
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required readonly><br><br>
        <div class="password-container">
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($_SESSION['password']); ?>" required readonly>
            <span id="eye-icon" class="eye-icon" onclick="togglePassword()"><i class="fa fa-eye"></i></span> <!-- Eye icon -->
        </div>
        <br><br>
        <!-- Submit button -->
        <input type="submit" value="Change Details">

        <button type="submit" name="logout" class="logout-btn">Log Out</button>
     <form>
</div>

<!-- Footer Section -->
<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>