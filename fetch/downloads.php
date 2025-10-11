<?php
require_once __DIR__ . '/../xsert.php';
require_once __DIR__ . '/../bin/functions.php';
header('Content-Type: application/json');

$charset = 'utf8mb4';


$input = json_decode(file_get_contents('php://input'), true);
$id = gen_uuid(); // sets unique ID


if (!isset($input['user_id'], $input['item_id'], $input['action'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

try {

    // Handle different actions
    if ($input['action'] === 'add') {
        // Check if item already exists for this user
        $stmt = $conn->prepare("SELECT id FROM report_downloads WHERE user_id = :user_id AND item_id = :item_id");
        $stmt->execute([':user_id' => $input['user_id'], ':item_id' => $input['item_id']]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE report_downloads  SET download_status = 'pending', download_at = NOW(), last_accessed_at = NOW() WHERE id = :id");
            $stmt->execute([':id' => $existing['id']]);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO report_downloads (id, user_id, item_id, item_price, download_status, 
                                   download_at, created_at) VALUES (:id, :user_id, :item_id, :item_price, 'pending', 
                                   NOW(), NOW())");
            
            $stmt->execute([
                ':id' => $id,
                ':user_id' => $input['user_id'],
                ':item_id' => $input['item_id'],
                ':item_price' => $input['item_price'] ?? null
                //':paid_by' => $input['paid_by'] ?? 'user'
            ]);
        }
    } elseif ($input['action'] === 'remove') {
        // Mark item as removed from cart
        $stmt = $conn->prepare("UPDATE report_downloads SET download_status = 'pending', cart_added_at = NULL, last_accessed_at = NOW() WHERE user_id = :user_id AND item_id = :item_id");
        $stmt->execute([
            ':user_id' => $input['user_id'],
            ':item_id' => $input['item_id']
        ]);
    }

    echo json_encode(["success" => true, "message" => "Sync completed"]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}