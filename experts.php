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
              <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9" disabled="" aria-label="Previous experts">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-4 w-4"><path d="m15 18-6-6 6-6"></path></svg>
              </button>
              <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9" aria-label="Next experts">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-4 w-4"><path d="m9 18 6-6-6-6"></path></svg>
              </button>
            </div>
          </div>
          
          <div class="flex justify-center gap-4 overflow-hidden">
            <!-- Expert Card 1 -->
            <div class="transition-all duration-300 ease-in-out">
              <div class="rounded-xl border text-card-foreground shadow w-[300px] h-[320px] overflow-hidden flex flex-col bg-white">
                <div class="flex flex-col space-y-1.5 p-6 pb-2 pt-4">
                  <div class="flex items-center gap-3">
                    <span class="relative flex shrink-0 overflow-hidden rounded-full h-14 w-14 border-2 border-primary/20">
                      <img class="aspect-square h-full w-full" alt="Dr. Jane Smith" src="https://api.dicebear.com/7.x/avataaars/svg?seed=expert1">
                    </span>
                    <div>
                      <h3 class="font-semibold text-lg">Dr. Jane Smith</h3>
                      <p class="text-xs text-muted-foreground line-clamp-1">Ph.D. in Economics, Harvard University</p>
                    </div>
                  </div>
                </div>
                
                <div class="p-6 pt-0 flex-grow">
                  <div class="flex items-center gap-1 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <span class="text-sm font-medium">4.8</span>
                    <span class="text-xs text-muted-foreground">(124 reviews)</span>
                  </div>
                  
                  <h4 class="text-sm font-medium mb-1">Specialties:</h4>
                  <div class="flex flex-wrap gap-1 mb-3">
                    <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-xs">Market Research</div>
                    <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-xs">Financial Analysis</div>
                    <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-xs">Economic Forecasting</div>
                  </div>
                  
                  <p class="text-xs text-muted-foreground">Expert in providing research-backed insights and analysis in specialized domains.</p>
                </div>
                
                <div class="items-center p-6 flex gap-2 pt-2 pb-4">
                  <a class="flex-1" href="/experts/expert-1">
                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs w-full">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-1"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                      View Profile
                    </button>
                  </a>
                  <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square h-4 w-4 mr-1"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    Contact
                  </button>
                </div>
              </div>
            </div>
            
            </div><div class="mt-8 text-center">
          <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 font-medium">View All Experts</button>
        </div>
            </div>
          </div>
        </div>
        
        
      </div>
    </section>
  </section>

  <!-- Expert Directory Section -->
  <section>
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
          <input class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-9 w-full" placeholder="Search experts..." value="">
        </div>
        
        <div class="flex gap-2">
          <button type="button" role="combobox" aria-controls="radix-:r3q:" aria-expanded="false" aria-autocomplete="none" dir="ltr" data-state="closed" class="flex h-9 items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&amp;>span]:line-clamp-1 w-[150px]">
            <span style="pointer-events: none;">All Specialties</span>
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50" aria-hidden="true">
              <path d="M4.93179 5.43179C4.75605 5.60753 4.75605 5.89245 4.93179 6.06819C5.10753 6.24392 5.39245 6.24392 5.56819 6.06819L7.49999 4.13638L9.43179 6.06819C9.60753 6.24392 9.89245 6.24392 10.0682 6.06819C10.2439 5.89245 10.2439 5.60753 10.0682 5.43179L7.81819 3.18179C7.73379 3.0974 7.61933 3.04999 7.49999 3.04999C7.38064 3.04999 7.26618 3.0974 7.18179 3.18179L4.93179 5.43179ZM10.0682 9.56819C10.2439 9.39245 10.2439 9.10753 10.0682 8.93179C9.89245 8.75606 9.60753 8.75606 9.43179 8.93179L7.49999 10.8636L5.56819 8.93179C5.39245 8.75606 5.10753 8.75606 4.93179 8.93179C4.75605 9.10753 4.75605 9.39245 4.93179 9.56819L7.18179 11.8182C7.35753 11.9939 7.64245 11.9939 7.81819 11.8182L10.0682 9.56819Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
          </button>
          
          <button type="button" role="combobox" aria-controls="radix-:r3r:" aria-expanded="false" aria-autocomplete="none" dir="ltr" data-state="closed" class="flex h-9 items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&amp;>span]:line-clamp-1 w-[130px]">
            <span style="pointer-events: none;">Top Rated</span>
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50" aria-hidden="true">
              <path d="M4.93179 5.43179C4.75605 5.60753 4.75605 5.89245 4.93179 6.06819C5.10753 6.24392 5.39245 6.24392 5.56819 6.06819L7.49999 4.13638L9.43179 6.06819C9.60753 6.24392 9.89245 6.24392 10.0682 6.06819C10.2439 5.89245 10.2439 5.60753 10.0682 5.43179L7.81819 3.18179C7.73379 3.0974 7.61933 3.04999 7.49999 3.04999C7.38064 3.04999 7.26618 3.0974 7.18179 3.18179L4.93179 5.43179ZM10.0682 9.56819C10.2439 9.39245 10.2439 9.10753 10.0682 8.93179C9.89245 8.75606 9.60753 8.75606 9.43179 8.93179L7.49999 10.8636L5.56819 8.93179C5.39245 8.75606 5.10753 8.75606 4.93179 8.93179C4.75605 9.10753 4.75605 9.39245 4.93179 9.56819L7.18179 11.8182C7.35753 11.9939 7.64245 11.9939 7.81819 11.8182L10.0682 9.56819Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
    
    <div dir="ltr" data-orientation="horizontal" class="w-full">
      <div role="tablist" aria-orientation="horizontal" class="inline-flex h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground mb-6" tabindex="0" data-orientation="horizontal" style="outline: none;">
        <button type="button" role="tab" aria-selected="false" aria-controls="radix-:r3s:-content-all" data-state="inactive" id="radix-:r3s:-trigger-all" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow" tabindex="-1" data-orientation="horizontal" data-radix-collection-item="">All Experts</button>
        <button type="button" role="tab" aria-selected="false" aria-controls="radix-:r3s:-content-business" data-state="inactive" id="radix-:r3s:-trigger-business" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow" tabindex="-1" data-orientation="horizontal" data-radix-collection-item="">Business &amp; Finance</button>
        <button type="button" role="tab" aria-selected="false" aria-controls="radix-:r3s:-content-technology" data-state="inactive" id="radix-:r3s:-trigger-technology" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow" tabindex="-1" data-orientation="horizontal" data-radix-collection-item="">Technology</button>
        <button type="button" role="tab" aria-selected="true" aria-controls="radix-:r3s:-content-science" data-state="active" id="radix-:r3s:-trigger-science" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow" tabindex="0" data-orientation="horizontal" data-radix-collection-item="">Science &amp; Medicine</button>
      </div>
      
      <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-:r3s:-trigger-all" id="radix-:r3s:-content-all" tabindex="0" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-0" style="" hidden=""></div>
      <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-:r3s:-trigger-business" id="radix-:r3s:-content-business" tabindex="0" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-0" hidden=""></div>
      <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-:r3s:-trigger-technology" id="radix-:r3s:-content-technology" tabindex="0" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-0" hidden=""></div>
      
      <div data-state="active" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-:r3s:-trigger-science" id="radix-:r3s:-content-science" tabindex="0" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-0">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <!-- Expert Grid Item 1 -->
          <div class="rounded-xl border text-card-foreground shadow w-[300px] h-[320px] overflow-hidden flex flex-col bg-white">
            <div class="flex flex-col space-y-1.5 p-6 pb-2 pt-4">
              <div class="flex items-center gap-3">
                <span class="relative flex shrink-0 overflow-hidden rounded-full h-14 w-14 border-2 border-primary/20">
                  <img class="aspect-square h-full w-full" alt="Prof. Michael Chen" src="https://api.dicebear.com/7.x/avataaars/svg?seed=expert2">
                </span>
                <div>
                  <h3 class="font-semibold text-lg">Prof. Michael Chen</h3>
                  <p class="text-xs text-muted-foreground line-clamp-1">Professor of Computer Science, MIT</p>
                </div>
              </div>
            </div>
            
            <div class="p-6 pt-0 flex-grow">
              <div class="flex items-center gap-1 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                <span class="text-sm font-medium">4.9</span>
                <span class="text-xs text-muted-foreground">(156 reviews)</span>
              </div>
              
              <h4 class="text-sm font-medium mb-1">Specialties:</h4>
              <div class="flex flex-wrap gap-1 mb-3">
                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-xs">AI Research</div>
                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-xs">Machine Learning</div>
                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-xs">Data Science</div>
              </div>
              
              <p class="text-xs text-muted-foreground">Expert in providing research-backed insights and analysis in specialized domains.</p>
            </div>
            
            <div class="items-center p-6 flex gap-2 pt-2 pb-4">
              <a class="flex-1" href="/experts/expert-1">
                <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs w-full">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-1"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                  View Profile
                </button>
              </a>
              <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square h-4 w-4 mr-1"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                Contact
              </button>
            </div>
          </div>
          
          </div>
        </div>
      </div>
    </div>
  </section>
  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>
</body>
</html>