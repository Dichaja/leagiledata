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

// Get all experts with their stats
$experts_stmt = $conn->prepare("
    SELECT e.*, 
           COUNT(DISTINCT cr.id) as total_requests,
           COUNT(DISTINCT CASE WHEN cr.status = 'completed' THEN cr.id END) as completed_consultations,
           AVG(er.rating) as avg_rating,
           COUNT(DISTINCT er.id) as total_reviews
    FROM experts e
    LEFT JOIN consultation_requests cr ON e.id = cr.expert_id
    LEFT JOIN expert_reviews er ON e.id = er.expert_id
    GROUP BY e.id
    ORDER BY e.created_at DESC
");
$experts_stmt->execute();
$experts = $experts_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $expert_id = $_POST['expert_id'] ?? '';
    $action = $_POST['action'] ?? '';
    
    if ($action === 'approve') {
        $conn->prepare("UPDATE experts SET status = 'approved' WHERE id = ?")->execute([$expert_id]);
        $success_message = "Expert approved successfully!";
    } elseif ($action === 'reject') {
        $conn->prepare("UPDATE experts SET status = 'rejected' WHERE id = ?")->execute([$expert_id]);
        $success_message = "Expert rejected successfully!";
    } elseif ($action === 'suspend') {
        $conn->prepare("UPDATE experts SET status = 'suspended' WHERE id = ?")->execute([$expert_id]);
        $success_message = "Expert suspended successfully!";
    }
    
    // Refresh the page to show updated data
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Management - Admin</title>
    <?php include('../bin/source_links.php'); ?>
    <style>
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #d1fae5; color: #065f46; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        .status-suspended { background-color: #f3f4f6; color: #374151; }
    </style>
</head>
<body>
    <?php siteHeader() ?>
    
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Expert Management</h1>
                <p class="text-gray-600">Manage expert applications and profiles</p>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <?php
                $total_experts = count($experts);
                $pending_experts = count(array_filter($experts, fn($e) => $e['status'] === 'pending'));
                $approved_experts = count(array_filter($experts, fn($e) => $e['status'] === 'approved'));
                $rejected_experts = count(array_filter($experts, fn($e) => $e['status'] === 'rejected'));
                ?>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Experts</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $total_experts; ?></p>
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
                            <p class="text-2xl font-bold text-gray-900"><?php echo $pending_experts; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $approved_experts; ?></p>
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
                            <p class="text-2xl font-bold text-gray-900"><?php echo $rejected_experts; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Experts Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">All Experts</h2>
                        <div class="flex space-x-2">
                            <button onclick="filterExperts('all')" class="px-3 py-1 text-sm bg-gray-100 rounded-md hover:bg-gray-200">All</button>
                            <button onclick="filterExperts('pending')" class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">Pending</button>
                            <button onclick="filterExperts('approved')" class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-md hover:bg-green-200">Approved</button>
                            <button onclick="filterExperts('rejected')" class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-md hover:bg-red-200">Rejected</button>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expert</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Experience</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($experts as $expert): ?>
                                <tr class="expert-row" data-status="<?php echo $expert['status']; ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" 
                                                     src="<?php echo $expert['profile_image'] ? htmlspecialchars($expert['profile_image']) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $expert['id']; ?>" 
                                                     alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    $<?php echo number_format($expert['hourly_rate'], 2); ?>/hour
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($expert['email']); ?></div>
                                        <?php if ($expert['phone']): ?>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($expert['phone']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo $expert['experience_years']; ?> years</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div><?php echo $expert['total_requests']; ?> requests</div>
                                        <div><?php echo $expert['completed_consultations']; ?> completed</div>
                                        <div><?php echo $expert['avg_rating'] ? number_format($expert['avg_rating'], 1) : '0'; ?>★ (<?php echo $expert['total_reviews']; ?>)</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-<?php echo $expert['status']; ?>">
                                            <?php echo ucfirst($expert['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($expert['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="viewExpert('<?php echo $expert['id']; ?>')" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($expert['status'] === 'pending'): ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="expert_id" value="<?php echo $expert['id']; ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Approve this expert?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="expert_id" value="<?php echo $expert['id']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Reject this expert?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            <?php elseif ($expert['status'] === 'approved'): ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="expert_id" value="<?php echo $expert['id']; ?>">
                                                    <input type="hidden" name="action" value="suspend">
                                                    <button type="submit" class="text-orange-600 hover:text-orange-900" onclick="return confirm('Suspend this expert?')">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
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

    <!-- Expert Details Modal -->
    <div id="expertModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Expert Details</h3>
                <button onclick="closeExpertModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="expertDetails">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>

    <?php siteFooter() ?>

    <script>
        function filterExperts(status) {
            const rows = document.querySelectorAll('.expert-row');
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        async function viewExpert(expertId) {
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/fetch/admin-expert-details.php?id=' + expertId);
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('expertDetails').innerHTML = data.html;
                    document.getElementById('expertModal').classList.remove('hidden');
                    document.getElementById('expertModal').classList.add('flex');
                } else {
                    alert('Error loading expert details');
                }
            } catch (error) {
                alert('Error loading expert details');
            }
        }

        function closeExpertModal() {
            document.getElementById('expertModal').classList.add('hidden');
            document.getElementById('expertModal').classList.remove('flex');
        }
    </script>
</body>
</html>