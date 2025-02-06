<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lost_and_found";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set
if (!isset($_GET['id'])) {
    die("ID is required.");
}

$id = $_GET['id'];

// Fetch photo path before deleting
$sql = "SELECT photo_path FROM lost_items WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Delete item from the database
$sql = "DELETE FROM lost_items WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    // Delete the photo file if it exists
    if (!empty($row['photo_path']) && file_exists($row['photo_path'])) {
        unlink($row['photo_path']); // Delete the file
    }

    echo "<script>alert('Item deleted successfully!'); window.location.href='fetch.php';</script>";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
