<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure Composer autoload is included
include 'db_connection.php';   // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $finder_email = isset($_POST['finder_email']) ? filter_var($_POST['finder_email'], FILTER_SANITIZE_EMAIL) : '';
    $claimer_email = isset($_POST['claimer_email']) ? filter_var($_POST['claimer_email'], FILTER_SANITIZE_EMAIL) : '';
    $claimer_message = isset($_POST['claimer_message']) ? htmlspecialchars($_POST['claimer_message']) : '';

    // Validate email addresses
    if (!filter_var($finder_email, FILTER_VALIDATE_EMAIL) || !filter_var($claimer_email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email address.");
    }

    if (empty($claimer_message)) {
        die("Error: Message cannot be empty.");
    }

    // Insert claim request into database
    $stmt = $conn->prepare("INSERT INTO claim_requests (item_id, claimer_email, claimer_message, finder_email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $item_id, $claimer_email, $claimer_message, $finder_email);
    
    if (!$stmt->execute()) {
        die("Database error: " . $stmt->error);
    }
    
    $stmt->close();

    // Send email notification to the finder
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'travel19122003@gmail.com';
        $mail->Password = 'mdvi jcrj opmr gtpi'; // Use Gmail App Password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom($claimer_email, 'Lost & Found User');
        $mail->addAddress($finder_email);

        $mail->isHTML(true);
        $mail->Subject = 'Claim Request for Your Found Item';
        $mail->Body = "<h3>Someone is claiming an item you found!</h3>
            <p><strong>Claimer Email:</strong> $claimer_email</p>
            <p><strong>Message:</strong></p>
            <p>$claimer_message</p>
            <br>
            <p>Kindly verify the claim and respond accordingly.</p>";

        if ($mail->send()) {
            echo "<script>alert('Claim request sent successfully!'); window.location.href = 'fetch_found_items.php';</script>";
        } else {
            echo "<script>alert('Email failed to send. Please try again.');</script>";
        }

    } catch (Exception $e) {
        echo "Failed to send email. Error: " . $mail->ErrorInfo;
    }
}
?>

