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

// Get dashboard statistics
try {
    // Users statistics
    $users_stmt = $conn->prepare("SELECT COUNT(*) as total_users FROM users");
    $users_stmt->execute();
    $total_users = $users_stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Reports statistics
    $reports_stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_reports,
            COUNT(CASE WHEN status = 'published' THEN 1 END) as published_reports,
            COUNT(CASE WHEN status = 'pending' OR status IS NULL THEN 1 END) as pending_reports
        FROM reports
    ");
    $reports_stmt->execute();
    $reports_stats = $reports_stmt->fetch(PDO::FETCH_ASSOC);

    // Experts statistics
    $experts_stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_experts,
            COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_experts,
            COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_experts
        FROM experts
    ");
    $experts_stmt->execute();
    $experts_stats = $experts_stmt->fetch(PDO::FETCH_ASSOC);

    // Downloads statistics
    $downloads_stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_downloads,
            COUNT(CASE WHEN download_status = 'approved' THEN 1 END) as approved_downloads,
            COUNT(CASE WHEN download_status = 'pending' THEN 1 END) as pending_downloads
        FROM report_downloads
    ");
    $downloads_stmt->execute();
    $downloads_stats = $downloads_stmt->fetch(PDO::FETCH_ASSOC);

    // Consultations statistics
    $consultations_stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_consultations,
            COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_consultations,
            COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_consultations
        FROM consultation_requests
    ");
    $consultations_stmt->execute();
    $consultations_stats = $consultations_stmt->fetch(PDO::FETCH_ASSOC);

    // Recent activities
    $activities_stmt = $conn->prepare("
        SELECT 'user_registration' as type, usr_name as title, created_at FROM users 
        UNION ALL
        SELECT 'report_upload' as type, title, created_at FROM reports
        UNION ALL
        SELECT 'expert_application' as type, CONCAT(first_name, ' ', last_name) as title, created_at FROM experts
        ORDER BY created_at DESC LIMIT 10
    ");
    $activities_stmt->execute();
    $recent_activities = $activities_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    // Set default values if queries fail
    $total_users = 0;
    $reports_stats = ['total_reports' => 0, 'published_reports' => 0, 'pending_reports' => 0];
    $experts_stats = ['total_experts' => 0, 'approved_experts' => 0, 'pending_experts' => 0];
    $downloads_stats = ['total_downloads' => 0, 'approved_downloads' => 0, 'pending_downloads' => 0];
    $consultations_stats = ['total_consultations' => 0, 'pending_consultations' => 0, 'completed_consultations' => 0];
    $recent_activities = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Leagile Data Research Center</title>
    <?php include('../bin/source_links.php'); ?>
    <style>
        .stat-card {
            transition: transform 0.2s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <?php siteHeader() ?>
    
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <!-- Dashboard Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Dashboard</h1>
                <p class="text-gray-600">Manage your research platform from here</p>
            </div>

            <!-- Quick Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Users Card -->
                <div class="stat-card bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Users</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo number_format($total_users); ?></h3>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Reports Card -->
                <div class="stat-card bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Reports</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo number_format($reports_stats['total_reports']); ?></h3>
                            <p class="text-xs text-green-600"><?php echo $reports_stats['published_reports']; ?> published</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-file-alt text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Experts Card -->
                <div class="stat-card bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Experts</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo number_format($experts_stats['total_experts']); ?></h3>
                            <p class="text-xs text-blue-600"><?php echo $experts_stats['approved_experts']; ?> approved</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Downloads Card -->
                <div class="stat-card bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Downloads</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo number_format($downloads_stats['total_downloads']); ?></h3>
                            <p class="text-xs text-orange-600"><?php echo $downloads_stats['pending_downloads']; ?> pending</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-full">
                            <i class="fas fa-download text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Content Management -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Content Management</h2>
                    <div class="space-y-4">
                        <a href="reports_mgmt.php" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-file-alt text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Reports Management</h3>
                                    <p class="text-sm text-gray-600">Manage uploaded reports and file submissions</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900"><?php echo $reports_stats['total_reports']; ?></span>
                                <?php if ($reports_stats['pending_reports'] > 0): ?>
                                    <span class="block text-xs text-yellow-600"><?php echo $reports_stats['pending_reports']; ?> pending</span>
                                <?php endif; ?>
                            </div>
                        </a>

                        <a href="downloads_mgmt.php" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-orange-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-download text-orange-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Downloads Management</h3>
                                    <p class="text-sm text-gray-600">Approve and manage download requests</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900"><?php echo $downloads_stats['total_downloads']; ?></span>
                                <?php if ($downloads_stats['pending_downloads'] > 0): ?>
                                    <span class="block text-xs text-yellow-600"><?php echo $downloads_stats['pending_downloads']; ?> pending</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- User & Expert Management -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">User & Expert Management</h2>
                    <div class="space-y-4">
                        <a href="experts_mgmt.php" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-user-tie text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Expert Management</h3>
                                    <p class="text-sm text-gray-600">Approve expert applications and manage profiles</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900"><?php echo $experts_stats['total_experts']; ?></span>
                                <?php if ($experts_stats['pending_experts'] > 0): ?>
                                    <span class="block text-xs text-yellow-600"><?php echo $experts_stats['pending_experts']; ?> pending</span>
                                <?php endif; ?>
                            </div>
                        </a>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">User Management</h3>
                                    <p class="text-sm text-gray-600">Manage user accounts and permissions</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900"><?php echo $total_users; ?></span>
                                <span class="block text-xs text-gray-500">total users</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics & Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Consultation Analytics -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Consultation Analytics</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Consultations</span>
                            <span class="font-semibold text-gray-900"><?php echo $consultations_stats['total_consultations']; ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Pending Requests</span>
                            <span class="font-semibold text-yellow-600"><?php echo $consultations_stats['pending_consultations']; ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Completed</span>
                            <span class="font-semibold text-green-600"><?php echo $consultations_stats['completed_consultations']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="<?php echo BASE_URL; ?>/add_file.php" class="block w-full text-left px-4 py-2 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add New Report
                        </a>
                        <a href="reports_mgmt.php?filter=pending" class="block w-full text-left px-4 py-2 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-clock mr-2"></i>Review Pending Reports
                        </a>
                        <a href="experts_mgmt.php?filter=pending" class="block w-full text-left px-4 py-2 bg-purple-100 text-purple-800 rounded-md hover:bg-purple-200 transition-colors">
                            <i class="fas fa-user-check mr-2"></i>Review Expert Applications
                        </a>
                        <a href="downloads_mgmt.php?filter=pending" class="block w-full text-left px-4 py-2 bg-orange-100 text-orange-800 rounded-md hover:bg-orange-200 transition-colors">
                            <i class="fas fa-download mr-2"></i>Approve Downloads
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Activity</h2>
                    <div class="space-y-3">
                        <?php if (empty($recent_activities)): ?>
                            <p class="text-gray-500 text-sm">No recent activity</p>
                        <?php else: ?>
                            <?php foreach (array_slice($recent_activities, 0, 5) as $activity): ?>
                                <div class="flex items-center">
                                    <div class="activity-icon mr-3 
                                        <?php 
                                        switch($activity['type']) {
                                            case 'user_registration': echo 'bg-blue-100'; break;
                                            case 'report_upload': echo 'bg-green-100'; break;
                                            case 'expert_application': echo 'bg-purple-100'; break;
                                            default: echo 'bg-gray-100';
                                        }
                                        ?>">
                                        <i class="fas 
                                            <?php 
                                            switch($activity['type']) {
                                                case 'user_registration': echo 'fa-user text-blue-600'; break;
                                                case 'report_upload': echo 'fa-file-alt text-green-600'; break;
                                                case 'expert_application': echo 'fa-user-tie text-purple-600'; break;
                                                default: echo 'fa-circle text-gray-600';
                                            }
                                            ?> text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['title']); ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?php 
                                            switch($activity['type']) {
                                                case 'user_registration': echo 'New user registered'; break;
                                                case 'report_upload': echo 'New report uploaded'; break;
                                                case 'expert_application': echo 'Expert application submitted'; break;
                                            }
                                            ?>
                                        </p>
                                        <p class="text-xs text-gray-400"><?php echo date('M j, g:i A', strtotime($activity['created_at'])); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php siteFooter() ?>

    <script>
        // Auto-refresh dashboard every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000);
    </script>
</body>
</html>