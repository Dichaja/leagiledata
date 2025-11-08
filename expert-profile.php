<?php
session_start();
require_once('bin/page_settings.php');
require_once('bin/functions.php');
require_once('xsert.php');

// Get expert ID from URL
$expert_id = $_GET['id'] ?? '';
if (empty($expert_id)) {
    header('Location: ' . BASE_URL . '/experts.php');
    exit;
}

// Get expert details with categories and specialties
$expert_stmt = $conn->prepare("
    SELECT e.*, 
           GROUP_CONCAT(DISTINCT es.specialty) as specialties,
           GROUP_CONCAT(DISTINCT ec.name) as categories
    FROM experts e
    LEFT JOIN expert_specialties es ON e.id = es.expert_id
    LEFT JOIN expert_category_assignments eca ON e.id = eca.expert_id
    LEFT JOIN expert_categories ec ON eca.category_id = ec.id
    WHERE e.id = ? AND e.status = 'approved'
    GROUP BY e.id
");
$expert_stmt->execute([$expert_id]);
$expert = $expert_stmt->fetch(PDO::FETCH_ASSOC);

if (!$expert) {
    header('Location: ' . BASE_URL . '/experts.php');
    exit;
}

// Get expert reviews
$reviews_stmt = $conn->prepare("
    SELECT er.*, u.usr_name, u.email
    FROM expert_reviews er
    JOIN users u ON er.user_id = u.id
    WHERE er.expert_id = ?
    ORDER BY er.created_at DESC
    LIMIT 10
");
$reviews_stmt->execute([$expert_id]);
$reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get expert availability
$availability_stmt = $conn->prepare("
    SELECT * FROM expert_availability 
    WHERE expert_id = ? AND is_available = 1
    ORDER BY FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')
");
$availability_stmt->execute([$expert_id]);
$availability = $availability_stmt->fetchAll(PDO::FETCH_ASSOC);

$specialties = $expert['specialties'] ? explode(',', $expert['specialties']) : [];
$categories = $expert['categories'] ? explode(',', $expert['categories']) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']); ?> - Expert Profile</title>
    <?php include('bin/source_links.php'); ?>
</head>
<body>
    <?php siteHeader() ?>
    
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <!-- Expert Header -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="flex flex-col md:flex-row items-start gap-8">
                    <div class="flex-shrink-0">
                        <img src="<?php echo $expert['profile_image'] ? htmlspecialchars($expert['profile_image']) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $expert['id']; ?>" 
                             alt="<?php echo htmlspecialchars($expert['first_name'] . ' ' . $expert['last_name']); ?>"
                             class="w-32 h-32 rounded-full border-4 border-blue-200">
                    </div>
                    
                    <div class="flex-grow">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">
                            <?php echo htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']); ?>
                        </h1>
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $expert['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                <?php endfor; ?>
                                <span class="ml-2 text-sm text-gray-600">
                                    <?php echo number_format($expert['rating'], 1); ?> (<?php echo $expert['total_reviews']; ?> reviews)
                                </span>
                            </div>
                            <span class="text-sm text-gray-600">
                                <?php echo $expert['total_consultations']; ?> consultations completed
                            </span>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php foreach ($categories as $category): ?>
                                <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                    <?php echo htmlspecialchars(trim($category)); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="flex items-center gap-6 mb-4">
                            <div>
                                <span class="text-sm text-gray-600">Experience:</span>
                                <span class="font-semibold"><?php echo $expert['experience_years']; ?> years</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Rate:</span>
                                <span class="font-semibold text-green-600">$<?php echo number_format($expert['hourly_rate'], 2); ?>/hour</span>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <button onclick="openContactModal()" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-message mr-2"></i>Contact Expert
                            </button>
                            <button onclick="openBookingModal()" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                                <i class="fas fa-calendar mr-2"></i>Book Consultation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- About -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold mb-4">About</h2>
                        <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($expert['bio'])); ?></p>
                    </div>
                    
                    <!-- Education & Qualifications -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Education & Qualifications</h2>
                        <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($expert['education'])); ?></p>
                    </div>
                    
                    <!-- Specialties -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Specialties</h2>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($specialties as $specialty): ?>
                                <span class="bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">
                                    <?php echo htmlspecialchars(trim($specialty)); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Reviews -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Reviews</h2>
                        <?php if (empty($reviews)): ?>
                            <p class="text-gray-600">No reviews yet.</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($reviews as $review): ?>
                                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($review['usr_name']); ?></span>
                                                <div class="flex ml-2">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star text-sm <?php echo $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                                        </div>
                                        <?php if ($review['review_text']): ?>
                                            <p class="text-gray-700"><?php echo htmlspecialchars($review['review_text']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Availability -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Availability</h3>
                        <?php if (empty($availability)): ?>
                            <p class="text-gray-600">No availability set.</p>
                        <?php else: ?>
                            <div class="space-y-2">
                                <?php foreach ($availability as $slot): ?>
                                    <div class="flex justify-between text-sm">
                                        <span class="capitalize"><?php echo $slot['day_of_week']; ?></span>
                                        <span><?php echo date('g:i A', strtotime($slot['start_time'])); ?> - <?php echo date('g:i A', strtotime($slot['end_time'])); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Contact Information</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 w-5"></i>
                                <span class="ml-2 text-sm"><?php echo htmlspecialchars($expert['email']); ?></span>
                            </div>
                            <?php if ($expert['phone']): ?>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 w-5"></i>
                                    <span class="ml-2 text-sm"><?php echo htmlspecialchars($expert['phone']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Contact Modal -->
    <div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Contact Expert</h3>
                <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="contactForm" class="space-y-4">
                <input type="hidden" name="expert_id" value="<?php echo $expert['id']; ?>">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeContactModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Send Message</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Book Consultation</h3>
                <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="bookingForm" class="space-y-4">
                <input type="hidden" name="expert_id" value="<?php echo $expert['id']; ?>">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Date</label>
                    <input type="date" name="preferred_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time</label>
                    <input type="time" name="preferred_time" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (hours)</label>
                    <select name="duration_hours" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">1 hour</option>
                        <option value="1.5">1.5 hours</option>
                        <option value="2">2 hours</option>
                        <option value="3">3 hours</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Budget (USD)</label>
                    <input type="number" name="budget" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeBookingModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Book Consultation</button>
                </div>
            </form>
        </div>
    </div>

    <?php siteFooter() ?>

    <script>
        function openContactModal() {
            <?php if (!isset($_SESSION['user_id'])): ?>
                alert('Please login to contact experts.');
                window.location.href = '<?php echo BASE_URL; ?>/login.php';
                return;
            <?php endif; ?>
            document.getElementById('contactModal').classList.remove('hidden');
            document.getElementById('contactModal').classList.add('flex');
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.add('hidden');
            document.getElementById('contactModal').classList.remove('flex');
        }

        function openBookingModal() {
            <?php if (!isset($_SESSION['user_id'])): ?>
                alert('Please login to book consultations.');
                window.location.href = '<?php echo BASE_URL; ?>/login.php';
                return;
            <?php endif; ?>
            document.getElementById('bookingModal').classList.remove('hidden');
            document.getElementById('bookingModal').classList.add('flex');
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
            document.getElementById('bookingModal').classList.remove('flex');
        }

        // Handle contact form submission
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'contact');
            
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/fetch/expert-contact.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Message sent successfully!');
                    closeContactModal();
                    this.reset();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error sending message. Please try again.');
            }
        });

        // Handle booking form submission
        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'book');
            
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/fetch/expert-contact.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Consultation request sent successfully!');
                    closeBookingModal();
                    this.reset();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error booking consultation. Please try again.');
            }
        });
    </script>
</body>
</html>