<?php
session_start();
require '../handlers/handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$conn = ConnectDB($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

$artistName = $_GET['name'] ?? '';

$artist = [];

if ($artistName) {
    $query = "SELECT * FROM artists WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $artistName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $artist = $result->fetch_assoc();
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist: <?php echo htmlspecialchars($artist['name'] ?? ''); ?></title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module" src="../handlers/handleMusic.js"></script>
    <script src="../miscellaneous/background.js"></script>
    <script type="module" src="../handlers/handleSearch.js"></script>
    <script src="../miscellaneous/utils.js"></script>
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
            <button onclick="accountOnClick()" class="account-btn">
                <i class="fa fa-user"></i>
                <span class="account-text">Account</span>
            </button>
        </div>
    </div>
</div>

<div class="color-block">

<div class="artist-section">
    <?php if (!empty($artist)): ?>
        <div class="artist-details">
            <h1 class="artist-name"><?php echo htmlspecialchars($artist['name']); ?></h1>
            <?php if (!empty($artist['image'])): ?>
                <img src="<?php echo htmlspecialchars($artist['image']); ?>" alt="Artist Image" class="artist-image">
            <?php endif; ?>
            <p class="artist-bio"><?php echo nl2br(htmlspecialchars($artist['bio'])); ?></p>
        </div>
    <?php else: ?>
        <h1>Artist not found.</h1>
    <?php endif; ?>
</div>

</div>

<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>
</body>
</html>

