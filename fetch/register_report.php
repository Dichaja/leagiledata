<?php
// fetch/register_report.php
// Endpoint to register a research report using the reports table structure

require_once __DIR__ . '/../bin/functions.php';
require_once __DIR__ . '/../xsert.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Collect POST data
$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';
$price = $_POST['price'] ?? 0;
$page_count = $_POST['page_count'] ?? 0;
$status = $_POST['status'] ?? 'published';
$publish = date('Y-m-d H:i:s');
$id = gen_uuid();

// Handle file upload
$uploadDir = '../uploads/';
$filePath = '';
$allowedTypes = ['pdf','docx'];
$maxFileSize = 10 * 1024 * 1024; // 10MB
$fileType = '';
$fileSizeFormatted = '';

// Handle thumbnail upload
$thumbnailPath = '';
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $thumbTmp = $_FILES['thumbnail']['tmp_name'];
    $thumbName = basename($_FILES['thumbnail']['name']);
    $thumbExt = strtolower(pathinfo($thumbName, PATHINFO_EXTENSION));
    $allowedImageTypes = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($thumbExt, $allowedImageTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid thumbnail type. Only JPG, PNG, GIF, WEBP allowed.']);
        exit();
    }
    $thumbnailPath =  uniqid() . '_thumb_' . $thumbName;
    if (!move_uploaded_file($thumbTmp, $thumbnailPath)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload thumbnail image.']);
        exit();
    }
}

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileName = basename($_FILES['file']['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $fileType = $fileExt;
    $fileSizeBytes = $_FILES['file']['size'];
    $fileSizeFormatted = formatFileSize($fileSizeBytes);

    if (!in_array($fileExt, $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only PDF and DOCX allowed.']);
        exit();
    }
    if ($fileSizeBytes > $maxFileSize) {
        http_response_code(400);
        echo json_encode(['error' => 'File size exceeds 10MB limit.']);
        exit();
    }
    $filePath = $uploadDir . uniqid() . '_' . $fileName;
    if (!move_uploaded_file($fileTmp, $filePath)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload file.']);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded.']);
    exit();
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO reports (id, title, author, description, price, thumbnail, category, status, download_url, page_count, publish_date, created_at, updated_at, file_size, file_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$result = $stmt->execute([
    $id, $title, $author, $description, $price, $thumbnailPath, $category, $status,
    $filePath, $page_count, $publish, $publish, $publish, $fileSizeFormatted, $fileType
]);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Report registered successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to register report.']);
}
