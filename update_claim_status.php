<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $claim_id = isset($_POST['claim_id']) ? intval($_POST['claim_id']) : 0;

    if ($claim_id > 0) {
        $sql = "UPDATE claim_requests SET status = 'Resolved' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $claim_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Claim status updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update claim status.";
        }

        $stmt->close();
    }
}

header("Location: admin_view_claims.php");
exit();
?>
