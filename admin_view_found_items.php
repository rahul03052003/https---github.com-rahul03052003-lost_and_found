<?php
session_start();
include 'db_connection.php';

// Ensure database connection is active
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle item deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch the image path before deleting
    $sql = "SELECT image FROM found_items WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Delete image file if exists
        if (!empty($row['image']) && file_exists($row['image'])) {
            unlink($row['image']);
        }

        // Delete the item from the database
        $delete_sql = "DELETE FROM found_items WHERE id=?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $delete_id);

        if ($delete_stmt->execute()) {
            echo "<script>alert('Item deleted successfully!'); window.location.href='admin_view_found_items.php';</script>";
        } else {
            echo "<script>alert('Error deleting item.');</script>";
        }
    }
}

// Fetch all found items
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT id, item_name, description, location, mobile, email, date_found, image FROM found_items WHERE item_name LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Found Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<style>
        .navbar {
            margin-bottom: 20px;
        }
        .header-container {
            background: linear-gradient(135deg, #dc3545, #ff6b81);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: white;
            margin-top: 20px; /* Added space between Admin Panel and header */
        }
        .card {
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-danger shadow">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="admin_view_found_items.php">View Found Items</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="fetch.php">View Lost Items</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="user.php">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header-container">
            <h1>Admin Panel - Found Item</h1>
        </div>
    </div>

     <!-- ✅ Search Bar -->
     <div class="container mt-4">
        <form action="" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="🔍 Search by Item Name..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="fetch.php" class="btn btn-secondary ms-2">Reset</a>
        </form>
    </div>
    <div class="container my-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-danger text-center">📋 Found Items List</h2>


                <?php if ($result && $result->num_rows > 0) { ?>
                    <table class="table table-bordered table-striped mt-4">
                        <thead class="table-dark">
                            <tr>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Location</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Date Found</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
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
                                    <td>
                                        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="admin_view_found_items.php?delete_id=<?php echo $row['id']; ?>" 
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
                        ⚠️ No found items available.
                    </div>
                <?php } ?>

                <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">🔙 Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
