<?php

session_start();
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../xsert.php';

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? 0;
    $thumbnail = $_POST['thumbnail'] ?? '';
    $page_count = $_POST['page_count'] ?? 0;
    $status = $_POST['status'] ?? '';
    $publish = date('Y-m-d H:i:s');
    $id = gen_uuid();

    // Handle file upload
    $uploadDir = 'uploads/';
    $filePath = '';
    $allowedTypes = ['.pdf','.docx','.xlst']; // Allowed file types
    $maxFileSize = 10 * 1024 * 1024; // 10MB max file size

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileName = basename($_FILES['file']['name']);
    
    // Get file extension properly
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $fileType = '.' . $fileExt; // Returns .pdf, .docx, etc
    
    // Format file size
    $fileSizeBytes = $_FILES['file']['size'];
    $fileSizeFormatted = formatFileSize($fileSizeBytes); // Custom function
    
    // Validate file type
    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['response'] = "Error: Only PDF files are allowed.";
        header("Location: ../add_file.php");
        exit();
    }

    // Validate file size (10MB in bytes)
    $maxFileSize = 10 * 1024 * 1024; 
    if ($fileSizeBytes > $maxFileSize) {
        $_SESSION['response'] = "Error: File size exceeds the maximum limit of 10MB.";
        header("Location: ../add_file.php");
        exit();
    }

    $filePath = $uploadDir . uniqid() . "_" . $fileName;

    if (!move_uploaded_file($fileTmp, '../' . $filePath)) {
        $_SESSION['response'] = "Error uploading the PDF file.";
        header("Location: ../add_file.php");
        exit();
    }

// Insert into database
$stmt = $conn->prepare("INSERT INTO reports (id, title, author, description, price, thumbnail, category, status, download_url, page_count, publish_date, updated_at, file_size, file_type)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$result = $stmt->execute([
    $id, $title, $author, $description, $price, $thumbnail, $category, $status,
    $filePath, $page_count, $publish, $publish, $fileSizeFormatted ?? '0 KB', $fileExt ?? ''
]);

 if ($result) {
        $_SESSION['response'] = "Report submitted successfully.";
    } else {
        $_SESSION['response'] = "Failed to submit report.";
    }
    header("Location: ../add_file.php");
    exit();

} else {
    $_SESSION['response'] = "Invalid request.";
    header("Location: ../add_file.php");
    exit();
 }
}
?>
