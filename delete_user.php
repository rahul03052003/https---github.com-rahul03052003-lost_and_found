<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lost_and_found";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Prevent admin from being deleted
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user || $user['role'] == 'admin') {
        die("Unauthorized action.");
    }

    // Delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully!'); window.location='user.php';</script>";
    } else {
        echo "<script>alert('Error deleting user.');</script>";
    }
    
    $stmt->close();
} else {
    die("Invalid request.");
}

$conn->close();
?>
