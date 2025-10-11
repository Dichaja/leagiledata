<?php
require_once 'xsert.php';



// Get user details
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Get user activities
$activities_stmt = $conn->prepare("SELECT * FROM user_activities WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$activities_stmt->bind_param("i", $user_id);
$activities_stmt->execute();
$activities_result = $activities_stmt->get_result();

// Get recommended reports
$reports_stmt = $conn->prepare("SELECT * FROM reports ORDER BY created_at DESC LIMIT 3");
$reports_stmt->execute();
$reports_result = $reports_stmt->get_result();

// Get stats
$downloads_stmt = $conn->prepare("SELECT COUNT(*) as count FROM user_activities WHERE user_id = ? AND activity_type = 'download'");
$downloads_stmt->bind_param("i", $user_id);
$downloads_stmt->execute();
$downloads = $downloads_stmt->get_result()->fetch_assoc()['count'];

$saved_stmt = $conn->prepare("SELECT COUNT(*) as count FROM user_activities WHERE user_id = ? AND activity_type = 'save'");
$saved_stmt->bind_param("i", $user_id);
$saved_stmt->execute();
$saved = $saved_stmt->get_result()->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Leagile Data Research Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 text-[#333]">
        <div class="container flex h-20 items-center justify-between px-4 md:px-6">
            <!-- Logo -->
            <a class="flex items-center space-x-2" href="/">
                <div class="h-8 w-14 flex items-center justify-center">
                    <img src="img_data/logo.jpg" alt="Research Center Logo" class="h-10 w-auto">
                </div>
                <div class="hidden md:flex flex-col">
                    <span class="font-bold text-xl">Leagile Research Data Center</span>
                    <span class="text-muted-slogan">The source of research Data</span>
                </div>
            </a>

            <!-- User Profile Dropdown -->
            <div class="flex items-center space-x-4">
                <!-- Cart -->
                <a href="cart.php" class="relative">
                    <button class="inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                            <path d="M3 6h18"></path>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <div id="cart-count" class="rounded-md border font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-destructive text-destructive-foreground shadow hover:bg-destructive/80 absolute -top-2 -right-2 h-5 w-5 flex items-center justify-center p-0 text-xs"></div>
                    </button>
                </a>

                <!-- User Profile -->
                <div class="relative">
                    <button id="userMenuBtn" class="flex items-center space-x-2 focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                            <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                        </div>
                        <span class="hidden md:block text-sm font-medium"><?php echo htmlspecialchars($user_name); ?></span>
                    </button>
                    
                    <!-- User Dropdown Menu -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                        <div class="px-4 py-2 border-b">
                            <p class="text-sm font-medium"><?php echo htmlspecialchars($user_name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($user_email); ?></p>
                        </div>
                        <a href="dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="subscriptions.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Subscriptions</a>
                        <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                        <div class="border-t"></div>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <!-- User Dashboard Section -->
            <div class="max-w-7xl mx-auto">
                <!-- Dashboard Header -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Welcome back, <?php echo htmlspecialchars($user_name); ?></h1>
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

                    <!-- Downloads Card -->
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Downloads This Month</p>
                                <h3 class="text-xl font-bold mt-1"><?php echo $downloads; ?></h3>
                                <p class="text-sm text-gray-500 mt-1">5 remaining</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-download text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <button class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-4 rounded-md text-sm font-medium">
                            View History
                        </button>
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
                        <?php while ($activity = $activities_result->fetch_assoc()): ?>
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
                                    case 'share':
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
                                    <p class="text-sm font-medium"><?php echo htmlspecialchars($activity['description']); ?></p>
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
                        <?php while ($report = $reports_result->fetch_assoc()): ?>
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
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12 w-full">
        <!-- Your existing footer content -->
    </footer>

    <script>
        // Toggle user menu
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>