<?php
session_start();
require __DIR__ . '/../bin/page_settings.php';
require __DIR__  . '/../bin/functions.php';
require __DIR__ . '/../xsert.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    // Get user details
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $user_email = $_SESSION['user_email'];
} else {
   header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Get user activities
$activities_stmt = $conn->prepare("SELECT * FROM user_activities WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$activities_stmt->execute([$user_id]);
//$activities_result = $activities_stmt->fetch(PDO::FETCH_ASSOC);

// Get recommended reports
$reports_stmt = $conn->prepare("SELECT * FROM reports ORDER BY created_at DESC LIMIT 3");
$reports_stmt->execute();

// Get stats
$downloads_stmt = $conn->prepare("SELECT COUNT(*) as count FROM report_downloads WHERE user_id = ? AND download_status = 'downloaded'");
$downloads_stmt->execute([$user_id]);
$downloads = $downloads_stmt->fetch(PDO::FETCH_ASSOC)['count'];

$saved_stmt = $conn->prepare("SELECT COUNT(*) as count FROM report_downloads WHERE user_id = ? AND download_status = 'pending'");
$saved_stmt->execute([$user_id]);
$saved = $saved_stmt->fetch(PDO::FETCH_ASSOC)['count'];

?>
<!DOCTYPE html>
<html lang="en">
 <head>
   <?php include __DIR__ . '/../bin/source_links.php' ?>
</head>
<body>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
  <div class="container mx-auto px-4 py-8">
    <!-- User Dashboard Section -->
    <div class="max-w-7xl mx-auto">
      <!-- Dashboard Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Welcome back, <?php echo $user_name ?></h1>
        <p class="text-gray-600">Here's what's happening with your account today.</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Active Subscription Card -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Active Subscription</p>
              <h3 class="text-xl font-bold mt-1">Premium Plan</h3>
              <p class="text-sm text-gray-500 mt-1">Expires in 15 days</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
              <i class="fas fa-crown text-blue-600 text-xl"></i>
            </div>
          </div>
          <button class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium">
            Upgrade Plan
          </button>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Downloads</p>
                                <h3 class="text-xl font-bold mt-1"><?php echo $downloads; ?></h3>
                                <p class="text-sm text-gray-500 mt-1">5 Items</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-download text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <a href="downloads_report.php"><button class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-4 rounded-md text-sm font-medium">
                            View History
                        </button></a>
                    </div>

        <!-- Saved Reports Card -->
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Saved Reports</p>
                                <h3 class="text-xl font-bold mt-1"><?php echo $saved; ?></h3>
                                <p class="text-sm text-gray-500 mt-1">Last added 2 days ago</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-bookmark text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <button class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-4 rounded-md text-sm font-medium">
                            View All
                        </button>
                    </div>
      </div>

      <!-- Recent Activity Section -->
                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <?php while ($activity = $activities_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="px-6 py-4 flex items-start">
                                <?php
                                $icon_class = '';
                                $bg_class = '';
                                switch ($activity['activity_type']) {
                                    case 'download':
                                        $icon_class = 'fas fa-download text-blue-600';
                                        $bg_class = 'bg-blue-100';
                                        break;
                                    case 'save':
                                        $icon_class = 'fas fa-bookmark text-green-600';
                                        $bg_class = 'bg-green-100';
                                        break;
                                    case 'login':
                                        $icon_class = 'fas fa-share-alt text-purple-600';
                                        $bg_class = 'bg-purple-100';
                                        break;
                                    case 'comment':
                                        $icon_class = 'fas fa-comment text-yellow-600';
                                        $bg_class = 'bg-yellow-100';
                                        break;
                                    default:
                                        $icon_class = 'fas fa-circle text-gray-600';
                                        $bg_class = 'bg-gray-100';
                                }
                                ?>
                                <div class="<?php echo $bg_class; ?> p-2 rounded-full mr-4">
                                    <i class="<?php echo $icon_class; ?>"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium"><?php echo htmlspecialchars($activity['activity_type']); ?></p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        
                    </div>
                    <div class="px-6 py-3 bg-gray-50 text-right">
                        <a href="activities.php" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all activity</a>
                    </div>
                </div>


      <!-- Recommended Reports Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Recommended For You</h2>
                        <a href="reports.php" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php while ($report = $reports_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                                <div class="h-40 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-chart-line text-4xl text-gray-400"></i>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-1"><?php echo htmlspecialchars($report['title']); ?></h3>
                                    <p class="text-sm text-gray-600 mb-3"><?php echo htmlspecialchars($report['description']); ?></p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded"><?php echo htmlspecialchars($report['category']); ?></span>
                                        <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">View</button>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
        </div>
      </div>
    </div>
  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>
</body></html>