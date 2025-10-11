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
      <div class="relative w-full md:w-64">
        <svg class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"
             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <path d="m21 21-4.3-4.3"></path>
        </svg>
        <input class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-9 w-full"
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
</body>
</html>