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

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($_POST['expert_id'])) {
            // Handle review submission
            $expert_id = $_POST['expert_id'];
            $consultation_id = $_POST['consultation_id'];
            $rating = (int)$_POST['rating'];
            $review_text = $_POST['review_text'] ?? '';
            
            if ($rating < 1 || $rating > 5) {
                throw new Exception('Invalid rating');
            }
            
            // Check if user has already reviewed this consultation
            $existing_review = $conn->prepare("SELECT id FROM expert_reviews WHERE user_id = ? AND consultation_id = ?");
            $existing_review->execute([$user_id, $consultation_id]);
            
            if ($existing_review->fetch()) {
                throw new Exception('You have already reviewed this consultation');
            }
            
            // Insert review
            $review_id = gen_uuid();
            $review_stmt = $conn->prepare("
                INSERT INTO expert_reviews (id, expert_id, user_id, consultation_id, rating, review_text)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $review_stmt->execute([$review_id, $expert_id, $user_id, $consultation_id, $rating, $review_text]);
            
            // Update expert rating
            $rating_stmt = $conn->prepare("
                UPDATE experts 
                SET rating = (
                    SELECT AVG(rating) FROM expert_reviews WHERE expert_id = ?
                ),
                total_reviews = (
                    SELECT COUNT(*) FROM expert_reviews WHERE expert_id = ?
                )
                WHERE id = ?
            ");
            $rating_stmt->execute([$expert_id, $expert_id, $expert_id]);
            
            echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
            
        } elseif (isset($input['action'])) {
            // Handle consultation actions
            $action = $input['action'];
            $request_id = $input['request_id'];
            
            if ($action === 'cancel') {
                // Verify request belongs to user
                $verify_stmt = $conn->prepare("SELECT id FROM consultation_requests WHERE id = ? AND user_id = ?");
                $verify_stmt->execute([$request_id, $user_id]);
                
                if (!$verify_stmt->fetch()) {
                    throw new Exception('Request not found');
                }
                
                // Update request status
                $update_stmt = $conn->prepare("
                    UPDATE consultation_requests 
                    SET status = 'cancelled', updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                $update_stmt->execute([$request_id]);
                
                echo json_encode(['success' => true, 'message' => 'Request cancelled successfully']);
            } else {
                throw new Exception('Invalid action');
            }
        } else {
            throw new Exception('Invalid request');
        }
    } else {
        throw new Exception('Invalid request method');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>