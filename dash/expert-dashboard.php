<?php
session_start();
require_once('../bin/page_settings.php');
require_once('../bin/functions.php');
require_once('../xsert.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if user is an approved expert
$expert_stmt = $conn->prepare("SELECT * FROM experts WHERE user_id = ? AND status = 'approved'");
$expert_stmt->execute([$user_id]);
$expert = $expert_stmt->fetch(PDO::FETCH_ASSOC);

if (!$expert) {
    header('Location: ' . BASE_URL . '/expert-registration.php');
    exit;
}

$expert_id = $expert['id'];

// Get consultation requests
$requests_stmt = $conn->prepare("
    SELECT cr.*, u.usr_name, u.email as user_email
    FROM consultation_requests cr
    JOIN users u ON cr.user_id = u.id
    WHERE cr.expert_id = ?
    ORDER BY cr.created_at DESC
");
$requests_stmt->execute([$expert_id]);
$requests = $requests_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats_stmt = $conn->prepare("
    SELECT 
        COUNT(*) as total_requests,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
        SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted_requests,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_requests
    FROM consultation_requests 
    WHERE expert_id = ?
");
$stats_stmt->execute([$expert_id]);
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

// Get recent reviews
$reviews_stmt = $conn->prepare("
    SELECT er.*, u.usr_name
    FROM expert_reviews er
    JOIN users u ON er.user_id = u.id
    WHERE er.expert_id = ?
    ORDER BY er.created_at DESC
    LIMIT 5
");
$reviews_stmt->execute([$expert_id]);
$recent_reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Dashboard - Leagile Data Research Center</title>
    <?php include('../bin/source_links.php'); ?>
    <style>
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-accepted { background-color: #d1fae5; color: #065f46; }
        .status-completed { background-color: #dbeafe; color: #1e40af; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        .status-cancelled { background-color: #f3f4f6; color: #374151; }
    </style>
</head>
<body>
    <?php siteHeader() ?>
    
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Expert Dashboard</h1>
                <p class="text-gray-600">Welcome back, <?php echo htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']); ?></p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Requests</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_requests']; ?></p>
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
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['pending_requests']; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Accepted</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['accepted_requests']; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-star text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rating</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($expert['rating'], 1); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Consultation Requests -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold">Consultation Requests</h2>
                            <div class="flex space-x-2">
                                <button onclick="filterRequests('all')" class="px-3 py-1 text-sm bg-gray-100 rounded-md hover:bg-gray-200">All</button>
                                <button onclick="filterRequests('pending')" class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">Pending</button>
                                <button onclick="filterRequests('accepted')" class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-md hover:bg-green-200">Accepted</button>
                            </div>
                        </div>
                        
                        <div class="space-y-4" id="requestsList">
                            <?php if (empty($requests)): ?>
                                <p class="text-gray-600 text-center py-8">No consultation requests yet.</p>
                            <?php else: ?>
                                <?php foreach ($requests as $request): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 request-item" data-status="<?php echo $request['status']; ?>">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($request['subject']); ?></h3>
                                                <p class="text-sm text-gray-600">From: <?php echo htmlspecialchars($request['usr_name']); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo date('M d, Y g:i A', strtotime($request['created_at'])); ?></p>
                                            </div>
                                            <span class="px-2 py-1 text-xs rounded-full status-<?php echo $request['status']; ?>">
                                                <?php echo ucfirst($request['status']); ?>
                                            </span>
                                        </div>
                                        
                                        <p class="text-gray-700 text-sm mb-3"><?php echo htmlspecialchars(substr($request['message'], 0, 150)) . (strlen($request['message']) > 150 ? '...' : ''); ?></p>
                                        
                                        <?php if ($request['preferred_date']): ?>
                                            <div class="text-sm text-gray-600 mb-3">
                                                <i class="fas fa-calendar mr-1"></i>
                                                Preferred: <?php echo date('M d, Y', strtotime($request['preferred_date'])); ?>
                                                <?php if ($request['preferred_time']): ?>
                                                    at <?php echo date('g:i A', strtotime($request['preferred_time'])); ?>
                                                <?php endif; ?>
                                                <?php if ($request['duration_hours']): ?>
                                                    (<?php echo $request['duration_hours']; ?> hours)
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($request['budget']): ?>
                                            <div class="text-sm text-gray-600 mb-3">
                                                <i class="fas fa-dollar-sign mr-1"></i>
                                                Budget: $<?php echo number_format($request['budget'], 2); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="flex space-x-2">
                                            <button onclick="viewRequest('<?php echo $request['id']; ?>')" class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200">
                                                View Details
                                            </button>
                                            <?php if ($request['status'] === 'pending'): ?>
                                                <button onclick="respondToRequest('<?php echo $request['id']; ?>', 'accepted')" class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-md hover:bg-green-200">
                                                    Accept
                                                </button>
                                                <button onclick="respondToRequest('<?php echo $request['id']; ?>', 'rejected')" class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-md hover:bg-red-200">
                                                    Decline
                                                </button>
                                            <?php elseif ($request['status'] === 'accepted'): ?>
                                                <button onclick="respondToRequest('<?php echo $request['id']; ?>', 'completed')" class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-md hover:bg-purple-200">
                                                    Mark Complete
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert_id; ?>" class="block w-full text-left px-4 py-2 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200">
                                <i class="fas fa-eye mr-2"></i>View My Profile
                            </a>
                            <a href="<?php echo BASE_URL; ?>/expert-registration.php" class="block w-full text-left px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">
                                <i class="fas fa-edit mr-2"></i>Edit Profile
                            </a>
                            <button onclick="openAvailabilityModal()" class="block w-full text-left px-4 py-2 bg-green-100 text-green-800 rounded-md hover:bg-green-200">
                                <i class="fas fa-calendar mr-2"></i>Set Availability
                            </button>
                        </div>
                    </div>
                    
                    <!-- Recent Reviews -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Recent Reviews</h3>
                        <?php if (empty($recent_reviews)): ?>
                            <p class="text-gray-600 text-sm">No reviews yet.</p>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach ($recent_reviews as $review): ?>
                                    <div class="border-b border-gray-200 pb-3 last:border-b-0">
                                        <div class="flex items-center mb-1">
                                            <div class="flex">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star text-xs <?php echo $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="ml-2 text-xs text-gray-600"><?php echo htmlspecialchars($review['usr_name']); ?></span>
                                        </div>
                                        <?php if ($review['review_text']): ?>
                                            <p class="text-xs text-gray-700"><?php echo htmlspecialchars(substr($review['review_text'], 0, 100)) . (strlen($review['review_text']) > 100 ? '...' : ''); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Request Details Modal -->
    <div id="requestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Request Details</h3>
                <button onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="requestDetails">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Response Modal -->
    <div id="responseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold" id="responseModalTitle">Respond to Request</h3>
                <button onclick="closeResponseModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="responseForm" class="space-y-4">
                <input type="hidden" name="request_id" id="responseRequestId">
                <input type="hidden" name="action" id="responseAction">
                
                <div id="responseFields">
                    <!-- Dynamic fields will be added here -->
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Response Message</label>
                    <textarea name="expert_response" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeResponseModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Send Response</button>
                </div>
            </form>
        </div>
    </div>

    <?php siteFooter() ?>

    <script>
        function filterRequests(status) {
            const items = document.querySelectorAll('.request-item');
            items.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        async function viewRequest(requestId) {
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/fetch/expert-requests.php?action=view&id=' + requestId);
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('requestDetails').innerHTML = data.html;
                    document.getElementById('requestModal').classList.remove('hidden');
                    document.getElementById('requestModal').classList.add('flex');
                } else {
                    alert('Error loading request details');
                }
            } catch (error) {
                alert('Error loading request details');
            }
        }

        function closeRequestModal() {
            document.getElementById('requestModal').classList.add('hidden');
            document.getElementById('requestModal').classList.remove('flex');
        }

        function respondToRequest(requestId, action) {
            document.getElementById('responseRequestId').value = requestId;
            document.getElementById('responseAction').value = action;
            
            const title = document.getElementById('responseModalTitle');
            const fields = document.getElementById('responseFields');
            
            if (action === 'accepted') {
                title.textContent = 'Accept Consultation Request';
                fields.innerHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date & Time</label>
                        <input type="datetime-local" name="scheduled_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Link (optional)</label>
                        <input type="url" name="meeting_link" placeholder="https://zoom.us/j/..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                `;
            } else if (action === 'rejected') {
                title.textContent = 'Decline Consultation Request';
                fields.innerHTML = '';
            } else if (action === 'completed') {
                title.textContent = 'Mark Consultation as Complete';
                fields.innerHTML = '';
            }
            
            document.getElementById('responseModal').classList.remove('hidden');
            document.getElementById('responseModal').classList.add('flex');
        }

        function closeResponseModal() {
            document.getElementById('responseModal').classList.add('hidden');
            document.getElementById('responseModal').classList.remove('flex');
        }

        // Handle response form submission
        document.getElementById('responseForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/fetch/expert-requests.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Response sent successfully!');
                    closeResponseModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error sending response. Please try again.');
            }
        });
    </script>
</body>
</html>