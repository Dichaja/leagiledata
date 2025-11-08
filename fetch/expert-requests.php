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

// Check if user is an approved expert
$expert_stmt = $conn->prepare("SELECT id FROM experts WHERE user_id = ? AND status = 'approved'");
$expert_stmt->execute([$user_id]);
$expert = $expert_stmt->fetch(PDO::FETCH_ASSOC);

if (!$expert) {
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

$expert_id = $expert['id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'view') {
        // View request details
        $request_id = $_GET['id'] ?? '';
        
        $stmt = $conn->prepare("
            SELECT cr.*, u.usr_name, u.email as user_email
            FROM consultation_requests cr
            JOIN users u ON cr.user_id = u.id
            WHERE cr.id = ? AND cr.expert_id = ?
        ");
        $stmt->execute([$request_id, $expert_id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$request) {
            throw new Exception('Request not found');
        }
        
        $html = '
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-800">Subject</h4>
                    <p class="text-gray-700">' . htmlspecialchars($request['subject']) . '</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-800">From</h4>
                    <p class="text-gray-700">' . htmlspecialchars($request['usr_name']) . ' (' . htmlspecialchars($request['user_email']) . ')</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-800">Message</h4>
                    <p class="text-gray-700">' . nl2br(htmlspecialchars($request['message'])) . '</p>
                </div>';
        
        if ($request['preferred_date']) {
            $html .= '
                <div>
                    <h4 class="font-semibold text-gray-800">Preferred Date & Time</h4>
                    <p class="text-gray-700">' . date('M d, Y', strtotime($request['preferred_date']));
            
            if ($request['preferred_time']) {
                $html .= ' at ' . date('g:i A', strtotime($request['preferred_time']));
            }
            
            if ($request['duration_hours']) {
                $html .= ' (' . $request['duration_hours'] . ' hours)';
            }
            
            $html .= '</p></div>';
        }
        
        if ($request['budget']) {
            $html .= '
                <div>
                    <h4 class="font-semibold text-gray-800">Budget</h4>
                    <p class="text-gray-700">$' . number_format($request['budget'], 2) . '</p>
                </div>';
        }
        
        $html .= '
                <div>
                    <h4 class="font-semibold text-gray-800">Status</h4>
                    <span class="px-2 py-1 text-sm rounded-full status-' . $request['status'] . '">' . ucfirst($request['status']) . '</span>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-800">Requested On</h4>
                    <p class="text-gray-700">' . date('M d, Y g:i A', strtotime($request['created_at'])) . '</p>
                </div>';
        
        if ($request['expert_response']) {
            $html .= '
                <div>
                    <h4 class="font-semibold text-gray-800">Your Response</h4>
                    <p class="text-gray-700">' . nl2br(htmlspecialchars($request['expert_response'])) . '</p>
                </div>';
        }
        
        if ($request['scheduled_date']) {
            $html .= '
                <div>
                    <h4 class="font-semibold text-gray-800">Scheduled Date & Time</h4>
                    <p class="text-gray-700">' . date('M d, Y g:i A', strtotime($request['scheduled_date'])) . '</p>
                </div>';
        }
        
        if ($request['meeting_link']) {
            $html .= '
                <div>
                    <h4 class="font-semibold text-gray-800">Meeting Link</h4>
                    <p class="text-gray-700"><a href="' . htmlspecialchars($request['meeting_link']) . '" target="_blank" class="text-blue-600 hover:underline">' . htmlspecialchars($request['meeting_link']) . '</a></p>
                </div>';
        }
        
        $html .= '</div>';
        
        echo json_encode(['success' => true, 'html' => $html]);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle expert response
        $request_id = $_POST['request_id'] ?? '';
        $action = $_POST['action'] ?? '';
        $expert_response = $_POST['expert_response'] ?? '';
        $scheduled_date = $_POST['scheduled_date'] ?? null;
        $meeting_link = $_POST['meeting_link'] ?? null;
        
        if (empty($request_id) || empty($action)) {
            throw new Exception('Missing required fields');
        }
        
        // Verify request belongs to this expert
        $verify_stmt = $conn->prepare("SELECT id FROM consultation_requests WHERE id = ? AND expert_id = ?");
        $verify_stmt->execute([$request_id, $expert_id]);
        if (!$verify_stmt->fetch()) {
            throw new Exception('Request not found');
        }
        
        // Update request
        $update_fields = ['status = ?', 'expert_response = ?', 'updated_at = CURRENT_TIMESTAMP'];
        $update_values = [$action, $expert_response];
        
        if ($scheduled_date) {
            $update_fields[] = 'scheduled_date = ?';
            $update_values[] = $scheduled_date;
        }
        
        if ($meeting_link) {
            $update_fields[] = 'meeting_link = ?';
            $update_values[] = $meeting_link;
        }
        
        $update_values[] = $request_id;
        
        $update_stmt = $conn->prepare("
            UPDATE consultation_requests 
            SET " . implode(', ', $update_fields) . "
            WHERE id = ?
        ");
        $update_stmt->execute($update_values);
        
        // If completed, update expert stats
        if ($action === 'completed') {
            $conn->prepare("
                UPDATE experts 
                SET total_consultations = total_consultations + 1 
                WHERE id = ?
            ")->execute([$expert_id]);
        }
        
        // Send notification email to user (implement based on your email system)
        // sendConsultationResponse($request_id, $action, $expert_response);
        
        echo json_encode(['success' => true, 'message' => 'Response sent successfully']);
        
    } else {
        throw new Exception('Invalid request method');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>