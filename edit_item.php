<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lost_and_found";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set
if (!isset($_GET['id'])) {
    die("ID is required.");
}

$id = $_GET['id'];

// Fetch item details
$sql = "SELECT * FROM lost_items WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Item not found.");
}

$row = $result->fetch_assoc();

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $reg_no = $conn->real_escape_string($_POST['reg_no']);
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $brand = $conn->real_escape_string($_POST['brand']);
    $phno = $conn->real_escape_string($_POST['phno']);
    $lastseen = $conn->real_escape_string($_POST['lastseen']);
    $describeit = $conn->real_escape_string($_POST['describeit']);

    $sql = "UPDATE lost_items SET 
            name='$name', reg_no='$reg_no', item_name='$item_name', 
            brand='$brand', phno='$phno', lastseen='$lastseen', describeit='$describeit' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item updated successfully!'); window.location.href='fetch.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lost Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title">Edit Lost Item</h2>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Name:</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Registration Number:</label>
                        <input type="text" name="reg_no" class="form-control" value="<?php echo $row['reg_no']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item Name:</label>
                        <input type="text" name="item_name" class="form-control" value="<?php echo $row['item_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Brand:</label>
                        <input type="text" name="brand" class="form-control" value="<?php echo $row['brand']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number:</label>
                        <input type="text" name="phno" class="form-control" value="<?php echo $row['phno']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Seen At:</label>
                        <input type="text" name="lastseen" class="form-control" value="<?php echo $row['lastseen']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description:</label>
                        <textarea name="describeit" class="form-control" required><?php echo $row['describeit']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="fetch.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
