<?php
session_start();
require_once('../bin/functions.php');
require_once('../xsert.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to continue']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

try {
    if ($action === 'contact') {
        // Handle contact message
        $expert_id = $_POST['expert_id'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';
        
        if (empty($expert_id) || empty($subject) || empty($message)) {
            throw new Exception('All fields are required');
        }
        
        // Create consultation request with contact type
        $request_id = gen_uuid();
        $stmt = $conn->prepare("
            INSERT INTO consultation_requests (id, user_id, expert_id, subject, message, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$request_id, $user_id, $expert_id, $subject, $message]);
        
        // Send notification email to expert
        $expert_stmt = $conn->prepare("SELECT email, first_name, last_name FROM experts WHERE id = ?");
        $expert_stmt->execute([$expert_id]);
        $expert = $expert_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($expert) {
            $user_stmt = $conn->prepare("SELECT usr_name, email FROM users WHERE id = ?");
            $user_stmt->execute([$user_id]);
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
            
            // Send email notification (you can implement this based on your email system)
            // sendExpertNotification($expert, $user, $subject, $message);
        }
        
        echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
        
    } elseif ($action === 'book') {
        // Handle consultation booking
        $expert_id = $_POST['expert_id'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';
        $preferred_date = $_POST['preferred_date'] ?? '';
        $preferred_time = $_POST['preferred_time'] ?? '';
        $duration_hours = (float)($_POST['duration_hours'] ?? 1);
        $budget = !empty($_POST['budget']) ? (float)$_POST['budget'] : null;
        
        if (empty($expert_id) || empty($subject) || empty($message) || empty($preferred_date) || empty($preferred_time)) {
            throw new Exception('All required fields must be filled');
        }
        
        // Validate date is in the future
        $preferred_datetime = $preferred_date . ' ' . $preferred_time;
        if (strtotime($preferred_datetime) <= time()) {
            throw new Exception('Preferred date and time must be in the future');
        }
        
        // Create consultation request
        $request_id = gen_uuid();
        $stmt = $conn->prepare("
            INSERT INTO consultation_requests (id, user_id, expert_id, subject, message, preferred_date, preferred_time, duration_hours, budget, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$request_id, $user_id, $expert_id, $subject, $message, $preferred_date, $preferred_time, $duration_hours, $budget]);
        
        // Send notification email to expert
        $expert_stmt = $conn->prepare("SELECT email, first_name, last_name FROM experts WHERE id = ?");
        $expert_stmt->execute([$expert_id]);
        $expert = $expert_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($expert) {
            $user_stmt = $conn->prepare("SELECT usr_name, email FROM users WHERE id = ?");
            $user_stmt->execute([$user_id]);
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
            
            // Send email notification (you can implement this based on your email system)
            // sendConsultationRequest($expert, $user, $subject, $message, $preferred_datetime, $duration_hours, $budget);
        }
        
        echo json_encode(['success' => true, 'message' => 'Consultation request sent successfully']);
        
    } else {
        throw new Exception('Invalid action');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>