<?php
session_start();
include 'db_connection.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, item_name, description, location, mobile, email, date_found, image FROM found_items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Found Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<body>
    <div class="container mt-5">
        <h2 class="text-center">📋 Found Items List</h2>

        <?php if ($result && $result->num_rows > 0) { ?>
            <table class="table table-bordered table-striped mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Date Found</th>
                        <th>Image</th>
                    
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobile']); ?></td>  
                            <td><?php echo htmlspecialchars($row['email']); ?></td>  
                            <td><?php echo htmlspecialchars($row['date_found']); ?></td>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" width="100" height="100" class="rounded">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning text-center">
                ⚠️ No found items available.
            </div>
        <?php } ?>
    </div>
    
</body>
</html>
