<?php
require_once('../xsert.php');
header('Content-Type: application/json');

// Get search query if provided
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build SQL query with search
$sql = "SELECT r.id, r.title, r.author, r.description, r.price, r.thumbnail, r.category, r.download_url, r.file_size, r.file_type,
        (SELECT COUNT(*) FROM user_activities ua WHERE ua.activity_type = 'download' AND ua.activity_id LIKE CONCAT('%', r.id, '%')) AS download_count
        FROM reports r WHERE r.status = 'published'";

// Add search condition if query exists
if (!empty($searchQuery)) {
    $sql .= " AND (r.title LIKE :search OR r.description LIKE :search OR r.author LIKE :search OR r.category LIKE :search)";
}

$sql .= " ORDER BY r.created_at DESC LIMIT 50";

$result = $conn->prepare($sql);

// Bind search parameter if exists
if (!empty($searchQuery)) {
    $searchParam = '%' . $searchQuery . '%';
    $result->bindParam(':search', $searchParam, PDO::PARAM_STR);
}

$result->execute();
$reports = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $reports[] = $row;
}

echo json_encode($reports); 
?>