<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

include 'pro2connection.php'; // Database connection

$username = $_SESSION['username']; // Get the logged-in user's username

// Function to get count from database
function getCount($conn, $query) {
    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    $row = $result->fetch_array();
    return $row[0];
}

// Fetch statistics
$lost_items = getCount($conn, "SELECT COUNT(*) FROM lost_items WHERE name = '$username'");
$found_items = getCount($conn, "SELECT COUNT(*) FROM found_items WHERE user_id IS NULL"); // Assuming NULL means user-reported items
$resolved_cases = getCount($conn, "SELECT COUNT(*) FROM claim_requests WHERE finder_email = '$username' AND status = 'Resolved'");

// Fetch recent activities dynamically
$activities = [];
$activity_query = "
    (SELECT 'Lost Item Reported' AS type, item_name AS details, created_at AS date 
     FROM lost_items WHERE name = '$username') 
    UNION 
    (SELECT 'Found Item Reported' AS type, item_name AS details, date_found AS date 
     FROM found_items WHERE user_id IS NULL) 
    UNION 
    (SELECT 'Claim Request Submitted' AS type, CONCAT('Claimed Item ID: ', item_id) AS details, created_at AS date 
     FROM claim_requests WHERE claimer_email = '$username')
    ORDER BY date DESC LIMIT 5
";

$result = $conn->query($activity_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
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

    <div class="container mt-5">
        <h2 class="text-center">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
        
        <!-- Dashboard Statistics -->
        <div class="row text-center mt-4">
            <div class="col-md-4">
                <div class="card shadow p-3 border-danger">
                    <h3 class="text-danger fw-bold"><?= $lost_items; ?></h3>
                    <p>Items Lost</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-3 border-success">
                    <h3 class="text-success fw-bold"><?= $found_items; ?></h3>
                    <p>Items Found</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-3 border-warning">
                    <h3 class="text-warning fw-bold"><?= $resolved_cases; ?></h3>
                    <p>Resolved Cases</p>
                </div>
            </div>
        </div>

        <!-- Dynamic Recent Activity Section -->
        <div class="mt-5">
            <h3 class="text-center">Recent Activity</h3>
            <ul class="list-group">
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $activity): ?>
                        <li class="list-group-item">
                            <strong><?= $activity['type']; ?>:</strong> <?= htmlspecialchars($activity['details']); ?>
                            <span class="text-muted float-end"><?= date("M d, Y", strtotime($activity['date'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-center text-muted">No recent activities.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Announcements -->
        <div class="mt-4">
            <h3 class="text-center">📢 Announcements</h3>
            <div class="alert alert-info">🔔 New Feature: You can now upload images for lost items!</div>
            <div class="alert alert-warning">⚠ Reminder: Always update your lost item details for better chances of recovery.</div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>&copy; 2025 Lost & Found Tracker | Developed by NIE Boys</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
