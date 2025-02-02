<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stolify</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery first -->
    <script src="script.js" defer></script> <!-- Your script after jQuery -->
</head>
<body>

<!-- Color Block Layer (Wrapper) -->
<div class="color-block">

    <!-- Title and Logo Section -->
    <header>
        <div class="logo-title">
            <img src="media/logo.svg" alt="Logo" class="logo">
            <h1>Stolify</h1>
        </div>
        <h2 class="subheading">We may get sued ;)</h2> <!-- Subheading below title -->
    </header>

    <form>
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

