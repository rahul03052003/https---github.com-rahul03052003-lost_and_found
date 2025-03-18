<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Lost Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <h1>Lost Items List</h1>
</header>

<div class="container my-4">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title">All Lost Items</h2>

            <!-- Search Form -->
            <form method="GET" class="d-flex mb-3">
                <input type="text" name="search" class="form-control me-2" placeholder="🔍 Search by Item Name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="user_fetch.php" class="btn btn-secondary ms-2">Reset</a>
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Registration Number</th>
                        <th>Item Name</th>
                        <th>Brand</th>
                        <th>Contact Number</th>
                        <th>Last Seen At</th>
                        <th>Description</th>
                        <th>Photo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "lost_and_found";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Search functionality
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $sql = "SELECT * FROM lost_items WHERE item_name LIKE ?";
                    $stmt = $conn->prepare($sql);
                    $search_param = "%$search%";
                    $stmt->bind_param("s", $search_param);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $photo_path = htmlspecialchars($row['photo_path']);
                            $photo_url = "http://localhost/project/images/" . basename($photo_path);

                            echo "<tr>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['reg_no']) . "</td>
                                    <td>" . htmlspecialchars($row['item_name']) . "</td>
                                    <td>" . htmlspecialchars($row['brand']) . "</td>
                                    <td>" . htmlspecialchars($row['phno']) . "</td>
                                    <td>" . htmlspecialchars($row['lastseen']) . "</td>
                                    <td>" . htmlspecialchars($row['describeit']) . "</td>
                                    <td><img src='" . $photo_url . "' alt='Item Photo' width='100' onerror='this.onerror=null;this.src=\"default.jpg\";'></td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
                    }

                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
            <a href="user_dashboard.php" class="btn btn-secondary w-100 mt-3">🔙 Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
