<?php
// Include the database connection
require '../handlers/handleConnection.php';

// Load environment variables
require_once '../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get the query parameter from the URL
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Check if the query is empty and return an empty array if so
if (empty($query)) {
    echo json_encode([]);
}

// Establish the database connection
$conn = new mysqli($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

// Check the connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
}

// Fetch albums from the database based on the search query
$sql = "SELECT * FROM albums WHERE name LIKE ? OR artist LIKE ?";
$stmt = $conn->prepare($sql);

// Sanitize the query input
$searchTerm = "%" . $query . "%";

// Bind the parameters to the SQL query
$stmt->bind_param("ss", $searchTerm, $searchTerm);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch all albums from the result
$filteredAlbums = [];
while ($album = $result->fetch_assoc()) {
    $filteredAlbums[] = $album;
}

// Close the statement and connection
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module" src="../handlers/handleMusic.js"></script>
    <script type="module" src="../handlers/handleSearch.js"></script>
    <script src="../miscellaneous/background.js"></script>
    <script src="../miscellaneous/utils.js"></script>
</head>
<body>

<!-- Color Block Layer (Wrapper) -->
<div class="color-block">

    <div class="logo-search-container">
        <div class="logo-title logo-small" onclick="logoOnClick();">
            <img src="../media/logo.svg" alt="Logo" class="logo">
        </div>
        <!-- Search Bar Section -->
        <div class="search-bar-container">
            <input type="text" id="search" placeholder="What would you like to play?" class="search-bar" value="<?php echo htmlspecialchars($query); ?>">
            <i class="fa fa-search search-icon"></i> <!-- Search Icon -->
        </div>
    </div>

    <!-- Search Results Section -->
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
                            <i class="fa fa-play"></i> <!-- Play Icon -->
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

<!-- Music Control Panel (Sidebar) -->
<div class="music-control-panel">
    <div class="music-content">
        <div class="music-info">
            <p class="song-title" id="song-title">Song Title</p>
            <p class="song-artist" id="song-artist">Artist Name</p>
        </div>

        <div class="controls">
            <button class="control-btn prev-btn">
                <i class="fa fa-backward"></i> <!-- Previous song -->
            </button>
            <button class="control-btn play-btn">
                <i class="fa fa-play"></i> <!-- Play/Pause -->
            </button>
            <button class="control-btn next-btn">
                <i class="fa fa-forward"></i> <!-- Next song -->
            </button>
        </div>

        <!-- Volume Control -->
        <div class="volume-control">
            <input type="range" class="volume-slider" id="volume-slider" min="0" max="1" step="0.01" value="0.5">
        </div>
    </div>
</div>

<!-- Footer Section -->
<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>

</body>
</html>
