<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to custom styles -->
    <style>
        /* Custom styling for the navigation */
        .navbar {
            background-color: #ff5733;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar .navbar-brand {
            font-size: 1.8rem;
            color: white;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: white !important;
            padding: 12px 20px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            background-color: #d54f33;
            color: white;
            border-radius: 5px;
        }
        .navbar-toggler-icon {
            background-color: white;
        }
        /* Hover effect for items in the nav */
        .navbar-nav .nav-item {
            margin-right: 15px;
        }
        .navbar-nav .nav-item:last-child {
            margin-right: 0;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="text-center py-4 bg-light">
        <h1 style="color: #ff5733; font-size: 2.5rem; font-weight: bold; text-shadow: 2px 2px 5px rgba(0,0,0,0.2);">🚀 Admin Dashboard 🚀</h1>
        <p style="color: #ff5733; font-size: 1.2rem; font-weight: bold;">Manage Your Lost & Found System</p>
    </header>

    <!-- Enhanced Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="fetch.php">
                            <i class="bi bi-database"></i> Fetch Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user.php">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Dashboard Content -->
    <div class="container mt-5">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Your role is: <?php echo $_SESSION['role']; ?></p>
        <p>Use the navigation menu above to access different sections.</p>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <footer class="text-center bg-dark text-white py-3 mt-5">
        <p>&copy; 2025 Lost & Found Tracker | All Rights Reserved</p>
        <p>Developed by <a href="#" class="text-warning">NIE Boys</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
