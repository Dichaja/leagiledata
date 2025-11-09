<?php
require_once(__DIR__ . '/../xsert.php');
header('Content-Type: application/json');

// Get search parameters
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Build SQL query
$sql = "SELECT r.id, r.title, r.author, r.description, r.price, r.thumbnail, r.category, r.download_url, r.file_size, r.file_type,
        (SELECT COUNT(*) FROM user_activities ua WHERE ua.activity_type = 'download' AND ua.activity_id LIKE CONCAT('%', r.id, '%')) AS download_count
        FROM reports r WHERE r.status = 'published'";

$params = [];
$types = '';

// Add search condition
if (!empty($searchQuery)) {
    $sql .= " AND (r.title LIKE ? OR r.description LIKE ? OR r.author LIKE ? OR r.category LIKE ?)";
    $searchParam = '%' . $searchQuery . '%';
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
    $types .= 'ssss';
}

// Add category filter
if (!empty($category)) {
    $sql .= " AND r.category = ?";
    $params[] = $category;
    $types .= 's';
}

$sql .= " ORDER BY r.created_at DESC LIMIT 50";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$reports = [];

while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

echo json_encode($reports);
?>
