<?php
session_start();
require_once('bin/page_settings.php');
require_once('bin/functions.php');
require_once('xsert.php');

// Get approved experts with their specialties and categories
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
    LIMIT 20
");
$experts_stmt->execute();
$experts = $experts_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories for filtering
$categories_stmt = $conn->prepare("SELECT * FROM expert_categories ORDER BY name");
$categories_stmt->execute();
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get featured experts (top 3)
$featured_experts = array_slice($experts, 0, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Experts - Leagile Data Research Center</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="tail.css">
    <link rel="icon" type="image/png" href="img_data/logo_fav.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet">
    <link  rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
  <div class="container mx-auto px-4 py-8">
   <!-- Featured Experts Section -->
    <section class="mb-16">
      <section class="w-full py-12 bg-slate-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-bold tracking-tight mb-2">Featured Research Experts</h2>
          <p class="text-muted-foreground max-w-2xl mx-auto">Connect with leading specialists in various research domains for personalized consultations and insights.</p>
        </div>
        
        <div class="relative">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Top Experts</h3>
            <div class="flex gap-2">
              <a href="<?php echo BASE_URL; ?>/expert-registration.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 h-9 px-4">
                Become an Expert
              </a>
            </div>
          </div>
          
          <div class="flex justify-center gap-4 overflow-hidden">
            <?php foreach ($featured_experts as $expert): 
                $expert_specialties = $expert['specialties'] ? explode(', ', $expert['specialties']) : [];
                $expert_categories = $expert['categories'] ? explode(', ', $expert['categories']) : [];
            ?>
            <!-- Expert Card -->
            <div class="transition-all duration-300 ease-in-out">
              <div class="rounded-xl border text-card-foreground shadow w-[300px] h-[320px] overflow-hidden flex flex-col bg-white">
                <div class="flex flex-col space-y-1.5 p-6 pb-2 pt-4">
                  <div class="flex items-center gap-3">
                    <span class="relative flex shrink-0 overflow-hidden rounded-full h-14 w-14 border-2 border-primary/20">
                      <img class="aspect-square h-full w-full" alt="<?php echo htmlspecialchars($expert['first_name'] . ' ' . $expert['last_name']); ?>" src="<?php echo $expert['profile_image'] ? htmlspecialchars($expert['profile_image']) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $expert['id']; ?>">
                    </span>
                    <div>
                      <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']); ?></h3>
                      <p class="text-xs text-muted-foreground line-clamp-1"><?php echo htmlspecialchars(substr($expert['education'], 0, 50)) . (strlen($expert['education']) > 50 ? '...' : ''); ?></p>
                    </div>
                  </div>
                </div>
                
                <div class="p-6 pt-0 flex-grow">
                  <div class="flex items-center gap-1 mb-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-4 w-4 <?php echo $i <= $expert['rating'] ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300'; ?>"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <?php endfor; ?>
                    <span class="text-sm font-medium"><?php echo number_format($expert['rating'], 1); ?></span>
                    <span class="text-xs text-muted-foreground">(<?php echo $expert['total_reviews']; ?> reviews)</span>
                  </div>
                  
                  <h4 class="text-sm font-medium mb-1">Specialties:</h4>
                  <div class="flex flex-wrap gap-1 mb-3">
                    <?php foreach (array_slice($expert_specialties, 0, 3) as $specialty): ?>
                      <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-xs"><?php echo htmlspecialchars(trim($specialty)); ?></div>
                    <?php endforeach; ?>
                  </div>
                  
                  <p class="text-xs text-muted-foreground"><?php echo htmlspecialchars(substr($expert['bio'], 0, 80)) . (strlen($expert['bio']) > 80 ? '...' : ''); ?></p>
                </div>
                
                <div class="items-center p-6 flex gap-2 pt-2 pb-4">
                  <a class="flex-1" href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert['id']; ?>">
                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs w-full">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-1"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                      View Profile
                    </button>
                  </a>
                  <a class="flex-1" href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert['id']; ?>">
                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs flex-1">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square h-4 w-4 mr-1"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                      Contact
                    </button>
                  </a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
            
            </div><div class="mt-8 text-center">
          <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 font-medium" onclick="document.getElementById('expert-directory').scrollIntoView()">View All Experts</button>
        </div>
            </div>
          </div>
        </div>
        
        
      </div>
    </section>
  </section>

  <!-- Expert Directory Section -->
  <section id="expert-directory">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
      <div>
        <h2 class="text-3xl font-bold mb-2">Expert Directory</h2>
        <p class="text-muted-foreground">Connect with leading specialists in various research domains for personalized consultations and insights</p>
      </div>
      
      <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <div class="relative flex-grow">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.3-4.3"></path>
          </svg>
          <input id="searchInput" class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-9 w-full" placeholder="Search experts..." onkeyup="filterExperts()">
        </div>
        
        <div class="flex gap-2">
          <select id="categoryFilter" onchange="filterExperts()" class="flex h-9 items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 w-[150px]">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?php echo htmlspecialchars($category['name']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
          </select>
          
          <select id="sortFilter" onchange="sortExperts()" class="flex h-9 items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 w-[130px]">
            <option value="rating">Top Rated</option>
            <option value="reviews">Most Reviews</option>
            <option value="experience">Experience</option>
            <option value="rate_low">Price: Low to High</option>
            <option value="rate_high">Price: High to Low</option>
          </select>
        </div>
      </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="expertsGrid">
      <?php foreach ($experts as $expert): 
          $expert_specialties = $expert['specialties'] ? explode(', ', $expert['specialties']) : [];
          $expert_categories = $expert['categories'] ? explode(', ', $expert['categories']) : [];
      ?>
        <!-- Expert Grid Item -->
        <div class="expert-card rounded-xl border text-card-foreground shadow w-full h-[320px] overflow-hidden flex flex-col bg-white" 
             data-name="<?php echo htmlspecialchars(strtolower($expert['first_name'] . ' ' . $expert['last_name'])); ?>"
             data-specialties="<?php echo htmlspecialchars(strtolower($expert['specialties'] ?? '')); ?>"
             data-categories="<?php echo htmlspecialchars(strtolower($expert['categories'] ?? '')); ?>"
             data-rating="<?php echo $expert['rating']; ?>"
             data-reviews="<?php echo $expert['total_reviews']; ?>"
             data-experience="<?php echo $expert['experience_years']; ?>"
             data-rate="<?php echo $expert['hourly_rate']; ?>">
          <div class="flex flex-col space-y-1.5 p-6 pb-2 pt-4">
            <div class="flex items-center gap-3">
              <span class="relative flex shrink-0 overflow-hidden rounded-full h-14 w-14 border-2 border-primary/20">
                <img class="aspect-square h-full w-full" alt="<?php echo htmlspecialchars($expert['first_name'] . ' ' . $expert['last_name']); ?>" src="<?php echo $expert['profile_image'] ? htmlspecialchars($expert['profile_image']) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $expert['id']; ?>">
              </span>
              <div>
                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($expert['title'] . ' ' . $expert['first_name'] . ' ' . $expert['last_name']); ?></h3>
                <p class="text-xs text-muted-foreground line-clamp-1"><?php echo htmlspecialchars(substr($expert['education'], 0, 40)) . (strlen($expert['education']) > 40 ? '...' : ''); ?></p>
              </div>
            </div>
          </div>
          
          <div class="p-6 pt-0 flex-grow">
            <div class="flex items-center gap-1 mb-2">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-4 w-4 <?php echo $i <= $expert['rating'] ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300'; ?>"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
              <?php endfor; ?>
              <span class="text-sm font-medium"><?php echo number_format($expert['rating'], 1); ?></span>
              <span class="text-xs text-muted-foreground">(<?php echo $expert['total_reviews']; ?> reviews)</span>
            </div>
            
            <h4 class="text-sm font-medium mb-1">Specialties:</h4>
            <div class="flex flex-wrap gap-1 mb-3">
              <?php foreach (array_slice($expert_specialties, 0, 3) as $specialty): ?>
                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-xs"><?php echo htmlspecialchars(trim($specialty)); ?></div>
              <?php endforeach; ?>
            </div>
            
            <p class="text-xs text-muted-foreground mb-2"><?php echo htmlspecialchars(substr($expert['bio'], 0, 60)) . (strlen($expert['bio']) > 60 ? '...' : ''); ?></p>
            <p class="text-sm font-semibold text-green-600">$<?php echo number_format($expert['hourly_rate'], 2); ?>/hour</p>
          </div>
          
          <div class="items-center p-6 flex gap-2 pt-2 pb-4">
            <a class="flex-1" href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert['id']; ?>">
              <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs w-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-1"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                View Profile
              </button>
            </a>
            <a class="flex-1" href="<?php echo BASE_URL; ?>/expert-profile.php?id=<?php echo $expert['id']; ?>">
              <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square h-4 w-4 mr-1"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                Contact
              </button>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <?php if (empty($experts)): ?>
      <div class="text-center py-12">
        <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Experts Available</h3>
        <p class="text-gray-500 mb-6">Be the first to join our expert network!</p>
        <a href="<?php echo BASE_URL; ?>/expert-registration.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 h-10 px-6">
          Become an Expert
        </a>
      </div>
    <?php endif; ?>
  </section>
  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>

<script>
function filterExperts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const cards = document.querySelectorAll('.expert-card');
    
    cards.forEach(card => {
        const name = card.dataset.name;
        const specialties = card.dataset.specialties;
        const categories = card.dataset.categories;
        
        const matchesSearch = !searchTerm || 
            name.includes(searchTerm) || 
            specialties.includes(searchTerm) || 
            categories.includes(searchTerm);
            
        const matchesCategory = !categoryFilter || categories.includes(categoryFilter);
        
        if (matchesSearch && matchesCategory) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function sortExperts() {
    const sortBy = document.getElementById('sortFilter').value;
    const grid = document.getElementById('expertsGrid');
    const cards = Array.from(document.querySelectorAll('.expert-card'));
    
    cards.sort((a, b) => {
        switch (sortBy) {
            case 'rating':
                return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            case 'reviews':
                return parseInt(b.dataset.reviews) - parseInt(a.dataset.reviews);
            case 'experience':
                return parseInt(b.dataset.experience) - parseInt(a.dataset.experience);
            case 'rate_low':
                return parseFloat(a.dataset.rate) - parseFloat(b.dataset.rate);
            case 'rate_high':
                return parseFloat(b.dataset.rate) - parseFloat(a.dataset.rate);
            default:
                return 0;
        }
    });
    
    // Clear and re-append sorted cards
    grid.innerHTML = '';
    cards.forEach(card => grid.appendChild(card));
}
</script>
</body>
</html>