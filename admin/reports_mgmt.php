<?php
session_start();
require_once('../bin/page_settings.php');
require_once('../bin/functions.php');
require_once('../xsert.php');

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Get all reports with upload statistics
$reports_stmt = $conn->prepare("
    SELECT r.*, 
           COUNT(DISTINCT rd.id) as total_downloads,
           COUNT(DISTINCT CASE WHEN rd.download_status = 'approved' THEN rd.id END) as approved_downloads,
           COUNT(DISTINCT CASE WHEN rd.download_status = 'pending' THEN rd.id END) as pending_downloads
    FROM reports r
    LEFT JOIN report_downloads rd ON r.id = rd.item_id
    GROUP BY r.id
    ORDER BY r.created_at DESC
");
$reports_stmt->execute();
$reports = $reports_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $report_id = $_POST['report_id'] ?? '';
    $action = $_POST['action'] ?? '';
    
    if ($action === 'approve') {
        $conn->prepare("UPDATE reports SET status = 'published' WHERE id = ?")->execute([$report_id]);
        $success_message = "Report approved and published successfully!";
    } elseif ($action === 'reject') {
        $conn->prepare("UPDATE reports SET status = 'rejected' WHERE id = ?")->execute([$report_id]);
        $success_message = "Report rejected successfully!";
    } elseif ($action === 'delete') {
        // Delete associated downloads first
        $conn->prepare("DELETE FROM report_downloads WHERE item_id = ?")->execute([$report_id]);
        // Delete the report
        $conn->prepare("DELETE FROM reports WHERE id = ?")->execute([$report_id]);
        $success_message = "Report deleted successfully!";
    }
    
    // Refresh the page to show updated data
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Get statistics
$total_reports = count($reports);
$published_reports = count(array_filter($reports, fn($r) => $r['status'] === 'published'));
$pending_reports = count(array_filter($reports, fn($r) => $r['status'] === 'pending' || empty($r['status'])));
$rejected_reports = count(array_filter($reports, fn($r) => $r['status'] === 'rejected'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Management - Admin</title>
    <?php include('../bin/source_links.php'); ?>
    <style>
        .status-published { background-color: #d1fae5; color: #065f46; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        .status- { background-color: #f3f4f6; color: #374151; }
    </style>
</head>
<body>
    <?php siteHeader() ?>
    
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Reports Management</h1>
                <p class="text-gray-600">Manage uploaded reports and file submissions</p>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-file-alt text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Reports</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $total_reports; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Published</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $published_reports; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $pending_reports; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-times text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rejected</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $rejected_reports; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">All Reports</h2>
                        <div class="flex space-x-2">
                            <button onclick="filterReports('all')" class="px-3 py-1 text-sm bg-gray-100 rounded-md hover:bg-gray-200">All</button>
                            <button onclick="filterReports('pending')" class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">Pending</button>
                            <button onclick="filterReports('published')" class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-md hover:bg-green-200">Published</button>
                            <button onclick="filterReports('rejected')" class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-md hover:bg-red-200">Rejected</button>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Downloads</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($reports as $report): ?>
                                <tr class="report-row" data-status="<?php echo $report['status'] ?: 'pending'; ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <?php if ($report['thumbnail']): ?>
                                                    <img class="h-10 w-10 rounded object-cover" src="<?php echo htmlspecialchars($report['thumbnail']); ?>" alt="">
                                                <?php else: ?>
                                                    <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-file-alt text-gray-400"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($report['title']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    $<?php echo number_format($report['price'], 2); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($report['author']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <?php echo htmlspecialchars($report['category']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div><?php echo $report['file_size'] ?: 'N/A'; ?></div>
                                        <div><?php echo $report['page_count']; ?> pages</div>
                                        <div class="text-xs">.<?php echo $report['file_type'] ?: 'pdf'; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div><?php echo $report['total_downloads']; ?> total</div>
                                        <div class="text-green-600"><?php echo $report['approved_downloads']; ?> approved</div>
                                        <div class="text-yellow-600"><?php echo $report['pending_downloads']; ?> pending</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-<?php echo $report['status'] ?: 'pending'; ?>">
                                            <?php echo ucfirst($report['status'] ?: 'Pending'); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($report['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="viewReport('<?php echo $report['id']; ?>')" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($report['download_url']): ?>
                                                <a href="<?php echo BASE_URL . '/' . $report['download_url']; ?>" target="_blank" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php //if (($report['status'] ?: 'pending') === 'pending'): ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Approve this report?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Reject this report?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            <?php //endif; ?>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this report? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Report Details Modal -->
    <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Report Details</h3>
                <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="reportDetails">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>

    <?php siteFooter() ?>

    <script>
        function filterReports(status) {
            const rows = document.querySelectorAll('.report-row');
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        async function viewReport(reportId) {
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/fetch/admin-report-details.php?id=' + reportId);
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('reportDetails').innerHTML = data.html;
                    document.getElementById('reportModal').classList.remove('hidden');
                    document.getElementById('reportModal').classList.add('flex');
                } else {
                    alert('Error loading report details');
                }
            } catch (error) {
                alert('Error loading report details');
            }
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            document.getElementById('reportModal').classList.remove('flex');
        }
    </script>
</body>
</html>