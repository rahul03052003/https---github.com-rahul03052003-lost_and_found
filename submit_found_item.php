<?php
session_start();


include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $date_found = $_POST['date_found'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $user_id = $_SESSION['user_id'];
    
    // Handle Image Upload
    $target_dir = "uploads/";
    $image = NULL; // Default value

    if (!empty($_FILES["image"]["name"])) {
        $image_name = basename($_FILES["image"]["name"]);
        $image_path = $target_dir . time() . "_" . $image_name; // Unique filename
        $imageFileType = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));

        // Validate file type & size
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowed_types) && $_FILES["image"]["size"] <= 5000000) { // Max 5MB
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                $image = $image_path;
            } else {
                echo "<script>alert('Error uploading image.'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Invalid image type or size!'); window.history.back();</script>";
            exit();
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO found_items (user_id, item_name, description, location, date_found, mobile, email, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $user_id, $item_name, $description, $location, $date_found, $mobile, $email, $image);

    if ($stmt->execute()) {
        echo "<script>alert('Item reported successfully!'); window.location='user_dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
