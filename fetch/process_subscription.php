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
$plan = trim($_POST['plan'] ?? '');

// Validation
if (empty($name) || empty($email) || empty($plan)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Plan details
$plans = [
    'basic' => ['name' => 'Basic Plan', 'price' => 50000, 'duration' => '1 month'],
    'standard' => ['name' => 'Standard Plan', 'price' => 120000, 'duration' => '3 months'],
    'premium' => ['name' => 'Premium Plan', 'price' => 400000, 'duration' => '1 year']
];

if (!isset($plans[$plan])) {
    echo json_encode(['success' => false, 'message' => 'Invalid subscription plan']);
    exit;
}

$planDetails = $plans[$plan];

try {
    // Check if user already has an active subscription
    $checkStmt = $conn->prepare("SELECT id FROM subscriptions WHERE email = ? AND status = 'active' AND end_date > NOW()");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You already have an active subscription']);
        exit;
    }
    
    // Insert subscription into database
    $subscription_id = gen_uuid();
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime('+' . $planDetails['duration']));
    
    $stmt = $conn->prepare("INSERT INTO subscriptions (id, subscriber_name, email, phone, plan, amount, start_date, end_date, created_at, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending')");
    $stmt->bind_param("sssssdss", $subscription_id, $name, $email, $phone, $plan, $planDetails['price'], $start_date, $end_date);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to save subscription');
    }
    
    // Send confirmation email to subscriber
    $subscriberEmailContent = "
        <h2 style='color: #D92B13;'>Subscription Request Received</h2>
        <p>Dear <strong>{$name}</strong>,</p>
        <p>Thank you for subscribing to Zzimba Online!</p>
        <div style='background-color: #f3f4f6; padding: 15px; border-radius: 4px; margin: 20px 0;'>
            <p style='margin: 5px 0;'><strong>Subscription ID:</strong> {$subscription_id}</p>
            <p style='margin: 5px 0;'><strong>Plan:</strong> {$planDetails['name']}</p>
            <p style='margin: 5px 0;'><strong>Amount:</strong> UGX " . number_format($planDetails['price']) . "</p>
            <p style='margin: 5px 0;'><strong>Duration:</strong> {$planDetails['duration']}</p>
            <p style='margin: 5px 0;'><strong>Start Date:</strong> " . date('F j, Y', strtotime($start_date)) . "</p>
            <p style='margin: 5px 0;'><strong>End Date:</strong> " . date('F j, Y', strtotime($end_date)) . "</p>
        </div>
        <p>Your subscription is currently pending payment. You will receive payment instructions shortly.</p>
        <p>Once payment is confirmed, you will have full access to all premium features.</p>
        <p>If you have any questions, please contact us.</p>
        <p>Best regards,<br><strong>The Zzimba Online Team</strong></p>
    ";
    
    $subscriberEmailSent = Mailer::sendMail($email, 'Subscription Request Received', $subscriberEmailContent);
    
    // Send notification email to admin
    $adminEmail = 'info@zzimbaonline.com';
    $adminEmailContent = "
        <h2 style='color: #D92B13;'>New Subscription Request</h2>
        <p>A new subscription has been submitted on the platform.</p>
        <div style='background-color: #f3f4f6; padding: 15px; border-radius: 4px; margin: 20px 0;'>
            <p style='margin: 5px 0;'><strong>Subscription ID:</strong> {$subscription_id}</p>
            <p style='margin: 5px 0;'><strong>Subscriber Name:</strong> {$name}</p>
            <p style='margin: 5px 0;'><strong>Email:</strong> {$email}</p>
            <p style='margin: 5px 0;'><strong>Phone:</strong> {$phone}</p>
            <p style='margin: 5px 0;'><strong>Plan:</strong> {$planDetails['name']}</p>
            <p style='margin: 5px 0;'><strong>Amount:</strong> UGX " . number_format($planDetails['price']) . "</p>
            <p style='margin: 5px 0;'><strong>Duration:</strong> {$planDetails['duration']}</p>
            <p style='margin: 5px 0;'><strong>Date:</strong> " . date('F j, Y g:i A') . "</p>
        </div>
        <p>Please process this subscription and send payment instructions to the subscriber.</p>
    ";
    
    $adminEmailSent = Mailer::sendMail($adminEmail, 'New Subscription Request', $adminEmailContent);
    
    echo json_encode([
        'success' => true,
        'message' => 'Subscription request received! Check your email for details.',
        'subscription_id' => $subscription_id,
        'emails_sent' => [
            'subscriber' => $subscriberEmailSent,
            'admin' => $adminEmailSent
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>
