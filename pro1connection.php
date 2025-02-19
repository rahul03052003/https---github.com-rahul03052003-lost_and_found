<?php
error_reporting(E_ALL); // Enable all error reporting for debugging

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lost_and_found";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form inputs
    $name = $conn->real_escape_string($_POST['uname']);
    $reg_no = $conn->real_escape_string($_POST['reg_no']);
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $brand = $conn->real_escape_string($_POST['brand']);
    $phno = $conn->real_escape_string($_POST['phno']);
    $lastseen = $conn->real_escape_string($_POST['lastseen']);
    $describeit = $conn->real_escape_string($_POST['describeit']);
    $photo = $_FILES['photo']['name'];

    // Define the target directory for file uploads
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/project/uploads/";
    
    // Check if the 'uploads' directory exists, if not create it
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory with proper permissions
    }

    // Set the target file path
    $target_file = $target_dir . basename($photo);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    if (!empty($_FILES["photo"]["tmp_name"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size (limit: 2MB)
    if ($_FILES["photo"]["size"] > 2097152) {
        echo "Sorry, your file is too large. Maximum size is 2MB.";
        $uploadOk = 0;
    }

    // Allow only certain file formats (JPG, JPEG, PNG, GIF)
    $allowed_formats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check for any errors with file upload
    if ($_FILES["photo"]["error"] != 0) {
        echo "Error uploading file: " . $_FILES["photo"]["error"];
        $uploadOk = 0;
    }

    // If the file is okay to upload
    if ($uploadOk == 1) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Insert form data into the database, including the file path
            $sql = "INSERT INTO lost_items (name, reg_no, item_name, brand, phno, lastseen, describeit, photo_path)
                    VALUES ('$name', '$reg_no', '$item_name', '$brand', '$phno', '$lastseen', '$describeit', '$target_file')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
                echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.html';
                }, 3000);
              </script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File upload was not successful.";
    }

    // Close the database connection
    $conn->close();
}
?>