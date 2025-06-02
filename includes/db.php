<?php
// Database connection details
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Replace with your DB username
define('DB_PASS', ''); // Replace with your DB password
define('DB_NAME', 'online_quiz_system'); // Replace with your DB name

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully"; // For testing connection

// It's good practice to set the charset
$conn->set_charset("utf8mb4");

?>
