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
    <!--<div class="text-center mb-12">
         <h1 class="text-4xl font-bold tracking-tight mb-3">
              Research Subscription Plans
         </h1>
         <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
             Get unlimited access to expert research reports and consultations with our flexible subscription options
         </p>
    </div>-->
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
                        <span class="text-gray-700">Unlimited access to all reports</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Unlimited downloads</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Advanced search &amp; filters</span>
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
                        <span class="text-gray-700">Monthly expert consultations</span>
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
         
<section class="mb-16">
  <h2 class="text-2xl font-bold text-center mb-8">What Our Subscribers Say</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Emily (Annual Premium) -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
      <div class="flex items-center mb-4">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=testimonial3" alt="User avatar" class="w-12 h-12 rounded-full">
        <div class="ml-4">
          <h4 class="font-semibold">Emily Rodriguez</h4>
          <p class="text-sm text-muted-foreground">Academic Researcher</p>
        </div>
      </div>
      <blockquote class="text-muted-foreground">
        "The annual Premium subscription offers incredible value. The unlimited access to reports and expert consultations has significantly accelerated my research projects."
      </blockquote>
    </div>

    <!-- Sarah (Premium Monthly) -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
      <div class="flex items-center mb-4">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=testimonial1" alt="User avatar" class="w-12 h-12 rounded-full">
        <div class="ml-4">
          <h4 class="font-semibold">Sarah Johnson</h4>
          <p class="text-sm text-muted-foreground">Marketing Director</p>
        </div>
      </div>
      <blockquote class="text-muted-foreground">
        "The Premium subscription has been invaluable for our market research. The expert consultations alone are worth the price, providing insights we couldn't get elsewhere."
      </blockquote>
    </div>

    <!-- Michael (Free Subscriber) -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
      <div class="flex items-center mb-4">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=testimonial2" alt="User avatar" class="w-12 h-12 rounded-full">
        <div class="ml-4">
          <h4 class="font-semibold">Michael Chen</h4>
          <p class="text-sm text-muted-foreground">Investment Analyst</p>
        </div>
      </div>
      <blockquote class="text-muted-foreground">
        "I've been a Free subscriber for six months, and the quality of research reports is exceptional. It's helped me make more informed investment decisions."
      </blockquote>
    </div>

  </div>
</section>

  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>
</body>
</html>