<?php
session_start();
include 'db_connection.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $date_found = $_POST['date_found'];
    $image_path = "";

    // Check if a new image is uploaded
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image type and size
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
        } elseif ($_FILES["image"]["size"] > 2000000) { // 2MB limit
            echo "<script>alert('Image size should not exceed 2MB.');</script>";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        }
    }

    // Update query
    if (!empty($image_path)) {
        $sql = "UPDATE found_items SET item_name=?, description=?, location=?, mobile=?, email=?, date_found=?, image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $item_name, $description, $location, $mobile, $email, $date_found, $image_path, $id);
    } else {
        $sql = "UPDATE found_items SET item_name=?, description=?, location=?, mobile=?, email=?, date_found=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $item_name, $description, $location, $mobile, $email, $date_found, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Item updated successfully!'); window.location.href='fetch_found_items.php';</script>";
    } else {
        echo "<script>alert('Error updating item.');</script>";
    }
}

$sql = "SELECT * FROM found_items WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Item not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Found Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-warning">✏️ Update Found Item</h2>
        <form method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            <div class="mb-3">
                <label class="form-label">Item Name</label>
                <input type="text" class="form-control" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($item['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Location Found</label>
                <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($item['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mobile</label>
                <input type="tel" class="form-control" name="mobile" maxlength="10" pattern="[1-9]{1}[0-9]{9}" value="<?php echo htmlspecialchars($item['mobile']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($item['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date Found</label>
                <input type="date" class="form-control" name="date_found" value="<?php echo htmlspecialchars($item['date_found']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <?php if (!empty($item['image'])): ?>
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" width="150" height="150" class="rounded">
                <?php else: ?>
                    <span class="text-muted">No Image</span>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload New Image (optional)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-warning w-100">Update Item</button>
        </form>
    </div>
</body>
</html>
