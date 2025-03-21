<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Found Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
    <header class="bg-success text-white text-center py-3">
        <h1>Report Found Item</h1>
    </header>
    <div class="container my-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title">Found Item Form</h2>
                <form action="submit_found_item.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name:</label>
                        <input type="text" id="name" name="uname" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="item_name" class="form-label">Item Type:</label>
                        <input type="text" id="item_name" name="item_name" class="form-control" placeholder="e.g., Wallet, Keys, Bag" required>
                    </div>
                    <div class="mb-3">
                        <label for="brand" class="form-label">Brand Name (if any):</label>
                        <input type="text" id="brand" name="brand" class="form-control" placeholder="e.g., Nike, HP">
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date Found:</label>
                        <input type="date" id="date" name="date_found" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location Found:</label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="e.g., Library, Canteen" required>
                    </div>
                    <div class="mb-3">
                        <label for="phoneno" class="form-label">Your Contact Number:</label>
                        <input type="tel" id="phoneno" name="mobile" class="form-control" maxlength="10" pattern="[0-9]{10}" placeholder="+91 **********" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                    </div>
                    <div class="mb-3">
                        <label for="describeit" class="form-label">Describe the Item:</label>
                        <textarea id="describeit" name="description" class="form-control" rows="3" placeholder="Describe the item in detail" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Upload Photo :</label>
                        <input type="file" id="photo" name="image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Submit Found Item</button>
                    <a href="user_dashboard.php" class="btn btn-secondary w-100 mt-3">🔙 Back to Dashboard</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
