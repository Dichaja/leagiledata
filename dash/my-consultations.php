<?php
session_start();
require  __DIR__  . '/../bin/page_settings.php';
require __DIR__  . '/../bin/functions.php';
require __DIR__  . '/../xsert.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's consultation requests
$requests_stmt = $conn->prepare("
    SELECT cr.*, e.title, e.first_name, e.last_name, e.profile_image, e.hourly_rate
    FROM consultation_requests cr
    JOIN experts e ON cr.expert_id = e.id
    WHERE cr.user_id = ?
    ORDER BY cr.created_at DESC
");
$requests_stmt->execute([$user_id]);
$requests = $requests_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats_stmt = $conn->prepare("
    SELECT COUNT(*) as total_requests,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
        SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted_requests,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_requests
    FROM consultation_requests 
    WHERE user_id = ?
");
$stats_stmt->execute([$user_id]);
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Consultations - Leagile Data Research Center</title>
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
                <h1 class="text-3xl font-bold text-gray-800 mb-2">My Consultations</h1>
                <p class="text-gray-600">Track your consultation requests and connect with experts</p>
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
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['completed_requests']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Consultation Requests -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold">My Consultation Requests</h2>
                            <a href="<?php echo BASE_URL; ?>/experts.php" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                <i class="fas fa-plus mr-2"></i>Find Experts
                            </a>
                        </div>
                        
                        <div class="space-y-4" id="requestsList">
                            <?php if (empty($requests)): ?>
                                <div class="text-center py-12">
                                    <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Consultation Requests</h3>
                                    <p class="text-gray-500 mb-6">Start by finding an expert and requesting a consultation.</p>
                                    <a href="<?php echo BASE_URL; ?>/experts.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 h-10 px-6">
                                        Browse Experts
                                    </a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($requests as $request): ?>
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex items-center">
                                                <img src="<?php echo $request['profile_image'] ? htmlspecialchars($request['profile_image']) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $request['expert_id']; ?>" 
                                                     alt="Expert" class="w-12 h-12 rounded-full mr-3">
                                                <div>
                                                    <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($request['subject']); ?></h3>
                                                    <p class="text-sm text-gray-600">
                                                        With: <?php echo htmlspecialchars($request['title'] . ' ' . $request['first_name'] . ' ' . $request['last_name']); ?>
                                                    </p>
                                                    <p class="text-xs text-gray-500"><?php echo date('M d, Y g:i A', strtotime($request['created_at'])); ?></p>
                                                </div>
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
                                        
                                        <?php if ($request['scheduled_date']): ?>
                                            <div class="text-sm text-green-600 mb-3">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Scheduled: <?php echo date('M d, Y g:i A', strtotime($request['scheduled_date'])); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($request['meeting_link']): ?>
                                            <div class="text-sm text-blue-600 mb-3">
                                                <i class="fas fa-video mr-1"></i>
                                                <a href="<?php echo htmlspecialchars($request['meeting_link']); ?>" target="_blank" class="hover:underline">
                                                    Join Meeting
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($request['budget']): ?>
                                            <div class="text-sm text-gray-600 mb-3">
                                                <i class="fas fa-dollar-sign mr-1"></i>
                                                Budget: $<?php echo number_format($request['budget'], 2); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($request['expert_response']): ?>
                                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                                <h4 class="font-semibold text-sm text-gray-800 mb-1">Expert Response:</h4>
                                                <p class="text-sm text-gray-700"><?php echo nl2br(htmlspecialchars($request['expert_response'])); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="flex space-x-2">
                                            <button onclick="viewRequest('<?php echo $request['id']; ?>')" class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200">
                                                View Details
                                            </button>
                                            <?php if ($request['status'] === 'completed'): ?>
                                                <button onclick="reviewExpert('<?php echo $request['expert_id']; ?>', '<?php echo $request['id']; ?>')" class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">
                                                    Leave Review
                                                </button>
                                            <?php endif; ?>
                                            <?php if (in_array($request['status'], ['pending', 'accepted'])): ?>
                                                <button onclick="cancelRequest('<?php echo $request['id']; ?>')" class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-md hover:bg-red-200">
                                                    Cancel
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
                            <a href="<?php echo BASE_URL; ?>/experts.php" class="block w-full text-left px-4 py-2 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200">
                                <i class="fas fa-search mr-2"></i>Find Experts
                            </a>
                            <a href="<?php echo BASE_URL; ?>/expert-registration.php" class="block w-full text-left px-4 py-2 bg-green-100 text-green-800 rounded-md hover:bg-green-200">
                                <i class="fas fa-user-plus mr-2"></i>Become an Expert
                            </a>
                        </div>
                    </div>
                    
                    <!-- Help & Support -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Help & Support</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <h4 class="font-semibold text-gray-800">How it works:</h4>
                                <ol class="list-decimal list-inside text-gray-600 mt-1 space-y-1">
                                    <li>Browse and find experts</li>
                                    <li>Send consultation request</li>
                                    <li>Expert reviews and responds</li>
                                    <li>Schedule and conduct consultation</li>
                                    <li>Leave a review</li>
                                </ol>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Need help?</h4>
                                <p class="text-gray-600 mt-1">Contact our support team for assistance with your consultations.</p>
                            </div>
                        </div>
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

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Leave a Review</h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="reviewForm" class="space-y-4">
                <input type="hidden" name="expert_id" id="reviewExpertId">
                <input type="hidden" name="consultation_id" id="reviewConsultationId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-1" id="ratingStars">
                        <i class="fas fa-star text-2xl text-gray-300 cursor-pointer" data-rating="1"></i>
                        <i class="fas fa-star text-2xl text-gray-300 cursor-pointer" data-rating="2"></i>
                        <i class="fas fa-star text-2xl text-gray-300 cursor-pointer" data-rating="3"></i>
                        <i class="fas fa-star text-2xl text-gray-300 cursor-pointer" data-rating="4"></i>
                        <i class="fas fa-star text-2xl text-gray-300 cursor-pointer" data-rating="5"></i>
                    </div>
                    <input type="hidden" name="rating" id="selectedRating" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Review (optional)</label>
                    <textarea name="review_text" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Share your experience..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeReviewModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit Review</button>
                </div>
            </form>
        </div>
    </div>

    <?php siteFooter() ?>

    <script>
        let selectedRating = 0;

        // Rating stars functionality
        document.querySelectorAll('#ratingStars i').forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.dataset.rating);
                document.getElementById('selectedRating').value = selectedRating;
                
                // Update star display
                document.querySelectorAll('#ratingStars i').forEach((s, index) => {
                    if (index < selectedRating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });

        async function viewRequest(requestId) {
            // Implementation similar to expert dashboard
            alert('View request details for: ' + requestId);
        }

        function reviewExpert(expertId, consultationId) {
            document.getElementById('reviewExpertId').value = expertId;
            document.getElementById('reviewConsultationId').value = consultationId;
            document.getElementById('reviewModal').classList.remove('hidden');
            document.getElementById('reviewModal').classList.add('flex');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewModal').classList.remove('flex');
            // Reset form
            selectedRating = 0;
            document.getElementById('selectedRating').value = '';
            document.querySelectorAll('#ratingStars i').forEach(s => {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            });
        }

        async function cancelRequest(requestId) {
            if (confirm('Are you sure you want to cancel this consultation request?')) {
                try {
                    const response = await fetch('<?php echo BASE_URL; ?>/fetch/user-consultations.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'cancel',
                            request_id: requestId
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Request cancelled successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    alert('Error cancelling request. Please try again.');
                }
            }
        }

        // Handle review form submission
        document.getElementById('reviewForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (selectedRating === 0) {
                alert('Please select a rating');
                return;
            }
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/fetch/user-consultations.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Review submitted successfully!');
                    closeReviewModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error submitting review. Please try again.');
            }
        });
    </script>
</body>
</html>