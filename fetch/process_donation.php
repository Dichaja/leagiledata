<?php
session_start();
require_once __DIR__ . '/../xsert.php';
require_once __DIR__ . '/../bin/Mailer.php';
require_once __DIR__ . '/../bin/functions.php';

use ZzimbaOnline\Mail\Mailer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$amount = trim($_POST['amount'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
if (empty($name) || empty($email) || empty($amount)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid donation amount']);
    exit;
}

try {
    // Insert donation into database
    $donation_id = gen_uuid();
    $stmt = $conn->prepare("INSERT INTO donations (id, donor_name, donor_email, donor_phone, amount, message, created_at, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'pending')");
    $stmt->bind_param("ssssds", $donation_id, $name, $email, $phone, $amount, $message);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to save donation');
    }
    
    // Send confirmation email to donor
    $donorEmailContent = "
        <h2 style='color: #D92B13;'>Thank You for Your Donation!</h2>
        <p>Dear <strong>{$name}</strong>,</p>
        <p>We have received your generous donation of <strong>UGX " . number_format($amount) . "</strong>.</p>
        <p>Your support helps us continue our mission to provide quality legal and agricultural information and services.</p>
        <div style='background-color: #f3f4f6; padding: 15px; border-radius: 4px; margin: 20px 0;'>
            <p style='margin: 5px 0;'><strong>Donation ID:</strong> {$donation_id}</p>
            <p style='margin: 5px 0;'><strong>Amount:</strong> UGX " . number_format($amount) . "</p>
            <p style='margin: 5px 0;'><strong>Date:</strong> " . date('F j, Y') . "</p>
        </div>
        <p>You will receive a payment confirmation once the transaction is processed.</p>
        <p>If you have any questions, please don't hesitate to contact us.</p>
        <p>With gratitude,<br><strong>The Zzimba Online Team</strong></p>
    ";
    
    $donorEmailSent = Mailer::sendMail($email, 'Thank You for Your Donation', $donorEmailContent);
    
    // Send notification email to admin
    $adminEmail = 'info@zzimbaonline.com';
    $adminEmailContent = "
        <h2 style='color: #D92B13;'>New Donation Received</h2>
        <p>A new donation has been submitted on the platform.</p>
        <div style='background-color: #f3f4f6; padding: 15px; border-radius: 4px; margin: 20px 0;'>
            <p style='margin: 5px 0;'><strong>Donation ID:</strong> {$donation_id}</p>
            <p style='margin: 5px 0;'><strong>Donor Name:</strong> {$name}</p>
            <p style='margin: 5px 0;'><strong>Email:</strong> {$email}</p>
            <p style='margin: 5px 0;'><strong>Phone:</strong> {$phone}</p>
            <p style='margin: 5px 0;'><strong>Amount:</strong> UGX " . number_format($amount) . "</p>
            <p style='margin: 5px 0;'><strong>Date:</strong> " . date('F j, Y g:i A') . "</p>
        </div>
        " . (!empty($message) ? "<p><strong>Message:</strong><br>{$message}</p>" : "") . "
        <p>Please process this donation and update the status accordingly.</p>
    ";
    
    $adminEmailSent = Mailer::sendMail($adminEmail, 'New Donation Received', $adminEmailContent);
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your donation! A confirmation email has been sent.',
        'donation_id' => $donation_id,
        'emails_sent' => [
            'donor' => $donorEmailSent,
            'admin' => $adminEmailSent
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>
