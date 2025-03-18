<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lost_and_found";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Fetch only "user" role
$sql = "SELECT id, username, role, created_at FROM users WHERE role = 'user'";
$result = $conn->query($sql);

// Check if query failed
if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
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
            <h1>Manage Users</h1>
        </div>
    </div>

    <div class="container my-4 content">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-danger text-center">👥 Registered Users</h2>

                <?php if ($result->num_rows > 0): ?>
                    <table class="table table-bordered table-striped mt-4">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Registered Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['role']) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="delete_user.php?id=<?= $row['id'] ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this user?');">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning text-center">
                        ⚠ No users found in the database.
                    </div>
                <?php endif; ?>

                <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">🔙 Back to Dashboard</a>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conn->close(); ?>
