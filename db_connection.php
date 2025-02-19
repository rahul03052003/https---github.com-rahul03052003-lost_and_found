<?php
$servername = "localhost";
$username = "root";  // Change if different
$password = "";      // Change if different
$database = "lost_and_found"; // Ensure correct DB name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
