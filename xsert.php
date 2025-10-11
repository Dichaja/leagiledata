<?php

// Database connection
$host = 'localhost';
$db   = 'leagile';
$user = 'root';
$pass = '';

/*$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}*/

try {
    $conn = new PDO("mysql:host=$host;port=3307;dbname=$db;charset=utf8mb4", $user, $pass);
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

?>