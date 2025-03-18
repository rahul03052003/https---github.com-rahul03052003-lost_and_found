<?php
session_start();
include 'db_connection.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch all claim requests
$sql = "SELECT c.id, f.item_name, c.claimer_email, c.claimer_message, c.finder_email, c.status, c.created_at
        FROM claim_requests c
        JOIN found_items f ON c.item_id = f.id
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Claims</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container mt-5">
        <h2 class="text-center">🔍 Claim Requests</h2>

        <?php if ($result && $result->num_rows > 0) { ?>
            <table class="table table-bordered table-striped mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Claimer Email</th>
                        <th>Message</th>
                        <th>Finder Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['claimer_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['claimer_message']); ?></td>
                            <td><?php echo htmlspecialchars($row['finder_email']); ?></td>
                            <td>
                                <?php if ($row['status'] == 'Pending') { ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php } else { ?>
                                    <span class="badge bg-success">Resolved</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'Pending') { ?>
                                    <form action="update_claim_status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="claim_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm">✔ Approve</button>
                                    </form>
                                <?php } ?>
                                <form action="delete_claim.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="claim_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">❌ Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning text-center">⚠️ No claim requests found.</div>
        <?php } ?>
        <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">🔙 Back to Dashboard</a>
    </div>

</body>
</html>
