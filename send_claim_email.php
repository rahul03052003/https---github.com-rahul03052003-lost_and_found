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

    if (empty($finder_email) || empty($claimer_email) || empty($claimer_message)) {
        die("Error: All fields are required.");
    }

    // Insert claim request into database
    $stmt = $conn->prepare("INSERT INTO claim_requests (item_id, claimer_email, claimer_message, finder_email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $item_id, $claimer_email, $claimer_message, $finder_email);
    $stmt->execute();
    $stmt->close();

    // Send email notification to the finder
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'travel19122003@gmail.com';
        $mail->Password = 'mdvi jcrj opmr gtpi';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

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

        $mail->send();
        echo "<script>alert('Claim request sent successfully!'); window.location.href = 'fetch_found_items.php';</script>";
    } catch (Exception $e) {
        echo "Failed to send email. Error: {$mail->ErrorInfo}";
    }
}
?>
