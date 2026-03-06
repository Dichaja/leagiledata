<?php
// fetch/edit_report.php
require_once __DIR__ . '/../bin/functions.php';
require_once __DIR__ . '/../xsert.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$id = $_POST['id'] ?? '';
$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';
$price = $_POST['price'] ?? 0;
$page_count = $_POST['page_count'] ?? 0;
$status = $_POST['status'] ?? 'pending';
$updated_at = date('Y-m-d H:i:s');

// Handle thumbnail upload
$thumbnailPath = '';
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';
    $thumbTmp = $_FILES['thumbnail']['tmp_name'];
    $thumbName = basename($_FILES['thumbnail']['name']);
    $thumbExt = strtolower(pathinfo($thumbName, PATHINFO_EXTENSION));
    $allowedImageTypes = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($thumbExt, $allowedImageTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid thumbnail type. Only JPG, PNG, GIF, WEBP allowed.']);
        exit();
    }
    $thumbnailPath = $uploadDir . uniqid() . '_thumb_' . $thumbName;
    if (!move_uploaded_file($thumbTmp, $thumbnailPath)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload thumbnail image.']);
        exit();
    }
}

// Build SQL
$sql = "UPDATE reports SET title=?, author=?, description=?, category=?, price=?, page_count=?, status=?, updated_at=?";
$params = [$title, $author, $description, $category, $price, $page_count, $status, $updated_at];
if ($thumbnailPath) {
    $sql .= ", thumbnail=?";
    $params[] = $thumbnailPath;
}
$sql .= " WHERE id=?";
$params[] = $id;

$stmt = $conn->prepare($sql);
$result = $stmt->execute($params);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Report updated successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update report.']);
}
