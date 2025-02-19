<?php
session_start(); // Start session if needed
include 'db_connection.php';

// Ensure database connection is active
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch all lost items
$sql = "SELECT id, name, reg_no, item_name, brand, phno, lastseen, describeit, photo_path FROM lost_items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Lost Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-danger text-white text-center py-3">
        <h1>Lost Items List</h1>
    </header>
    <div class="container my-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-danger text-center">📋 All Lost Items</h2>

                <?php if ($result && $result->num_rows > 0) { ?>
                    <table class="table table-bordered table-striped mt-4">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Registration Number</th>
                                <th>Item Name</th>
                                <th>Brand</th>
                                <th>Contact Number</th>
                                <th>Last Seen At</th>
                                <th>Description</th>
                                <th>Photo</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { 
                                $photo_path = htmlspecialchars($row['photo_path']);
                                $photo_url = !empty($photo_path) ? "http://localhost/project/images/" . basename($photo_path) : "default.jpg"; 
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['brand']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phno']); ?></td>
                                    <td><?php echo htmlspecialchars($row['lastseen']); ?></td>
                                    <td><?php echo htmlspecialchars($row['describeit']); ?></td>
                                    <td>
                                        <img src="<?php echo $photo_url; ?>" alt="Item Photo" width="100" height="100" class="rounded" 
                                             onerror="this.onerror=null;this.src='default.jpg';">
                                    </td>
                                    <td>
                                        <a href="edit_item.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete_item.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this item?');">
                                           Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-warning text-center">
                        ⚠️ No lost items found.
                    </div>
                <?php } ?>

                <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">🔙 Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
