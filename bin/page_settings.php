<?php

function siteHeader(){
   // Redirect if already logged in
 $user_id = $_SESSION['user_id'] ?? '';
 $user_name = $_SESSION['user_name'] ?? '';
 $user_email = $_SESSION['user_email'] ?? '';
 $url = isset($_SESSION['admin_usr']) ? '/admin/dashboard.php' : '/dash/dash.php';
 $downloads = isset($_SESSION['admin_usr']) ? '/admin/downloads_mgmt.php' : '/dash/downloads_report.php';
  ?>
<!-- Main Navigation -->
    <nav class="bg-gray-900 text-white sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center justify-between py-3">
                <div class="flex items-center flex-shrink-0 mr-4">
                    <a href="<?php echo BASE_URL ?>/index.php" class="flex items-center">
                        <img src="<?php echo BASE_URL ?>/img_data/logo.jpg" alt="Research Center Logo" class="h-10 w-auto mr-3 rounded">
                        <span class="text-xl font-bold" style="color: rgb(234 179 8 / var(--tw-bg-opacity, 1));">Leagile Data Research Center</span>
                    </a>
                </div>
                
                <!-- Search Bar -->
                <div class="flex flex-1 max-w-2xl mx-4">
                    <div class="flex w-full">
                        <select class="bg-gray-100 text-gray-800 border-r-0 rounded-l-md px-3 py-2 text-sm border-gray-300 focus:outline-none focus:ring-1 focus:ring-yellow-500">
                            <option>All Categories</option>
                            <option>Technology</option>
                            <option>Healthcare</option>
                            <option>Finance</option>
                            <option>Energy</option>
                        </select>
                        <input type="text" class="flex-grow px-4 py-2 border-t border-b border-gray-300 focus:outline-none focus:ring-1 focus:ring-yellow-500"  placeholder="Search research reports...">
                        <button class="bg-yellow-400 hover:bg-yellow-500 px-4 rounded-r-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Account + Cart -->
                <div class="flex items-center space-x-4 ml-4">
                   <?php if(!$user_name){ ?>
                     <div class="hidden md:flex items-center space-x-2">
                        <a href="<?php echo BASE_URL ?>/login.php?tab=register" class="px-3 py-1.5 rounded text-sm hover:underline">Register</a>
                        <span class="text-gray-400">|</span>
                        <a href="<?php echo BASE_URL ?>/login.php?tab=login" class="px-3 py-1.5 rounded text-sm font-medium bg-yellow-400 text-gray-900 hover:bg-yellow-500">Sign In</a>
                    </div>
                <?php } else { ?>
        <div class="relative dropdown group ">
          <button id="userMenuBtn" class="flex items-center space-x-1 focus:outline-none">
            <span class="text-sm font-medium">Hi, <span class="text-yellow-300"><?php echo htmlspecialchars($user_name); ?></span></span>
            <svg class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
           <!-- Dropdown Menu -->
           <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
            <a href="<?php echo BASE_URL . $url ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-user-circle mr-2"></i> My Account</a>
            <a href="<?php echo BASE_URL .  $downloads ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-file-download mr-2"></i>Downloads</a>
            <!--<a href="/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-shopping-bag mr-2"></i> My Orders</a>
                <div class="border-t border-gray-200"></div>-->
            <a href="<?php echo BASE_URL ?>/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-sign-out-alt mr-2"></i> Sign Out</a>
           </div>          
         </div>
           <?php } ?>
            <div class="relative">
               <a href="<?php echo BASE_URL ?>/cart.php" class="p-1 rounded hover:bg-gray-800 flex items-center">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                      </svg>
                 <span class="cart-count  cart-count-hidden absolute -top-1 -right-1 bg-yellow-400 text-gray-900 rounded-full h-5 w-5 flex items-center justify-center text-xs font-bold" data-count="0"></span>
               </a>
            </div>
        </div>
    </div>
            <!-- Mobile Navigation -->
            <div class="md:hidden">
                <!-- Top Bar -->
                <div class="flex items-center justify-between py-3">
                    <!-- Logo -->
                <div class="flex items-center flex-shrink-0 mr-4">
                    <a href="<?php echo BASE_URL ?>/index.php" class="flex items-center">
                        <img src="<?php echo BASE_URL ?>/img_data/logo.jpg" alt="Research Center Logo" class="h-10 w-auto mr-3 rounded">
                        <!--<span class="text-xl font-bold" style="color: rgb(234 179 8 / var(--tw-bg-opacity, 1));">Leagile Data Research Center</span>-->
                    </a>
                </div>
                    
                    <!-- Menu Button + Cart -->
                    <div class="flex items-center space-x-4">
                        <button id="mobileMenuButton" class="text-white focus:outline-none">
                            <svg id="menuIcon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>                        
                        <div class="relative">
                            <a href="<?php echo BASE_URL ?>/cart.php" class="p-1 rounded hover:bg-gray-800 flex items-center">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                             <span id="" class="cart-count cart-count-hidden absolute -top-1 -right-1 bg-yellow-400 text-gray-900 rounded-full h-5 w-5 flex items-center justify-center text-xs font-bold" data-count="0"></span>
                            </a>
                       </div>
                    </div>
                </div>
                
                <!-- Mobile Search -->
                <div class="pb-3">
                    <div class="flex w-full">
                        <input type="text" class="flex-grow px-4 py-2 rounded-l-md border-t border-b border-l border-gray-300 focus:outline-none focus:ring-1 focus:ring-yellow-500" placeholder="Search research reports...">
                        <button class="bg-yellow-400 hover:bg-yellow-500 px-4 rounded-r-md">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Menu Content -->
                <div id="mobileMenu" class="mobile-menu bg-gray-800">
                    <div class="px-2 pt-2 pb-4 space-y-1">
                      <?php if(!$user_name){ ?>
                        <!-- Account Links -->
                        <div class="flex space-x-2 px-3 py-2">
                            <a href="<?php echo BASE_URL ?>/login.php?tab=register" class="flex-1 text-center text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium border border-gray-600">Register</a>
                            <a href="<?php echo BASE_URL ?>/login.php?tab=login" class="flex-1 text-center bg-yellow-400 text-gray-900 hover:bg-yellow-500 block px-3 py-2 rounded-md text-sm font-medium">Sign In</a>
                        </div>
                    <?php } else { ?>
                        <!-- User Info -->
        <div class="px-3 py-2 border-b border-gray-700">
            <p class="text-white font-medium">Hi, <span class="text-yellow-300"><?php echo htmlspecialchars($user_name); ?></span></p>
        </div>
        <?php } ?> 
        <!-- User Links -->
        <div class="px-3 py-2 border-b border-gray-700">
            <a href="<?php echo BASE_URL ?>/" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">
             <i class="fas fa-home mr-2"></i> Home</a>
           <a href="<?php echo BASE_URL . $url ?>" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">
             <i class="fas fa-user-circle mr-2"></i> My Account</a>
           <a href="<?php echo BASE_URL ?>/dash/downloads_report.php" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">
             <i class="fas fa-file-download mr-2"></i>My Downloads</a>
           <a href="<?php echo BASE_URL ?>/categories.php" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">
             <i class="fas fa-th-list mr-2"></i> All Categories</a>
        </div>
                    
        <!-- Navigation Links -->
        <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">New Releases</a>
        <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">Premium Datasets</a>
        <a href="<?php echo BASE_URL ?>/services.php" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">Our Services</a>
        <a href="<?php echo BASE_URL ?>/subscriptions.php" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">Subscription Plans</a>
        <a href="<?php echo BASE_URL ?>/expert.php" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">Experts</a>
        <a href="<?php echo BASE_URL ?>/donate.php" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">Donation</a>
        <!-- Sign Out Button - Now Properly Included -->
            <div class="border-t border-gray-700 pt-2">
                <a href="<?php echo BASE_URL ?>/logout.php" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-sm font-medium">
                 <i class="fas fa-sign-out-alt mr-2"></i> Sign Out</a>
            </div>
         
                    </div>
                </div>
            </div>
        </div>
        </div>
    </nav>
<!-- Secondary Navigation (Desktop only) -->
<div class="bg-gray-800 text-white hidden md:block">
    <div class="container mx-auto px-4">
        <div class="flex space-x-6 py-2">
              <a href="<?php echo BASE_URL ?>/index.php" class="flex items-center text-sm font-medium hover:text-yellow-200 whitespace-nowrap py-2">
               <i class="fas fa-home mr-2"></i> Home
              </a>
            <a href="<?php echo BASE_URL ?>/categories.php" class="text-sm font-medium hover:text-yellow-300 whitespace-nowrap py-2">All Categories</a>
            <a href="#" class="text-sm font-medium hover:text-yellow-300 whitespace-nowrap py-2">Trending</a>
            <a href="<?php echo BASE_URL ?>/services.php" class="text-sm font-medium hover:text-yellow-300 whitespace-nowrap py-2">Our Services</a>
            <a href="<?php echo BASE_URL ?>/subscriptions.php" class="text-sm font-medium hover:text-yellow-300 whitespace-nowrap py-2">Subscription Plans</a>
            <a href="<?php echo BASE_URL ?>/experts.php" class="text-sm font-medium hover:text-yellow-300 whitespace-nowrap py-2">Experts</a>
            <a href="<?php echo BASE_URL ?>/donate.php" class="text-sm font-medium hover:text-yellow-300 whitespace-nowrap py-2">Donation</a>
        </div>
    </div>
 </div>
<?php
}

function siteHeader_old(){ 
 // Redirect if already logged in
 $user_id = $_SESSION['user_id'] ?? '';
 $user_name = $_SESSION['user_name'] ?? '';
 $user_email = $_SESSION['user_email'] ?? '';
?>
<header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 text-[#333]">
  <div class="container flex h-20 items-center justify-between px-4 md:px-6">

    <!-- Logo -->
    <a class="flex items-center space-x-2" href="<?php echo BASE_URL ?>/index.php">
      <div class="h-8 w-14 flex items-center justify-center">
        <img src="img_data/logo.jpg" alt="Research Center Logo" class="h-10 w-auto">
      </div>
      <div class="hidden md:flex flex-col">
        <span class="font-bold text-xl">Leagile Research Data Center</span>
        <span class="text-muted-slogan">The source of research Data</span>
      </div>
    </a>

    <!-- Mobile Menu Toggle -->
    <button id="menuToggleBtn" type="button"
      class="inline-flex items-center justify-center h-9 w-9 rounded-md md:hidden hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-1 focus:ring-ring">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="2">
        <line x1="4" x2="20" y1="6" y2="6"></line>
        <line x1="4" x2="20" y1="12" y2="12"></line>
        <line x1="4" x2="20" y1="18" y2="18"></line>
      </svg>
    </button>

    <!-- Desktop Navigation -->
    <nav class="hidden md:flex items-center space-x-6">
      <a href="index.php" class="text-sm font-medium hover:text-primary transition-colors">Home</a>
      <a href="categories.php" class="text-sm font-medium hover:text-primary transition-colors">Categories</a>
      <a href="experts.php" class="text-sm font-medium hover:text-primary transition-colors">Experts</a>
      <a href="subscriptions.php" class="text-sm font-medium hover:text-primary transition-colors">Subscription Plans</a>
      <a href="<?php echo BASE_URL ?>/donate.php" class="text-sm font-medium hover:text-primary transition-colors">Donation</a>
      <a href="services.php" class="text-sm font-medium hover:text-primary transition-colors">Services</a>
    </nav>

    <!-- Actions -->
    <div class="flex items-center space-x-4">
      <!-- Cart -->
      <a href="<?php echo BASE_URL ?>/cart.php" class="relative">
        <button
          class="inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-1 focus:ring-ring">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
            <path d="M3 6h18" />
            <path d="M16 10a4 4 0 0 1-8 0" />
          </svg>
          <div id="cart-count" class="rounded-md border font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-destructive text-destructive-foreground shadow hover:bg-destructive/80 absolute -top-2 -right-2 h-5 w-5 flex items-center justify-center p-0 text-xs"></div>
        </button>
      </a>
      <?php if($user_name){ ?>
            <div class="flex items-center space-x-4">
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
      <?php } else { ?>
      <!-- Desktop Auth -->
      <div class="hidden md:flex items-center space-x-2">
        <a href="<?php echo BASE_URL ?>/login.php?tab=login">
          <button class="items-center justify-center h-8 px-3 rounded-md text-xs font-medium hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-1 focus:ring-ring">Login</button>
        </a>
        <a href="<?php echo BASE_URL ?>/login.php?tab=register">
          <button class="items-center justify-center h-8 px-3 rounded-md text-xs font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 focus:outline-none focus:ring-1 focus:ring-ring">Register</button>
        </a>
      </div>

     <!--Mobile Auth Toggle-->
      <div class="relative md:hidden">
        <button id="authToggleBtn" type="button" class="inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-1 focus:ring-ring">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
        </button>

      <!--Auth Dropdown--> 
        <div id="authDropdown"
          class="hidden absolute right-0 mt-2 w-40 bg-background border rounded-md shadow-md p-2 z-50">
          <a href="<?php echo BASE_URL ?>/login" class="block w-full px-4 py-2 text-sm hover:bg-accent hover:text-accent-foreground rounded-md">Login</a>
          <a href="<?php echo BASE_URL ?>/register" class="block w-full px-4 py-2 text-sm bg-primary text-primary-foreground hover:bg-primary/90 rounded-md text-center">Register</a>
        </div>
      </div>
    <?php } ?>
        </div>
    </div>

  </div>
</header>

<!-- Mobile Menu -->
<div id="mobileMenu" class="hidden md:hidden fixed top-20 inset-x-0 bg-background z-40 border-t shadow-md p-4 space-y-3">
  <nav class="flex flex-col space-y-2">
    <a href="index.php" class="text-sm font-medium hover:text-primary transition-colors">Home</a>
    <a href="categories.php" class="text-sm font-medium hover:text-primary transition-colors">Categories</a>
    <a href="experts.php" class="text-sm font-medium hover:text-primary transition-colors">Experts</a>
    <a href="subscriptions.php" class="text-sm font-medium hover:text-primary transition-colors">Subscription Plans</a>
    <a href="<?php echo BASE_URL ?>/donate.php" class="text-sm font-medium hover:text-primary transition-colors">Donation</a>
    <a href="services.php" class="text-sm font-medium hover:text-primary transition-colors">Services</a>
  </nav>
</div>

<!-- JS: Menu + Auth Dropdown Toggle -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
  // Element references
  const menuToggleBtn = document.getElementById('menuToggleBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const authToggleBtn = document.getElementById('authToggleBtn');
  const authDropdown = document.getElementById('authDropdown');
  const userMenuBtn = document.getElementById('userMenuBtn');
  const userDropdown = document.getElementById('userDropdown');
  const carousel = document.getElementById('reportCarousel');

  // Nav menu toggle
  if (menuToggleBtn && mobileMenu) {
    menuToggleBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  // Auth dropdown toggle
  if (authToggleBtn && authDropdown) {
    authToggleBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      authDropdown.classList.toggle('hidden');
    });
  }

  // User dropdown toggle
  if (userMenuBtn && userDropdown) {
    userMenuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      userDropdown.classList.toggle('hidden');
    });
  }

  // Unified outside click handler to close all dropdowns/menus
  document.addEventListener('click', (e) => {
    if (mobileMenu && !mobileMenu.contains(e.target) && !menuToggleBtn.contains(e.target)) {
      mobileMenu.classList.add('hidden');
    }
    if (authDropdown && !authDropdown.contains(e.target) && !authToggleBtn.contains(e.target)) {
      authDropdown.classList.add('hidden');
    }
    if (userDropdown && !userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
      userDropdown.classList.add('hidden');
    }
  });

  // Optional: define scroll amount for carousel
  if (carousel) {
    const scrollAmount = () => carousel.offsetWidth * 0.9;
    // You can now use `scrollAmount()` when attaching scroll buttons
  }
});

</script>



<?php } 
function siteFooter(){
?>
<footer class="bg-slate-900 text-white py-12 w-full">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-10 text-left">

      <!-- Logo and Description -->
      <div>
        <div class="flex items-center gap-3 mb-4">
          <img src="<?php echo BASE_URL ?>/img_data/logo.jpg" alt="Research Center Logo" class="h-10 w-auto mr-3 rounded bg-white p-1">
          <h3 class="text-xl font-bold leading-tight">Leagile Data <br> Research Center</h3>
        </div>
        <p class="text-slate-300 mb-4">
          Your trusted platform for expert research and consultation services.
        </p>
        <div class="flex space-x-4 text-slate-300">
          <a href="#" class="hover:text-white"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="hover:text-white"><i class="fab fa-twitter"></i></a>
          <a href="#" class="hover:text-white"><i class="fab fa-linkedin-in"></i></a>
          <a href="#" class="hover:text-white"><i class="fab fa-instagram"></i></a>
          <a href="#" class="hover:text-white"><i class="fab fa-github"></i></a>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
        <ul class="space-y-2 text-slate-300">
          <li><a href="#" class="hover:text-white">Home</a></li>
          <li><a href="#" class="hover:text-white">Browse Reports</a></li>
          <li><a href="#" class="hover:text-white">Subscription Plans</a></li>
          <li><a href="#" class="hover:text-white">Expert Directory</a></li>
          <li><a href="#" class="hover:text-white">About Us</a></li>
        </ul>
      </div>

      <!-- Research Categories -->
      <div>
        <h3 class="text-lg font-semibold mb-4">Research Categories</h3>
        <ul class="space-y-2 text-slate-300">
          <li><a href="#" class="hover:text-white">Business & Finance</a></li>
          <li><a href="#" class="hover:text-white">Technology & Innovation</a></li>
          <li><a href="#" class="hover:text-white">Healthcare & Medicine</a></li>
          <li><a href="#" class="hover:text-white">Environmental Studies</a></li>
          <li><a href="#" class="hover:text-white">Social Sciences</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div>
        <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
        <ul class="space-y-3 text-slate-300">
          <li class="flex items-start gap-2">
            <i class="fas fa-map-marker-alt text-slate-400 mt-1"></i>
            <span>Kampala, Uganda</span>
          </li>
          <li class="flex items-center gap-2">
            <i class="fas fa-phone text-slate-400"></i>
            <span>+256 701 339677</span>
          </li>
          <li class="flex items-center gap-2">
            <i class="fas fa-envelope text-slate-400"></i>
            <span>info@leagileresearch.com</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Divider -->
    <div class="my-8 h-px bg-slate-700 w-full"></div>

    <!-- Bottom Bar -->
    <div class="text-sm text-slate-400">
      <p>&copy; 2025 Leagile Research Data Center. All rights reserved.</p>
      <div class="mt-2">
        <a href="#" class="hover:text-white">Privacy Policy</a>
        <span class="mx-2">|</span>
        <a href="#" class="hover:text-white">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>
<script src="<?php echo BASE_URL ?>/script/menuScript.js"></script>
<script src="<?php echo BASE_URL ?>/script/src_script.js"></script>


<!--Modal popup-->

<div class="success-popup" id="popup">
        <div class="popup-content pop-width-small">
            <h2>Registration Successful!</h2>
            <p>We've sent a verification email to your inbox. Please check your email to verify your account.</p>
            <button id="popup-close" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium popup-content-button">OK</button>
      </div>
</div>

<div class="error-popup" id="error-popup">
  <div class="popup-content">
            <h2>Registration Failed</h2>
            <p id="error-message-text">An error occurred during registration.</p>
            <button id="error-popup-close" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium">OK</button>
 </div>
</div>

<div class="success-popup " id="success-popup">
        <div class="popup-content pop-width-large" id="popup-content">
           <!-- <div class="rounded-xl shadow-lg bg-white border border-gray-200  mx-auto mt-10">
   Header -->
  <div class="text-center p-6 border-b">
    <h3 class="text-2xl font-bold">Login to Continue</h3>
  </div>
  <!-- Tabs -->
  <div class="p-6 pt-0">
    <div class="grid grid-cols-2 bg-gray-100 rounded-lg overflow-hidden mb-6">
      <button class="py-2 text-sm font-medium bg-white text-black shadow" id="tab-login" onclick="switchTab('login')">Login</button>
      <button class="py-2 text-sm font-medium text-gray-500 hover:text-black" id="tab-register" onclick="switchTab('register')">Register</button>
    </div>

    <!-- Login Panel -->
    <div id="login-panel">
        <!-- Error message -->
          <?php if (!empty($error)): ?>
              <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
              </div>
        <?php endif; ?>
      <form method="POST" action="login.php" class="space-y-4" id="login-form">
        <div>
          <label for="login-email" class="text-sm font-medium">Email</label>
          <input type="email" id="login-email" name="email" required placeholder="name@example.com"
            class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" />
        </div>
        <div>
          <label for="login-password" class="text-sm font-medium">Password</label>
          <input type="password" id="login-password" name="password" required placeholder="********"
            class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" />
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium" id="login-button">Sign in</button>
      </form>

      <!-- Divider 
                <div class="my-6">
                    <div class="flex items-center justify-center">
                        <span class="w-full border-t"></span>
                        <span class="px-2 text-xs text-gray-400">Or continue with</span>
                        <span class="w-full border-t"></span>
                    </div>
                    <button disabled class="w-full mt-4 border px-4 py-2 rounded-md text-sm text-gray-500 hover:bg-gray-50">
                        Google
                    </button>
                </div>-->
    </div>

    <!-- Register Panel -->
    <div id="register-panel" class="hidden">
      <form id="registration-form" class="space-y-4">
                <div>
                 <label for="reg-name" class="text-sm font-medium">User Name</label>
                 <input type="text" id="reg-name" name="name" placeholder="John Doe"  class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" required/>
                 <div class="error-message" id="name-error">Please enter your full name</div>
                </div>
                <div>
                    <label for="reg-email" class="text-sm font-medium">Email</label>
                    <input type="email" id="reg-email" name="email" placeholder="you@example.com" class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" required>
                    <div class="error-message" id="email-error">Please enter a valid email address</div>
                </div>
                
                <div>
                    <label for="reg-password" class="text-sm font-medium">Password</label>
                    <input type="password" id="reg-password" name="password" placeholder="••••••••" class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" required />
                    <div class="password-strength">
                        <div class="strength-meter" id="strength-meter"></div>
                    </div>
                    <div class="strength-text" id="strength-text">Password strength</div>
                    <div class="error-message" id="password-error">Password must be at least 8 characters</div>
                </div>
                <div>
                    <label for="reg-confirm-password" class="text-sm font-medium">Confirm Password</label>
                    <input type="password" id="reg-confirm-password" name="confirmPassword" class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" required>
                    <div class="password-match" id="password-match">
                        <span class="match">Passwords match</span>
                        <span class="no-match">Passwords do not match</span>
                    </div>
                </div>
        <div class="flex items-start space-x-2">
          <input type="checkbox" id="agree" required class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="agree" class="text-sm text-gray-600">
            I agree to the <a href="#" class="underline text-blue-600">terms of service</a> and
            <a href="#" class="underline text-blue-600">privacy policy</a>.
          </label>
        </div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium" id="submit-button">
           <span id="button-text">Create account</span>
          <div class="loader" id="loader"></div>
        </button>
      </form>
  </div> 
</div>
      </div>
</div>
<?php } ?>