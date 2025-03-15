// Purpose: Get all albums and their songs from the database and send them as a JSON response.
// For handleMusic.js to get the albums and their songs, it needs to send a request to this handler.

<?php

require_once 'handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Connect to the database.
$conn = ConnectDB($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

// Get all albums and their songs.
$sql = "SELECT albums.id AS album_id, albums.name AS album_name, songs.title, songs.artist, songs.audio_file 
FROM albums
JOIN songs ON albums.id = songs.album_id";
$result = $conn->query($sql);

// Store the albums and their songs in an array.
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

// Close the connection.
$conn->close();

// Send the albums and their songs as a JSON response.
header('Content-Type: application/json');
echo json_encode($albums);
