<?php
session_start();
include 'db_connection.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['id']) || !isset($_GET['finder_email'])) {
    die("Invalid request.");
}

$item_id = $_GET['id'];
$finder_email = $_GET['finder_email']; // Finder's email passed from fetch_found_items.php

// Fetch item details
$sql = "SELECT item_name, description, location FROM found_items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Item not found.");
}

$item = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Found Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center text-danger">📩 Claim Found Item</h2>
        <div class="card p-4 shadow">
            <p><strong>Item Name:</strong> <?php echo htmlspecialchars($item['item_name']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location']); ?></p>

            <form action="send_claim_email.php" method="POST">
                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                <input type="hidden" name="finder_email" value="<?php echo htmlspecialchars($finder_email); ?>">

                <div class="mb-3">
                    <label for="claimer_email" class="form-label">Your Email:</label>
                    <input type="email" id="claimer_email" name="claimer_email" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label for="claimer_message" class="form-label">Message to Finder:</label>
                    <textarea id="claimer_message" name="claimer_message" class="form-control" rows="4" placeholder="Explain why this item belongs to you..." required></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100">Send Claim Request</button>
            </form>
        </div>
    </div>

</body>
</html>
