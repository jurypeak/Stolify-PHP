<?php

require_once 'handleConnection.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$conn = new mysqli($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

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

header('Content-Type: application/json');
echo json_encode($albums);
