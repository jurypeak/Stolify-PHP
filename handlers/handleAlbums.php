<?php
// Include database connection
require_once 'handleConnection.php';

// Load environment variables
require_once '../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Establish database connection
$conn = new mysqli($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch albums and their songs
$sql = "SELECT albums.id AS album_id, albums.name AS album_name, songs.title, songs.artist, songs.audio_file 
FROM albums
JOIN songs ON albums.id = songs.album_id";
$result = $conn->query($sql);

$albums = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $albumName = $row["album_name"];
        $albums[$albumName][] = [
            "title" => $row["title"],
            "artist" => $row["artist"],
            "audioFile" => $row["audio_file"]
    ];
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($albums);
