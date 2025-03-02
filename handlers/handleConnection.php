<?php
// Used mySQLi cause PDO didn't work.
function ConnectDB($servername, $username, $password, $dbname) {
    // Create a new MySQLi connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if connection is successful
    if ($conn->connect_error) {
        die(json_encode([
            "status" => "error",
            "message" => "Connection failed: " . $conn->connect_error
        ]));
    }

    return $conn;
}

