<?php
session_start();
require_once('bin/page_settings.php');
require_once('bin/functions.php');
require_once('xsert.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Check if user is already an expert
$expert_check = $conn->prepare("SELECT id, status FROM experts WHERE user_id = ?");
$expert_check->execute([$user_id]);
$existing_expert = $expert_check->fetch(PDO::FETCH_ASSOC);

// Get categories for dropdown
$categories_stmt = $conn->prepare("SELECT * FROM expert_categories ORDER BY name");
$categories_stmt->execute();
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $expert_id = gen_uuid();
        $title = $_POST['title'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $bio = $_POST['bio'];
        $education = $_POST['education'];
        $experience_years = (int)$_POST['experience_years'];
        $hourly_rate = (float)$_POST['hourly_rate'];
        $specialties = $_POST['specialties'] ?? [];
        $selected_categories = $_POST['categories'] ?? [];

        // Handle profile image upload
        $profile_image = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/experts/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $profile_image = $upload_dir . $expert_id . '.' . $file_extension;
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $profile_image);
        }

        if ($existing_expert) {
            // Update existing expert
            $update_stmt = $conn->prepare("
                UPDATE experts SET 
                title = ?, first_name = ?, last_name = ?, phone = ?, bio = ?, 
                education = ?, experience_years = ?, hourly_rate = ?, profile_image = ?, 
                status = 'pending', updated_at = CURRENT_TIMESTAMP
                WHERE user_id = ?
            ");
            $update_stmt->execute([
                $title, $first_name, $last_name, $phone, $bio, 
                $education, $experience_years, $hourly_rate, $profile_image, $user_id
            ]);
            $expert_id = $existing_expert['id'];
        } else {
            // Insert new expert
            $insert_stmt = $conn->prepare("
                INSERT INTO experts (id, user_id, title, first_name, last_name, email, phone, bio, education, experience_years, hourly_rate, profile_image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert_stmt->execute([
                $expert_id, $user_id, $title, $first_name, $last_name, $user_email, 
                $phone, $bio, $education, $experience_years, $hourly_rate, $profile_image
            ]);
        }

        // Clear existing specialties and categories
        $conn->prepare("DELETE FROM expert_specialties WHERE expert_id = ?")->execute([$expert_id]);
        $conn->prepare("DELETE FROM expert_category_assignments WHERE expert_id = ?")->execute([$expert_id]);

        // Insert specialties
        if (!empty($specialties)) {
            $specialty_stmt = $conn->prepare("INSERT INTO expert_specialties (id, expert_id, specialty) VALUES (?, ?, ?)");
            foreach ($specialties as $specialty) {
                if (!empty(trim($specialty))) {
                    $specialty_stmt->execute([gen_uuid(), $expert_id, trim($specialty)]);
                }
            }
        }

        // Insert category assignments
        if (!empty($selected_categories)) {
            $category_stmt = $conn->prepare("INSERT INTO expert_category_assignments (id, expert_id, category_id) VALUES (?, ?, ?)");
            foreach ($selected_categories as $category_id) {
                $category_stmt->execute([gen_uuid(), $expert_id, $category_id]);
            }
        }

        $success_message = "Expert profile " . ($existing_expert ? "updated" : "submitted") . " successfully! Your application is under review.";
        
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become an Expert - Leagile Data Research Center</title>
    <?php include('bin/source_links.php'); ?>
    <style>
        .specialty-input {
            margin-bottom: 10px;
        }
        .add-specialty-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .remove-specialty-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <?php siteHeader() ?>
    
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8 max-w-4xl">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Become an Expert</h1>
                <p class="text-gray-600">Join our network of research experts and share your knowledge with clients worldwide.</p>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($existing_expert && $existing_expert['status'] === 'pending'): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                    Your expert application is currently under review. You can update your information below.
                </div>
            <?php elseif ($existing_expert && $existing_expert['status'] === 'approved'): ?>
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                    Your expert profile is approved! You can update your information below.
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <select name="title" id="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Title</option>
                                <option value="Dr.">Dr.</option>
                                <option value="Prof.">Prof.</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Ms.">Ms.</option>
                                <option value="Mrs.">Mrs.</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" id="first_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" id="last_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Professional Information -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Professional Bio</label>
                        <textarea name="bio" id="bio" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tell us about your professional background and expertise..."></textarea>
                    </div>

                    <div>
                        <label for="education" class="block text-sm font-medium text-gray-700 mb-2">Education & Qualifications</label>
                        <textarea name="education" id="education" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="List your degrees, certifications, and relevant qualifications..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                            <input type="number" name="experience_years" id="experience_years" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate (USD)</label>
                            <input type="number" name="hourly_rate" id="hourly_rate" min="0" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expertise Categories</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <?php foreach ($categories as $category): ?>
                                <label class="flex items-center">
                                    <input type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>" class="mr-2">
                                    <span class="text-sm"><?php echo htmlspecialchars($category['name']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Specialties -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Specialties</label>
                        <div id="specialties-container">
                            <div class="specialty-input">
                                <input type="text" name="specialties[]" placeholder="Enter a specialty" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <button type="button" onclick="addSpecialty()" class="add-specialty-btn mt-2">Add Another Specialty</button>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="<?php echo BASE_URL; ?>/experts.php" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <?php echo $existing_expert ? 'Update Profile' : 'Submit Application'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php siteFooter() ?>

    <script>
        function addSpecialty() {
            const container = document.getElementById('specialties-container');
            const div = document.createElement('div');
            div.className = 'specialty-input';
            div.innerHTML = `
                <input type="text" name="specialties[]" placeholder="Enter a specialty" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="removeSpecialty(this)" class="remove-specialty-btn">Remove</button>
            `;
            container.appendChild(div);
        }

        function removeSpecialty(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>