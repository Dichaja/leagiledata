<?php
 session_start();
 require_once('bin/page_settings.php');
 require_once('bin/functions.php');
 require_once('xsert.php');
 
 // Redirect if already logged in
 $user_id = $_SESSION['user_id'] ?? '';
 $user_name = $_SESSION['user_name'] ?? '';
 $user_email = $_SESSION['user_email'] ?? '';

 // Get featured experts (top 3 approved experts)
 $experts_stmt = $conn->prepare("
     SELECT e.*, 
            GROUP_CONCAT(DISTINCT es.specialty SEPARATOR ', ') as specialties,
            GROUP_CONCAT(DISTINCT ec.name SEPARATOR ', ') as categories
     FROM experts e
     LEFT JOIN expert_specialties es ON e.id = es.expert_id
     LEFT JOIN expert_category_assignments eca ON e.id = eca.expert_id
     LEFT JOIN expert_categories ec ON eca.category_id = ec.id
     WHERE e.status = 'approved'
     GROUP BY e.id
     ORDER BY e.rating DESC, e.total_reviews DESC
     LIMIT 3
 ");
 $experts_stmt->execute();
 $featured_experts = $experts_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('bin/source_links.php'); ?>
</head>
<body class="font-sans bg-gray-100 text-gray-900">
   <?php siteHeader() ?>
     <div class="relative">
        <div class="h-[200px] sm:h-[190px] w-full bg-cover bg-center py-2" style="background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1200&q=80');">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 to-gray-900/50"></div>
            
            <div class="container mx-auto h-full flex flex-col justify-center px-4 relative z-10">
                <!-- Main Title -->
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4 max-w-2xl">
                    Obtain Accurate Data Requirements
                </h1>
                
                <!-- Search Bar -->
                <div class="mb-4 max-w-2xl">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="search-input" 
                            placeholder="Search research reports..." 
                            class="w-full px-4 py-3 pl-12 pr-4 rounded-lg border-2 border-white/20 bg-white/10 backdrop-blur-sm text-white placeholder-white/60 focus:outline-none focus:border-yellow-400 focus:bg-white/20 transition-all"
                        />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Key Stats Grid - Compact -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 mb-4 sm:mb-6 max-w-2xl">
                    <div class="bg-white/10 p-2 sm:p-3 rounded border border-white/20">
                        <div class="text-xl sm:text-2xl font-bold text-yellow-400">100</div>
                        <div class="text-xs text-white/80">Research Reports</div>
                    </div>
                    <div class="bg-white/10 p-2 sm:p-3 rounded border border-white/20">
                        <div class="text-xl sm:text-2xl font-bold text-yellow-400">150</div>
                        <div class="text-xs text-white/80">Monthly Downloads</div>
                    </div>
                    <div class="bg-white/10 p-2 sm:p-3 rounded border border-white/20 hidden sm:block">
                        <div class="text-xl sm:text-2xl font-bold text-yellow-400">50</div>
                        <div class="text-xs text-white/80">New Additions</div>
                    </div>
                </div>
                
                <!-- Trust Badges - Compact -->
                <div class="flex flex-wrap gap-2 text-white/80 text-xs">
                    <div class="flex items-center bg-white/10 px-2 py-1 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Peer-Reviewed
                    </div>
                    <div class="flex items-center bg-white/10 px-2 py-1 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Secure Access
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - Reduced space between hero and deals -->
    <main class="container mx-auto px-4 mt-4">
        <section class="mb-6 sm:mb-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg sm:text-xl font-bold">Trending Research Today</h2>
                <a href="categories.php" class="text-sm text-blue-600 hover:underline">See all deals</a>
            </div>
            <div id="report-cards" class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-4">
                <!-- Research report card items -->
            </div>
        </section>
        
        <section class="bg-gradient-to-r from-blue-50 to-indigo-50 py-10 px-4 sm:px-6 rounded-xl shadow-md border border-gray-100 mb-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row items-center">
            <!-- Text Content -->
            <div class="lg:w-1/2 mb-8 lg:mb-0 lg:pr-10">
                <!-- Badge and Title -->
                <div class="flex items-center mb-4">
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide">Premium</span>
                    <span class="ml-3 text-indigo-600 font-medium">Most Popular</span>
                </div>
                
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Leagile Research Prime</h2>
                
                <p class="text-lg text-gray-700 mb-8">Full access to all reports and expert consultations with our premium membership.</p>
                
                <!-- Pricing Tiers -->
                <div class="mb-8 space-y-4">
                    <!-- Monthly Plan -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-baseline mb-3">
                            <span class="text-3xl font-extrabold text-gray-900">UGX 100k</span>
                            <span class="ml-2 text-gray-600">/month</span>
                            <span class="ml-auto text-sm bg-indigo-50 text-indigo-700 px-2.5 py-0.5 rounded-full">Flexible</span>
                        </div>
                        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 subscribe-btn" 
                                data-plan="monthly" 
                                data-price="100000">
                            Subscribe Now
                        </button>
                    </div>
                    
                    <!-- 6 month Plan -->
                    <div class="bg-gray-50 p-4 rounded-lg border-2 border-indigo-100">
                        <div class="flex items-baseline mb-2">
                            <span class="text-3xl font-extrabold text-gray-900">UGX 200k</span>
                            <span class="ml-2 text-gray-600">/ 6 months</span>
                            <span class="ml-auto text-sm bg-indigo-100 text-indigo-700 px-2.5 py-0.5 rounded-full">Best Value</span>
                        </div>
                        <div class="flex mb-3">
                            <div>
                                <span class="block text-sm text-gray-500">Billed as UGX 600k</span>
                                <span class="text-xs text-green-600 font-medium">Save 60%</span>
                            </div>
                        </div>
                        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 subscribe-btn" 
                                data-plan="6-month" 
                                data-price="200000" 
                                data-billing="600000">
                            Subscribe Now
                        </button>
                    </div>
                    
                    <!-- Annual Plan -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-baseline mb-2">
                            <span class="text-3xl font-extrabold text-gray-900">UGX 300k</span>
                            <span class="ml-2 text-gray-600"> Annually</span>
                        </div>
                        <div class="flex mb-3">
                            <div>
                                <span class="block text-sm text-gray-500">Billed as UGX 1.2m</span>
                                <span class="text-xs text-green-600 font-medium">Save 75%</span>
                            </div>
                        </div>
                        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 subscribe-btn" 
                                data-plan="annual" 
                                data-price="300000" 
                                data-billing="1200000">
                            Subscribe Now
                        </button>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <button class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-900 font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 mb-8 free-trial-btn">
                    Try Prime Free for 30 Days
                </button>
                
                <!-- Features Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Discount on Downloads</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Priority email &amp; phone support</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Expert Consultations</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Advanced analytics dashboard</span>
                    </div>
                </div>
            </div>
            
            <!-- Image -->
            <div class="lg:w-1/2">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1000&amp;q=80" alt="Premium Benefits" class="w-full h-auto rounded-lg object-cover">
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">Join 5,000+ researchers and businesses using Leagile Research Prime</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="w-full py-8 sm:py-12 bg-gradient-to-b from-slate-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 max-w-7xl">
        <!-- Section Header -->
        <div class="text-center mb-8 sm:mb-12">
            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full uppercase tracking-wider mb-3">Expert Network</span>
            <h2 class="text-2xl sm:text-4xl font-bold tracking-tight text-gray-900 mb-2 sm:mb-4">Featured Research Experts</h2>
            <p class="text-sm sm:text-lg text-gray-600 max-w-3xl mx-auto">
                Connect with leading specialists in various research domains for personalized consultations and insights.
            </p>
        </div>

        <?php if (empty($featured_experts)): ?>
            <!-- No Experts Available -->
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Experts Available Yet</h3>
                <p class="text-gray-500 mb-6">Be the first to join our expert network and share your expertise!</p>
                <a href="<?php echo BASE_URL; ?>/expert-registration.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 h-10 px-6">
                    Become an Expert
                </a>
            </div>
        <?php else: ?>
            <!-- Expert Cards - Mobile Carousel -->
            <div class="lg:hidden relative">
                <div class="overflow-x-auto pb-4">
                    <div class="flex space-x-4" style="width: max-content;">
                        <?php foreach ($featured_experts as $expert): 
                            $expert_specialties = $expert['specialties'] ? explode(', ', $expert['specialties']) : [];
                            $expert_categories = $expert['categories'] ? explode(', ', $expert['categories']) : [];
                        ?>
                        <!-- Expert Card - Mobile -->
                        <div class="w-72 flex-shrink-0">
                            <div class="rounded-xl border border-gray-200 shadow-md overflow-hidden flex flex-col bg-white h-full">
                                <div class="relative h-32 bg-gradient-to-r from-blue-500 to-indigo-600">
                                    <div class="absolute -bottom-8 left-4">
                                        <div class="relative flex shrink-0 overflow-hidden rounded-full h-16 w-16 border-2 border-white bg-white">
                                            <img class="aspect-square h-full w-full" alt="<?php echo htmlspecialchars($expert['first_name'] . ' ' . $expert['last_name']); ?>" src="<?php echo $expert['profile_image'] ? htmlspecialchars($expert['profile_image']) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $expert['id']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-10 px-4 pb-4 flex-grow">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900"><?php echo htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']); ?></h3>
                                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars(substr($expert['education'], 0, 30)) . (strlen($expert['education']) > 30 ? '...' : ''); ?></p>
                                        </div>
                                        <div class="flex items-center gap-1 bg-blue-50 px-2 py-0.5 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 fill-yellow-400 text-yellow-400" viewBox="0 0 24 24">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                            <span class="text-xs font-medium"><?php echo number_format($expert['rating'], 1); ?></span>
                                        </div>
                                    </div>
                                    
                                    <h4 class="text-xs font-semibold text-gray-700 mb-1">Specialties:</h4>
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        <?php foreach (array_slice($expert_specialties, 0, 2) as $specialty): ?>
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700"><?php echo htmlspecialchars(trim($specialty)); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <div class="flex gap-2 mt-3">
                                        <a href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert['id']; ?>" class="flex-1">
                                            <button class="w-full flex items-center justify-center gap-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-1.5 px-2 rounded-lg transition-colors text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                Profile
                                            </button>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert['id']; ?>" class="flex-1">
                                            <button class="w-full flex items-center justify-center gap-1 bg-blue-600 text-white hover:bg-blue-700 font-medium py-1.5 px-2 rounded-lg transition-colors text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                                Contact
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Expert Cards - Desktop Grid -->
            <div class="hidden lg:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($featured_experts as $expert): 
                    $expert_specialties = $expert['specialties'] ? explode(', ', $expert['specialties']) : [];
                    $expert_categories = $expert['categories'] ? explode(', ', $expert['categories']) : [];
                ?>
                <!-- Expert Card - Desktop -->
                <div class="group transition-all duration-300 hover:-translate-y-1">
                    <div class="rounded-xl border border-gray-200 shadow-lg overflow-hidden flex flex-col bg-white h-full">
                        <div class="relative h-40 bg-gradient-to-r from-blue-500 to-indigo-600">
                            <div class="absolute -bottom-12 left-6">
                                <div class="relative flex shrink-0 overflow-hidden rounded-full h-24 w-24 border-4 border-white bg-white">
                                    <img class="aspect-square h-full w-full" alt="<?php echo htmlspecialchars($expert['first_name'] . ' ' . $expert['last_name']); ?>" src="<?php echo $expert['profile_image'] ? htmlspecialchars($expert['profile_image']) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $expert['id']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="pt-16 px-6 pb-6 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-xl text-gray-900"><?php echo htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']); ?></h3>
                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars(substr($expert['education'], 0, 50)) . (strlen($expert['education']) > 50 ? '...' : ''); ?></p>
                                </div>
                                <div class="flex items-center gap-1 bg-blue-50 px-2 py-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-yellow-400 text-yellow-400" viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <span class="text-sm font-medium"><?php echo number_format($expert['rating'], 1); ?></span>
                                    <span class="text-xs text-gray-500">(<?php echo $expert['total_reviews']; ?>)</span>
                                </div>
                            </div>
                            
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Specialties:</h4>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <?php foreach (array_slice($expert_specialties, 0, 3) as $specialty): ?>
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700"><?php echo htmlspecialchars(trim($specialty)); ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-6"><?php echo htmlspecialchars(substr($expert['bio'], 0, 100)) . (strlen($expert['bio']) > 100 ? '...' : ''); ?></p>
                            
                            <div class="flex gap-3">
                                <a href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert['id']; ?>" class="flex-1">
                                    <button class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Profile
                                    </button>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert['id']; ?>" class="flex-1">
                                    <button class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Contact
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- View All Button -->
            <div class="mt-8 sm:mt-12 text-center">
                <a href="<?php echo BASE_URL; ?>/experts.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-lg text-sm font-medium border border-gray-300 bg-white shadow-sm hover:bg-gray-50 text-gray-700 h-10 sm:h-11 px-5 sm:px-6 py-2 sm:py-3 transition-colors">
                    View All Experts
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
    </main>           
<!-- footer section -->
<?php siteFooter() ?>    
<script src="script/fetch.js" type="text/javascript"></script>
</body>
</html>