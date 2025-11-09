<?php
session_start();
require_once('bin/page_settings.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('bin/source_links.php'); ?>
</head>
<body>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
  <div class="container mx-auto px-4 py-8">
    
    <!-- Header + Button + Search -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
      
      <!-- Title and Add File Button -->
      <div>
        <h1 class="text-3xl font-bold mb-2">All Your Research Datasets</h1>
        <p class="text-muted-foreground">
          Browse our comprehensive collection of research file by category
        </p>
        <?php if(isset($_SESSION['admin_usr'])){ ?>
        <div class="mt-4">
          <a href="add_file.php">
            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary-foreground shadow h-9 px-4 py-2 bg-primary hover:bg-primary/90">
              Add New File
            </button>
          </a>
        </div>
        <?php } ?>
      </div>

      <!-- Search Bar -->
      <div class="relative w-full md:w-96">
        <svg class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"
             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <path d="m21 21-4.3-4.3"></path>
        </svg>
        <input id="category-search-input" class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-9 w-full"
               placeholder="Search categories..." value="">
      </div>

    </div>

    <section class="mb-6 sm:mb-8">
            <div id="report-cards" class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-4">
                <!-- Research report card items -->
            </div>
        </section>
  </div>
</main>

<!-- footer section -->
<?php siteFooter() ?>
    
<script src="script/fetch.js" type="text/javascript"></script>
<script>
// Category page search functionality
document.addEventListener('DOMContentLoaded', () => {
    const categorySearchInput = document.getElementById('category-search-input');
    
    // Check for URL parameters on page load
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    const categoryParam = urlParams.get('category');
    
    // Set input value if search parameter exists
    if (searchParam && categorySearchInput) {
        categorySearchInput.value = searchParam;
    }
    
    // Perform initial search if parameters exist
    if (searchParam || categoryParam) {
        searchReportsInCategories(searchParam || '', categoryParam || '');
    }
    
    // Add input event listener for real-time search
    if (categorySearchInput) {
        let searchTimeout;
        categorySearchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchReportsInCategories(e.target.value);
            }, 300);
        });
    }
});

async function searchReportsInCategories(searchQuery = '', category = '') {
    try {
        let url = 'fetch/search_reports.php';
        const params = new URLSearchParams();
        
        if (searchQuery) params.append('search', searchQuery);
        if (category) params.append('category', category);
        
        if (params.toString()) {
            url += '?' + params.toString();
        } else {
            url = 'fetch/get_reports.php';
        }
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const reports = await response.json();
        const container = document.getElementById('report-cards');
        container.innerHTML = '';
        
        if (reports.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">No reports found matching your search.</div>';
            return;
        }
        
        reports.forEach(report => {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300';
            card.innerHTML = `
                <a href="report-details.php?id=${report.id}">
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img src="${report.thumbnail || 'img_data/logo.jpg'}" alt="${report.title}" class="w-full h-48 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 line-clamp-2">${report.title}</h3>
                        <p class="text-sm text-gray-600 mb-2">${report.author || 'Unknown Author'}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-600">${report.category || 'Uncategorized'}</span>
                            <span class="text-sm text-gray-500">${report.download_count || 0} downloads</span>
                        </div>
                        <div class="mt-3">
                            <span class="text-lg font-bold text-green-600">UGX ${Number(report.price || 0).toLocaleString()}</span>
                        </div>
                    </div>
                </a>
            `;
            container.appendChild(card);
        });
    } catch (error) {
        console.error('Search error:', error);
        const container = document.getElementById('report-cards');
        container.innerHTML = '<div class="col-span-full text-center py-8 text-red-500">Error loading reports. Please try again.</div>';
    }
}
</script>
</body>
</html>