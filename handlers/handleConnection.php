<?php
function ConnectDB($servername, $username, $password, $dbname) {
    // Create connection.
    $conn = new mysqli($servername, $username, $password, $dbname);
    // If connection fails, return an error message.
    if ($conn->connect_error) {
        die(json_encode([
            "status" => "error",
            "message" => "Connection failed: " . $conn->connect_error
        ]));
    }
    // Else return the connection.
    return $conn;
}

