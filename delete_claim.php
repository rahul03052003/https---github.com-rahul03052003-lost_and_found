<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $claim_id = isset($_POST['claim_id']) ? intval($_POST['claim_id']) : 0;

    if ($claim_id > 0) {
        $stmt = $conn->prepare("DELETE FROM claim_requests WHERE id = ?");
        $stmt->bind_param("i", $claim_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Claim deleted successfully!'); window.location.href = 'admin_view_claims.php';</script>";
    } else {
        echo "<script>alert('Invalid claim ID.'); window.location.href = 'admin_view_claims.php';</script>";
    }
}
?>
