<?php
$servername = "localhost"; // Database server
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "lost_and_found"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log error to a file
    error_log("Connection failed: " . $conn->connect_error, 3, "error_log.txt");
    
    // Display failure message and redirect after 3 seconds
    echo "<p>Connection failed. Please try again later.</p>";
    header("Refresh: 3; url=index.html"); // Redirect after 3 seconds
    exit();
} else {
    // Connection successful (You can remove this if you don't want the success message)
    // echo "<p>Connected successfully to the database.</p>";
}
?>
