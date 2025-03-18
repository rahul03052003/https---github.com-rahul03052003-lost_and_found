<?php
session_start();
include 'db_connection.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT f.id, f.item_name, f.description, f.location, f.mobile, f.email, f.date_found, f.image, 
               cr.status 
        FROM found_items f 
        LEFT JOIN claim_requests cr ON f.id = cr.item_id 
        WHERE f.item_name LIKE ?";
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
    <title>View Found Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-danger shadow">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="#">User Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="user_report.html">Report Lost Item</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="user_fetch.php">Items Lost</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="found_item.php">Report Found Item</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="fetch_found_items.php">View Found Items</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header -->
<header class="bg-danger text-white text-center py-3">
    <h1>📋 Found Items List</h1>
</header>

<div class="container my-4">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title">All Found Items</h2>

            <!-- Search Form -->
            <form method="GET" class="d-flex mb-3">
                <input type="text" name="search" class="form-control me-2" placeholder="🔍 Search by Item Name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="fetch_found_items.php" class="btn btn-secondary ms-2">Reset</a>
            </form>

            <?php if ($result && $result->num_rows > 0) { ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Date Found</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_found']); ?></td>
                                <td>
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['image']); ?>" width="100" height="100" class="rounded">
                                    <?php else: ?>
                                        <span class="text-muted">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'Resolved') { ?>
                                        <span class="badge bg-success">Resolved</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <center>
                                        <?php if ($row['status'] !== 'Resolved') { ?>
                                            <a href="claim_item.php?id=<?php echo $row['id']; ?>&finder_email=<?php echo urlencode($row['email']); ?>" class="btn btn-success btn-sm">It's Mine</a>
                                        <?php } ?>
                                    </center>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="user_dashboard.php" class="btn btn-secondary w-100 mt-3">🔙 Back to Dashboard</a>
            <?php } else { ?>
                <div class="alert alert-warning text-center">
                    ⚠️ No found items available.
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
