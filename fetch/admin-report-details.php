<?php
session_start();
require_once('../bin/functions.php');
require_once('../xsert.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to continue']);
    exit;
}

$report_id = $_GET['id'] ?? '';
if (empty($report_id)) {
    echo json_encode(['success' => false, 'message' => 'Report ID required']);
    exit;
}

try {
    // Get report details
    $report_stmt = $conn->prepare("SELECT * FROM reports WHERE id = ?");
    $report_stmt->execute([$report_id]);
    $report = $report_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$report) {
        throw new Exception('Report not found');
    }
    
    // Get download statistics
    $downloads_stmt = $conn->prepare("
        SELECT rd.*, u.usr_name, u.email as user_email
        FROM report_downloads rd
        JOIN users u ON rd.user_id = u.id
        WHERE rd.item_id = ?
        ORDER BY rd.created_at DESC
        LIMIT 10
    ");
    $downloads_stmt->execute([$report_id]);
    $downloads = $downloads_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $html = '
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-4">' . htmlspecialchars($report['title']) . '</h3>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold">Author:</span> ' . htmlspecialchars($report['author']) . '
                        </div>
                        <div>
                            <span class="font-semibold">Price:</span> $' . number_format($report['price'], 2) . '
                        </div>
                        <div>
                            <span class="font-semibold">Category:</span> ' . htmlspecialchars($report['category']) . '
                        </div>
                        <div>
                            <span class="font-semibold">Pages:</span> ' . $report['page_count'] . '
                        </div>
                        <div>
                            <span class="font-semibold">File Size:</span> ' . ($report['file_size'] ?: 'N/A') . '
                        </div>
                        <div>
                            <span class="font-semibold">File Type:</span> .' . ($report['file_type'] ?: 'pdf') . '
                        </div>
                        <div>
                            <span class="font-semibold">Status:</span> 
                            <span class="px-2 py-1 text-xs rounded-full status-' . ($report['status'] ?: 'pending') . '">' . ucfirst($report['status'] ?: 'Pending') . '</span>
                        </div>
                        <div>
                            <span class="font-semibold">Uploaded:</span> ' . date('M d, Y', strtotime($report['created_at'])) . '
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Description</h4>
                    <p class="text-gray-700 text-sm">' . nl2br(htmlspecialchars($report['description'])) . '</p>
                </div>
                
                <!-- File Info -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">File Information</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium">Download URL:</p>
                                <p class="text-sm text-gray-600">' . ($report['download_url'] ? htmlspecialchars($report['download_url']) : 'No file uploaded') . '</p>
                            </div>';
    
    if ($report['download_url']) {
        $html .= '
                            <a href="' . BASE_URL . '/' . htmlspecialchars($report['download_url']) . '" target="_blank" class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>';
    }
    
    $html .= '
                        </div>
                    </div>
                </div>
                
                <!-- Thumbnail -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Thumbnail</h4>';
    
    if ($report['thumbnail']) {
        $html .= '
                    <img src="' . htmlspecialchars($report['thumbnail']) . '" alt="Thumbnail" class="w-full max-w-xs rounded-lg border">';
    } else {
        $html .= '
                    <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                    </div>';
    }
    
    $html .= '
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Download History -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4">Recent Downloads</h4>';
    
    if (empty($downloads)) {
        $html .= '<p class="text-gray-600 text-sm">No downloads yet.</p>';
    } else {
        $html .= '<div class="space-y-3">';
        foreach ($downloads as $download) {
            $status_class = $download['download_status'] == 'approved' ? 'status-published' : 'status-pending';
            $html .= '
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h5 class="font-medium text-sm">' . htmlspecialchars($download['usr_name']) . '</h5>
                            <p class="text-xs text-gray-600">' . htmlspecialchars($download['user_email']) . '</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full ' . $status_class . '">' . ucfirst($download['download_status']) . '</span>
                    </div>
                    <p class="text-xs text-gray-500">' . date('M d, Y g:i A', strtotime($download['created_at'])) . '</p>
                </div>';
        }
        $html .= '</div>';
    }
    
    $html .= '
                </div>
                
                <!-- Statistics -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4">Statistics</h4>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">';
    
    // Get download counts
    $stats_stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_downloads,
            COUNT(CASE WHEN download_status = 'approved' THEN 1 END) as approved_downloads,
            COUNT(CASE WHEN download_status = 'pending' THEN 1 END) as pending_downloads
        FROM report_downloads 
        WHERE item_id = ?
    ");
    $stats_stmt->execute([$report_id]);
    $stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
    
    $html .= '
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Downloads:</span>
                            <span class="text-sm font-medium">' . $stats['total_downloads'] . '</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Approved Downloads:</span>
                            <span class="text-sm font-medium text-green-600">' . $stats['approved_downloads'] . '</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Pending Downloads:</span>
                            <span class="text-sm font-medium text-yellow-600">' . $stats['pending_downloads'] . '</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    
    echo json_encode(['success' => true, 'html' => $html]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>