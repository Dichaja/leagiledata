<?php
require_once('../xsert.php');
header('Content-Type: application/json');

// Get report ID from URL
$reportId = $_GET['id'] ?? null;

if (!$reportId) {
    http_response_code(400);
    echo json_encode(['error' => 'Report ID is required']);
    exit;
}

try {
    // Fetch single report with download count
    $sql = "SELECT r.id, r.title, r.author, r.description, r.price, r.thumbnail, r.category, r.download_url, r.created_at, r.file_size, r.file_type,
            (SELECT COUNT(*) FROM user_activities ua WHERE ua.activity_type = 'download' AND ua.activity_id LIKE CONCAT('%', r.id, '%')) AS download_count
            FROM reports r
            WHERE r.id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $reportId);
    $stmt->execute();
    
    $report = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$report) {
        http_response_code(404);
        echo json_encode(['error' => 'Report not found']);
        exit;
    }
    
    // Add additional dataset-specific fields if needed
    $report['formats'] = ['CSV', 'JSON']; // Example formats
    $report['size'] = '5MB'; // Example size
    $report['recordCount'] = 1250; // Example record count
    
    echo json_encode($report);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>