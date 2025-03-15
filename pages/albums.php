<?php
session_start();
require '../handlers/handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

// Load the environment variables.
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');

$dotenv->load();

// Connect to the database.
$conn = ConnectDB($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

// If the user is not logged in, redirect them to the login page.
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Create an empty array to store the albums.
$albums = [];

// Get all albums from the database.
$query = "SELECT * FROM albums";
$result = $conn->query($query);

// Store the albums in an array.
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $albums[] = $row;
    }
    // If no albums are found, display a message.
} else {
    echo "No albums found.";
}

// Close the connection.
$conn->close();
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

    <div class="album-grid-container" id="album-grid-container">
        <?php
        // Check if there are any albums available.
        if (!empty($albums)) {
            // Loop through the albums and display them.
            foreach ($albums as $album) {
                echo '
                <div class="album-grid-item" data-album="' . htmlspecialchars($album['name']) . '" data-artist="' . htmlspecialchars($album['artist']) . '" data-year="' . htmlspecialchars($album['year']) . '">
                    <img src="' . htmlspecialchars($album['cover_image']) . '" alt="' . htmlspecialchars($album['name']) . ' Cover" class="album-cover">
                    <button class="hover-play-btn">
                        <i class="fa fa-play"></i> <!-- Play Icon -->
                    </button>
                    <h3 class="album-name">' . htmlspecialchars($album['name']) . '</h3>
                    <p class="artist-name">
                    <a href="artist.php?name=' . $album['artist'] . '">' . htmlspecialchars($album['artist']) . '</a>
                    </p>
                    <p class="album-description">' . htmlspecialchars($album['year']) . ' • Album</p>
                </div>';
            }
            // If no albums are available, display a message.
        } else {
            echo '<p>No albums available.</p>';
        }
        ?>
    </div>
</div>

<div class="music-control-panel">
    <div class="music-content">
        <div class="music-info">
            <p class="song-title" id="song-title">Song Title</p>
            <p class="song-artist" id="song-artist">Artist Name</p>
        </div>

        <div class="controls">
            <button class="control-btn prev-btn">
                <i class="fa fa-backward"></i>
            </button>
            <button class="control-btn play-btn">
                <i class="fa fa-play"></i>
            </button>
            <button class="control-btn next-btn">
                <i class="fa fa-forward"></i>
            </button>
        </div>

        <div class="volume-control">
            <input type="range" class="volume-slider" id="volume-slider" min="0" max="1" step="0.01" value="0.5">
        </div>
    </div>
</div>

<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>




