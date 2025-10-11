<?php
session_start();
require_once('bin/page_settings.php');
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leagile Data Research Center</title>
    <link rel="icon" type="image/png" href="img_data/logo_fav.png" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="tail.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet">
    <link  rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
 <!-- Header -->
 <?php siteHeader() ?>
<div class="relative w-full h-[400px] bg-slate-100 overflow-hidden">
  <!-- Background Image -->
  <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 transform scale-105" 
       style="background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1200&q=80');">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/60"></div>
  </div>

  <!-- Content Container -->
  <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4 md:px-6 lg:px-8">
    <div class="max-w-2xl text-white">
     <div class="max-w-2xl text-white">
  <div class="overflow-hidden min-h-[3.5rem] md:min-h-[4rem] lg:min-h-[4.5rem] mb-4" data-aos="fade-up">
    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold">
      Obtain accurate data requirement
    </h1>
  </div>
  <div class="overflow-hidden min-h-[4rem] md:min-h-[5rem] mb-8" data-aos="fade-up" data-aos-delay="100">
    <p class="text-lg md:text-xl">
      Get precisely the data you need with our customizable research solutions. Our experts ensure you receive accurate, relevant information tailored to your specific requirements.
    </p>
  </div>
  <div class="flex flex-col sm:flex-row gap-4">
  <!-- Search Input with Button -->
  <div class="relative w-full max-w-md">
    <input
      type="search"
      class="flex w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 h-11 pl-4 pr-10 bg-white/10 border-white text-white placeholder:text-white/70 focus:bg-white/20"
      placeholder="Search for reports..."
    >
    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground absolute right-1 top-1/2 -translate-y-1/2 text-white h-9 w-9">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
        viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-search h-5 w-5">
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.3-4.3"></path>
      </svg>
    </button>
  </div>

  <!-- Browse Button -->
  <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary-foreground shadow h-10 rounded-md px-8 bg-primary hover:bg-primary/90">Browse Reports</button>
</div>

     </div>
   </div>
 </div>
</div>

<section class="w-full py-8 bg-gradient-to-b from-slate-900 to-slate-800 text-white">
  <div class="container mx-auto px-4">
    <!-- Carousel Navigation & Title -->
    <div class="relative">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold tracking-tight mb-2">Featured Reports</h2>
    <div class="flex gap-2">
      <button id="scrollLeftBtn" class="inline-flex items-center justify-center h-9 w-9 rounded-md text-sm font-medium border bg-background shadow-sm hover:bg-accent hover:text-accent-foreground disabled:pointer-events-none disabled:opacity-50" aria-label="Previous reports">
        <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-left h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path d="m15 18-6-6 6-6"></path>
        </svg>
      </button>
      <button id="scrollRightBtn" class="inline-flex items-center justify-center h-9 w-9 rounded-md text-sm font-medium border bg-background shadow-sm hover:bg-accent hover:text-accent-foreground disabled:pointer-events-none disabled:opacity-50" aria-label="Next reports">
        <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-right h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path d="m9 18 6-6-6-6"></path>
        </svg>
      </button>
    </div>
  </div>
  <div id="report-cards" class="flex gap-4 overflow-x-auto scroll-smooth"></div>
</div>
  </div>
</section>


<style>
.button-nav {
  @apply inline-flex items-center justify-center rounded-md text-sm font-medium border bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9;
}
.icon-chevron-left, .icon-chevron-right {
  @apply w-4 h-4;
}
.card {
  @apply rounded-xl border text-card-foreground shadow w-full max-w-[350px] h-[380px] flex flex-col overflow-hidden bg-white transition-all duration-300 ease-in-out;
}
.card-image {
  @apply relative h-40 overflow-hidden;
}
.card-image img {
  @apply w-full h-full object-cover transition-transform hover:scale-105;
}
.badge {
  @apply absolute top-2 right-2 bg-primary text-white text-xs font-bold px-2 py-1 rounded;
}
.card-body {
  @apply flex flex-col space-y-1.5 p-4 pb-0;
}
.card-title {
  @apply tracking-tight text-lg font-bold line-clamp-1;
}
.card-author {
  @apply text-sm text-gray-600 mt-1;
}
.card-description {
  @apply text-muted-foreground text-sm line-clamp-3;
}
.card-rating {
  @apply mt-2 flex items-center gap-1;
}
.stars {
  @apply text-yellow-400 text-sm;
}
.card-footer {
  @apply p-4 pt-0 flex justify-between items-center;
}
.price {
  @apply font-bold text-lg;
}
.actions {
  @apply flex gap-2;
}
.button-download, .button-add {
  @apply justify-center font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring border bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs flex items-center gap-1;
}
.button-add {
  @apply bg-primary text-primary-foreground hover:bg-primary/90;
}
</style>
<div class="w-full bg-gray-50 py-12">
  <div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-2xl font-bold">Browse by Category</h2>
        <p class="text-sm text-muted-foreground mt-1">
          Explore our research reports by specialized domains
        </p>
      </div>
      <div class="flex gap-2">
        <!-- Left Scroll Button -->
        <button 
          class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9 opacity-50 cursor-not-allowed" 
          aria-label="Scroll left" 
          disabled
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-left h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="m15 18-6-6 6-6"></path>
          </svg>
        </button>

        <!-- Right Scroll Button -->
        <button 
          class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9" 
          aria-label="Scroll right"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-right h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="m9 18 6-6-6-6"></path>
          </svg>
        </button>
      </div>
    </div>

    <div class="flex space-x-4 pb-4 px-1 overflow-x-auto snap-x snap-mandatory" style="scroll-behavior: smooth; scrollbar-width: none;">
  
  <!-- Academic Research -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-book-open" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Academic Research</span>
  </button>

  <!-- Educational -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-graduation-cap" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path>
        <path d="M22 10v6"></path>
        <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Educational</span>
  </button>

  <!-- Scientific Studies -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-microscope" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 18h8"></path>
        <path d="M3 22h18"></path>
        <path d="M14 22a7 7 0 1 0 0-14h-1"></path>
        <path d="M9 14h2"></path>
        <path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"></path>
        <path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Scientific Studies</span>
  </button>

  <!-- Healthcare -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-heart-pulse" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
        <path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Healthcare</span>
  </button>

  <!-- Market Analysis -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-line-chart" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 3v18h18"></path>
        <path d="m19 9-5 5-4-4-3 3"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Market Analysis</span>
  </button>

  <!-- Business Strategy -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-briefcase" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
        <rect width="20" height="14" x="2" y="6" rx="2"></rect>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Business Strategy</span>
  </button>

  <!-- Industry Reports -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-building" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect>
        <path d="M9 22v-4h6v4"></path>
        <path d="M8 6h.01"></path>
        <path d="M16 6h.01"></path>
        <path d="M12 6h.01"></path>
        <path d="M12 10h.01"></path>
        <path d="M12 14h.01"></path>
        <path d="M16 10h.01"></path>
        <path d="M16 14h.01"></path>
        <path d="M8 10h.01"></path>
        <path d="M8 14h.01"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Industry Reports</span>
  </button>

  <!-- Global Trends -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-globe" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
        <path d="M2 12h20"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Global Trends</span>
  </button>

  <!-- Data Analysis -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-database" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
        <path d="M3 5V19A9 3 0 0 0 21 19V5"></path>
        <path d="M3 12A9 3 0 0 0 21 12"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Data Analysis</span>
  </button>

  <!-- Security Research -->
  <button class="whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0">
    <div class="text-primary p-2 rounded-full bg-primary/10">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-shield" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
      </svg>
    </div>
    <span class="text-sm font-medium text-center">Security Research</span>
  </button>

</div>


<!-- Tailwind Utility Classes for Reuse -->
<style>
  .category-card {
    @apply whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground flex flex-col items-center justify-center h-32 w-32 p-4 gap-2 rounded-lg bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-300 snap-center flex-shrink-0;
  }
  .category-icon {
    @apply text-primary p-2 rounded-full bg-primary/10;
  }
  .category-text {
    @apply text-sm font-medium text-center;
  }
</style>
<div class="container mx-auto px-4 py-8">
  <section class="mb-16">
    <div class="w-full bg-gray-50 p-6 rounded-lg">
      
      <!-- Header & Filters -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold">Research Reports</h2>

        <!-- Search and Filter Controls -->
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
          
          <!-- Search Box -->
          <div class="relative flex-grow">
            <svg class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <path d="m21 21-4.3-4.3"></path>
            </svg>
            <input 
              class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm pl-9 w-full placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
              placeholder="Search reports..." 
              value=""
            />
          </div>

          <!-- Filters -->
          <div class="flex gap-2">
            <button type="button" class="flex h-9 items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm w-[130px]">
              <span>All Categories</span>
              <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                <path fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" d="..."></path>
              </svg>
            </button>
            <button type="button" class="flex h-9 items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm w-[130px]">
              <span>Top Rated</span>
              <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                <path fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" d="..."></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="w-full" dir="ltr" data-orientation="horizontal">
        <div role="tablist" aria-orientation="horizontal" class="inline-flex h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground mb-6">
          <button type="button" role="tab" aria-selected="true" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-md data-[state=active]:bg-background">Trending</button>
          <button type="button" role="tab" aria-selected="false" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-md">Newly Added</button>
          <button type="button" role="tab" aria-selected="false" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-md">Most Reviewed</button>
        </div>

        <!-- Tab Content -->
        <div role="tabpanel">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Report Card -->
            <div class="rounded-xl border shadow w-full max-w-[350px] h-[380px] flex flex-col overflow-hidden bg-white">
              
              <!-- Image -->
              <div class="relative h-40 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1677442135136-760c813028c0?w=400&q=80" alt="AI Implementation Strategies" class="w-full h-full object-cover transition-transform hover:scale-105">
                <div class="absolute top-2 right-2 bg-primary text-white text-xs font-bold px-2 py-1 rounded">Technology</div>
              </div>
              
              <!-- Title & Author -->
              <div class="p-4 pb-0 flex flex-col space-y-1.5">
                <h3 class="text-lg font-bold line-clamp-1">AI Implementation Strategies for Enterprise</h3>
                <p class="text-sm text-gray-600">Dr. Robert Zhang</p>
              </div>

              <!-- Description & Rating -->
              <div class="p-4 flex-grow">
                <p class="text-muted-foreground text-sm line-clamp-3">
                  Strategic framework for enterprise AI adoption, including ROI analysis, implementation roadmaps, and case studies from Fortune 500 companies.
                </p>
                <div class="mt-2 flex items-center gap-1">
                  <!-- Full Stars -->
                  <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                  <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                  <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                  <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                  <!-- Half Star -->
                  <div class="relative">
                    <svg class="lucide lucide-star h-4 w-4 text-yellow-400" ...></svg>
                    <div class="absolute inset-0 overflow-hidden w-1/2">
                      <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                    </div>
                  </div>
                  <span class="ml-1 text-sm text-gray-600">4.9</span>
                </div>
              </div>

              <!-- Footer -->

              
              <div class="p-4 pt-0 flex justify-between items-center">
                <div class="font-bold text-lg">$89.99</div>
                <div class="flex gap-2">
                  <button class="h-8 rounded-md px-3 text-xs border bg-background shadow-sm hover:bg-accent hover:text-accent-foreground flex items-center gap-1">
                    <svg class="lucide lucide-eye h-4 w-4" ...></svg> Preview
                  </button>
                  <button class="h-8 rounded-md px-3 text-xs bg-primary text-white shadow hover:bg-primary/90 flex items-center gap-1">
                    <svg class="lucide lucide-shopping-cart h-4 w-4" ...></svg> Add
                  </button>
                </div>
              </div>

            </div>
            <!-- End Report Card -->
           <!-- Report Card -->
            <div class="rounded-xl border shadow w-full max-w-[350px] h-[380px] flex flex-col overflow-hidden bg-white">
              
              <!-- Image -->
              <div class="relative h-40 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1677442135136-760c813028c0?w=400&q=80" alt="AI Implementation Strategies" class="w-full h-full object-cover transition-transform hover:scale-105">
                <div class="absolute top-2 right-2 bg-primary text-white text-xs font-bold px-2 py-1 rounded">Technology</div>
              </div>
              
              <!-- Title & Author -->
              <div class="p-4 pb-0 flex flex-col space-y-1.5">
                <h3 class="text-lg font-bold line-clamp-1">AI Implementation Strategies for Enterprise</h3>
                <p class="text-sm text-gray-600">Dr. Robert Zhang</p>
              </div>

              <!-- Description & Rating -->
              <div class="p-4 flex-grow">
                <p class="text-muted-foreground text-sm line-clamp-3">
                  Strategic framework for enterprise AI adoption, including ROI analysis, implementation roadmaps, and case studies from Fortune 500 companies.
                </p>
                <div class="mt-2 flex items-center gap-1">
                  <!-- Full Stars -->
                  <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                  <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                  <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                  <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                  <!-- Half Star -->
                  <div class="relative">
                    <svg class="lucide lucide-star h-4 w-4 text-yellow-400" ...></svg>
                    <div class="absolute inset-0 overflow-hidden w-1/2">
                      <svg class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" ...></svg>
                    </div>
                  </div>
                  <span class="ml-1 text-sm text-gray-600">4.9</span>
                </div>
              </div> 

              <!-- Footer -->
              <div class="p-4 pt-0 flex justify-between items-center">
                <div class="font-bold text-lg">$89.99</div>
                <div class="flex gap-2">
                  <button class="h-8 rounded-md px-3 text-xs border bg-background shadow-sm hover:bg-accent hover:text-accent-foreground flex items-center gap-1">
                    <svg class="lucide lucide-eye h-4 w-4" ...></svg> Preview
                  </button>
                  <button class="h-8 rounded-md px-3 text-xs bg-primary text-white shadow hover:bg-primary/90 flex items-center gap-1">
                    <svg class="lucide lucide-shopping-cart h-4 w-4" ...></svg> Add
                  </button>
                </div>
              </div>

            </div>
            <!-- End Report Card -->
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
<section class="w-full py-12 bg-background mb-16">
  <div class="container mx-auto px-4">
    <!-- Section Heading -->
    <div class="text-center mb-10">
      <h2 class="text-3xl font-bold tracking-tight mb-2">Choose Your Research Plan</h2>
      <p class="text-muted-foreground max-w-2xl mx-auto">
        Get access to expert research reports and consultations with flexible subscription options
      </p>
    </div>

    <!-- Pricing Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
      <!-- Free Plan -->
      <div class="rounded-xl border bg-card text-card-foreground shadow flex flex-col h-full">
        <div class="flex flex-col space-y-1.5 p-6">
          <h3 class="font-semibold text-2xl tracking-tight">Free</h3>
          <p class="text-sm text-muted-foreground">Basic access to limited research content</p>
          <div class="mt-4">
            <span class="text-4xl font-bold">$0</span>
            <span class="text-muted-foreground ml-2">/monthly</span>
          </div>
        </div>
        <div class="p-6 pt-0 flex-grow">
          <ul class="space-y-3">
            <li class="flex items-start">
              <x-check /> <span>Access to 5 free reports</span>
            </li>
            <li class="flex items-start">
              <x-check /> <span>Limited downloads (3/month)</span>
            </li>
            <li class="flex items-start">
              <x-check /> <span>Basic search functionality</span>
            </li>
            <li class="flex items-start">
              <x-check /> <span>Community forum access</span>
            </li>
            <li class="flex items-start text-muted-foreground">
              <x-x /> <span>Monthly expert consultations <x-help-icon /></span>
            </li>
            <li class="flex items-start text-muted-foreground">
              <x-x /> <span>Early access to new reports</span>
            </li>
            <li class="flex items-start text-muted-foreground">
              <x-x /> <span>Advanced analytics dashboard</span>
            </li>
          </ul>
        </div>
        <div class="p-6 pt-4">
          <button class="btn-outline w-full">Start Free</button>
        </div>
      </div>

      <!-- Premium Plan -->
      <div class="relative rounded-xl border border-primary shadow-lg bg-card text-card-foreground flex flex-col h-full">
        <!-- Premium Badge -->
        <div class="absolute top-3 right-3">
          <div class="bg-primary text-primary-foreground text-xs font-semibold px-2.5 py-0.5 rounded-md shadow">
            Premium Plan
          </div>
        </div>
        <div class="flex flex-col space-y-1.5 p-6">
          <h3 class="font-semibold text-2xl tracking-tight">Premium</h3>
          <p class="text-sm text-muted-foreground">Full access to all reports and expert consultations</p>
          <div class="mt-4">
            <span class="text-4xl font-bold">$99.99</span>
            <span class="text-muted-foreground ml-2">/monthly</span>
          </div>
        </div>
        <div class="p-6 pt-0 flex-grow">
          <ul class="space-y-3">
            <li class="flex items-start"><x-check /> <span>Unlimited access to all reports</span></li>
            <li class="flex items-start"><x-check /> <span>Unlimited downloads</span></li>
            <li class="flex items-start"><x-check /> <span>Advanced search & filters</span></li>
            <li class="flex items-start"><x-check /> <span>Priority email & phone support</span></li>
            <li class="flex items-start"><x-check /> <span>Monthly expert consultations <x-help-icon /></span></li>
            <li class="flex items-start"><x-check /> <span>Early access to new reports</span></li>
            <li class="flex items-start"><x-check /> <span>Advanced analytics dashboard</span></li>
          </ul>
        </div>
        <div class="p-6 pt-4">
          <button class="btn-primary w-full">Subscribe Now</button>
        </div>
      </div>
    </div>
</section>

<section class="mb-16">
  <section class="w-full py-12 bg-slate-50">
    <div class="container mx-auto px-4">
      <!-- Section Header -->
      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold tracking-tight mb-2">Featured Research Experts</h2>
        <p class="text-muted-foreground max-w-2xl mx-auto">
          Connect with leading specialists in various research domains for personalized consultations and insights.
        </p>
      </div>

      <!-- Carousel Controls -->
      <div class="relative">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-semibold">Top Experts</h3>
          <div class="flex gap-2">
            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9" disabled aria-label="Previous experts">
              <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-left h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
              </svg>
            </button>
            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9" aria-label="Next experts">
              <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-right h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Expert Cards -->
        <div class="flex justify-center gap-4 overflow-hidden">
          <!-- Expert 1 -->
          <div class="transition-all duration-300 ease-in-out">
            <div class="rounded-xl border text-card-foreground shadow w-[300px] h-[320px] overflow-hidden flex flex-col bg-white">
              <div class="flex flex-col space-y-1.5 p-6 pb-2 pt-4">
                <div class="flex items-center gap-3">
                  <span class="relative flex shrink-0 overflow-hidden rounded-full h-14 w-14 border-2 border-primary/20">
                    <img class="aspect-square h-full w-full" alt="Dr. Jane Smith" src="https://api.dicebear.com/7.x/avataaars/svg?seed=expert1" />
                  </span>
                  <div>
                    <h3 class="font-semibold text-lg">Dr. Jane Smith</h3>
                    <p class="text-xs text-muted-foreground line-clamp-1">Ph.D. in Economics, Harvard University</p>
                  </div>
                </div>
              </div>
              <div class="p-6 pt-0 flex-grow">
                <div class="flex items-center gap-1 mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                  </svg>
                  <span class="text-sm font-medium">4.8</span>
                  <span class="text-xs text-muted-foreground">(124 reviews)</span>
                </div>
                <h4 class="text-sm font-medium mb-1">Specialties:</h4>
                <div class="flex flex-wrap gap-1 mb-3">
                  <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold bg-secondary text-secondary-foreground text-xs">Market Research</div>
                  <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold bg-secondary text-secondary-foreground text-xs">Financial Analysis</div>
                  <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold bg-secondary text-secondary-foreground text-xs">Economic Forecasting</div>
                </div>
                <p class="text-xs text-muted-foreground">Expert in providing research-backed insights and analysis in specialized domains.</p>
              </div>
              <div class="items-center p-6 flex gap-2 pt-2 pb-4">
                <a class="flex-1" href="/experts/expert-1">
                  <button class="inline-flex items-center justify-center whitespace-nowrap font-medium border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-user h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    View Profile
                  </button>
                </a>
                <button class="inline-flex items-center justify-center font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs flex-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-message-square h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                  </svg>
                  Contact
                </button>
              </div>
            </div>
          </div>

          <!-- Repeat for Expert 2 and Expert 3 (same structure as above) -->

        </div>

        <!-- View All Button -->
        <div class="mt-8 text-center">
          <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
            View All Experts
          </button>
        </div>
      </div>
    </div>
  </section>
</section>
</div>
</section>
</div>
</div>

<!-- footer section -->
<?php siteFooter() ?>

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="script/fetch.js" type="text/javascript"></script>
<script>
  AOS.init({
    duration: 800,  // animation duration
    once: true,     // animate only once (when scrolled into view)
  });
</script>
</body>
</html>