<?php
session_start();
require_once('../bin/functions.php');
require_once('../xsert.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to continue']);
    exit;
}

$expert_id = $_GET['id'] ?? '';
if (empty($expert_id)) {
    echo json_encode(['success' => false, 'message' => 'Expert ID required']);
    exit;
}

try {
    // Get expert details with specialties and categories
    $expert_stmt = $conn->prepare("
        SELECT e.*, 
               GROUP_CONCAT(DISTINCT es.specialty) as specialties,
               GROUP_CONCAT(DISTINCT ec.name) as categories
        FROM experts e
        LEFT JOIN expert_specialties es ON e.id = es.expert_id
        LEFT JOIN expert_category_assignments eca ON e.id = eca.expert_id
        LEFT JOIN expert_categories ec ON eca.category_id = ec.id
        WHERE e.id = ?
        GROUP BY e.id
    ");
    $expert_stmt->execute([$expert_id]);
    $expert = $expert_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$expert) {
        throw new Exception('Expert not found');
    }
    
    // Get consultation requests
    $requests_stmt = $conn->prepare("
        SELECT cr.*, u.usr_name, u.email as user_email
        FROM consultation_requests cr
        JOIN users u ON cr.user_id = u.id
        WHERE cr.expert_id = ?
        ORDER BY cr.created_at DESC
        LIMIT 10
    ");
    $requests_stmt->execute([$expert_id]);
    $requests = $requests_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get reviews
    $reviews_stmt = $conn->prepare("
        SELECT er.*, u.usr_name
        FROM expert_reviews er
        JOIN users u ON er.user_id = u.id
        WHERE er.expert_id = ?
        ORDER BY er.created_at DESC
        LIMIT 10
    ");
    $reviews_stmt->execute([$expert_id]);
    $reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $specialties = $expert['specialties'] ? explode(',', $expert['specialties']) : [];
    $categories = $expert['categories'] ? explode(',', $expert['categories']) : [];
    
    $html = '
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <img src="' . ($expert['profile_image'] ? htmlspecialchars($expert['profile_image']) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $expert['id']) . '" 
                             alt="Profile" class="w-16 h-16 rounded-full mr-4">
                        <div>
                            <h3 class="text-xl font-bold">' . htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']) . '</h3>
                            <p class="text-gray-600">' . htmlspecialchars($expert['email']) . '</p>
                            ' . ($expert['phone'] ? '<p class="text-gray-600">' . htmlspecialchars($expert['phone']) . '</p>' : '') . '
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold">Experience:</span> ' . $expert['experience_years'] . ' years
                        </div>
                        <div>
                            <span class="font-semibold">Rate:</span> $' . number_format($expert['hourly_rate'], 2) . '/hour
                        </div>
                        <div>
                            <span class="font-semibold">Rating:</span> ' . number_format($expert['rating'], 1) . '/5.0
                        </div>
                        <div>
                            <span class="font-semibold">Reviews:</span> ' . $expert['total_reviews'] . '
                        </div>
                        <div>
                            <span class="font-semibold">Consultations:</span> ' . $expert['total_consultations'] . '
                        </div>
                        <div>
                            <span class="font-semibold">Status:</span> 
                            <span class="px-2 py-1 text-xs rounded-full status-' . $expert['status'] . '">' . ucfirst($expert['status']) . '</span>
                        </div>
                    </div>
                </div>
                
                <!-- Bio -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Professional Bio</h4>
                    <p class="text-gray-700 text-sm">' . nl2br(htmlspecialchars($expert['bio'])) . '</p>
                </div>
                
                <!-- Education -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Education & Qualifications</h4>
                    <p class="text-gray-700 text-sm">' . nl2br(htmlspecialchars($expert['education'])) . '</p>
                </div>
                
                <!-- Categories -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Categories</h4>
                    <div class="flex flex-wrap gap-2">';
    
    foreach ($categories as $category) {
        $html .= '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">' . htmlspecialchars(trim($category)) . '</span>';
    }
    
    $html .= '
                    </div>
                </div>
                
                <!-- Specialties -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Specialties</h4>
                    <div class="flex flex-wrap gap-2">';
    
    foreach ($specialties as $specialty) {
        $html .= '<span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">' . htmlspecialchars(trim($specialty)) . '</span>';
    }
    
    $html .= '
                    </div>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Recent Requests -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4">Recent Consultation Requests</h4>';
    
    if (empty($requests)) {
        $html .= '<p class="text-gray-600 text-sm">No consultation requests yet.</p>';
    } else {
        $html .= '<div class="space-y-3">';
        foreach ($requests as $request) {
            $html .= '
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h5 class="font-medium text-sm">' . htmlspecialchars($request['subject']) . '</h5>
                            <p class="text-xs text-gray-600">From: ' . htmlspecialchars($request['usr_name']) . '</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full status-' . $request['status'] . '">' . ucfirst($request['status']) . '</span>
                    </div>
                    <p class="text-xs text-gray-700">' . htmlspecialchars(substr($request['message'], 0, 100)) . (strlen($request['message']) > 100 ? '...' : '') . '</p>
                    <p class="text-xs text-gray-500 mt-1">' . date('M d, Y', strtotime($request['created_at'])) . '</p>
                </div>';
        }
        $html .= '</div>';
    }
    
    $html .= '
                </div>
                
                <!-- Recent Reviews -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4">Recent Reviews</h4>';
    
    if (empty($reviews)) {
        $html .= '<p class="text-gray-600 text-sm">No reviews yet.</p>';
    } else {
        $html .= '<div class="space-y-3">';
        foreach ($reviews as $review) {
            $html .= '
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center">
                            <span class="font-medium text-sm">' . htmlspecialchars($review['usr_name']) . '</span>
                            <div class="flex ml-2">';
            
            for ($i = 1; $i <= 5; $i++) {
                $html .= '<i class="fas fa-star text-xs ' . ($i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300') . '"></i>';
            }
            
            $html .= '
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">' . date('M d, Y', strtotime($review['created_at'])) . '</span>
                    </div>';
            
            if ($review['review_text']) {
                $html .= '<p class="text-xs text-gray-700">' . htmlspecialchars($review['review_text']) . '</p>';
            }
            
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    
    $html .= '
                </div>
            </div>
        </div>';
    
    echo json_encode(['success' => true, 'html' => $html]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>