<?php
require_once('../xsert.php');
header('Content-Type: application/json');

// Fetch reports
$sql = "SELECT r.id, r.title, r.author, r.description, r.price, r.thumbnail, r.category, r.download_url, r.file_size, r.file_type,
        (SELECT COUNT(*) FROM user_activities ua WHERE ua.activity_type = 'download' AND ua.activity_id LIKE CONCAT('%', r.id, '%')) AS download_count
        FROM reports r
        ORDER BY r.created_at DESC 
        LIMIT 10";
$result = $conn->prepare($sql);
$result->execute();
$reports = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $reports[] = $row;
}

echo json_encode($reports); 
?>
