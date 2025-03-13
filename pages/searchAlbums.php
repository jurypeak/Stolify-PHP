<?php

session_start();
require '../handlers/handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$query = isset($_GET['query']) ? $_GET['query'] : '';

if (empty($query)) {
    echo json_encode([]);
}

$conn = new mysqli($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
}

$sql = "SELECT * FROM albums WHERE name LIKE ? OR artist LIKE ?";
$stmt = $conn->prepare($sql);

$searchTerm = "%" . $query . "%";

$stmt->bind_param("ss", $searchTerm, $searchTerm);

$stmt->execute();

$result = $stmt->get_result();

$filteredAlbums = [];
while ($album = $result->fetch_assoc()) {
    $filteredAlbums[] = $album;
}

$stmt->close();
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
    <script type="module" src="../handlers/handleSearch.js"></script>
    <script src="../miscellaneous/background.js"></script>
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

    <section id="results-container">
        <h2>Search Results for: "<?php echo htmlspecialchars($query); ?>"</h2>

        <?php if (!empty($filteredAlbums)): ?>
            <div class="album-grid-container" id="album-grid-container">
                <?php foreach ($filteredAlbums as $album): ?>
                    <div class="album-grid-item" data-album="<?php echo htmlspecialchars($album['name']); ?>"
                         data-artist="<?php echo htmlspecialchars($album['artist']); ?>"
                         data-year="<?php echo htmlspecialchars($album['year']); ?>">
                        <img src="<?php echo htmlspecialchars($album['cover_image']); ?>" alt="<?php echo htmlspecialchars($album['name']); ?> Cover" class="album-cover">
                        <button class="hover-play-btn">
                            <i class="fa fa-play"></i>
                        </button>
                        <h3><?php echo htmlspecialchars($album['name']); ?></h3>
                        <p class="artist-name"><?php echo htmlspecialchars($album['artist']); ?></p>
                        <p class="album-description"><?php echo htmlspecialchars($album['year']); ?> • Album</p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-albums">
                <p>No albums found for your search.</p>
            </div>
        <?php endif; ?>
    </section>

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

</body>
</html>
