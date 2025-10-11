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
    <div class="max-w-4xl mx-auto p-6">
        <!-- Header Section -->
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-6">Our Research Services</h1>
            <p class="text-xl text-gray-700">We offer comprehensive research services to help you gather, analyze, and interpret data for your academic, business, or policy needs.</p>
        </header>

        <!-- Why Choose Us Section (moved up for better positioning) -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold mb-6 text-center">Why Choose Our Research Services?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h3 class="text-xl font-semibold mb-3">Expert Team</h3>
                    <p>Our research team consists of PhD-level experts with extensive experience in various fields including data science, economics, social sciences, and more.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h3 class="text-xl font-semibold mb-3">Rigorous Methodology</h3>
                    <p>We follow established research methodologies and maintain the highest standards of academic and professional integrity in all our work.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h3 class="text-xl font-semibold mb-3">Customized Approach</h3>
                    <p>We tailor our research services to your specific needs, ensuring that you receive relevant and actionable insights for your unique context.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h3 class="text-xl font-semibold mb-3">Comprehensive Support</h3>
                    <p>From initial consultation to final delivery, we provide comprehensive support throughout the research process, including regular updates and consultations.</p>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Data Collection (moved to appear first) -->
            <div class="rounded-xl border bg-white shadow-md hover:shadow-lg transition-shadow">
                <div class="flex flex-col space-y-1.5 p-6 pb-4">
                    <div class="mb-4 bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database h-6 w-6 text-blue-600">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M3 5V19A9 3 0 0 0 21 19V5"></path>
                            <path d="M3 12A9 3 0 0 0 21 12"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold leading-none tracking-tight text-xl">Data Collection</h3>
                    <p class="text-sm text-gray-600">Gather high-quality data for your research needs</p>
                </div>
                <div class="p-6 pt-0">
                    <p class="text-gray-700">We provide comprehensive data collection services tailored to your specific research requirements and objectives.</p>
                    <ul class="mt-4 space-y-2 text-gray-700">
                        <li>• Surveys & questionnaires</li>
                        <li>• Interviews & focus groups</li>
                        <li>• Field data collection</li>
                        <li>• Database mining</li>
                    </ul>
                </div>
                <div class="flex items-center p-6 pt-0">
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-300 disabled:pointer-events-none disabled:opacity-50 border border-gray-300 bg-white shadow-sm hover:bg-gray-50 h-9 px-4 py-2 w-full" onclick="window.location.href='<?php echo BASE_URL ?>/data_collect.php'">
                        Learn More
                    </button></div>
            </div>

            <!-- Data Analysis -->
            <div class="rounded-xl border bg-white shadow-md hover:shadow-lg transition-shadow">
                <div class="flex flex-col space-y-1.5 p-6 pb-4">
                    <div class="mb-4 bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart3 h-6 w-6 text-blue-600">
                            <path d="M3 3v18h18"></path>
                            <path d="M18 17V9"></path>
                            <path d="M13 17V5"></path>
                            <path d="M8 17v-3"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold leading-none tracking-tight text-xl">Data Analysis</h3>
                    <p class="text-sm text-gray-600">Extract meaningful insights from your research data</p>
                </div>
                <div class="p-6 pt-0">
                    <p class="text-gray-700">Our expert team provides comprehensive data analysis services to help you understand patterns, test hypotheses, and visualize results.</p>
                    <ul class="mt-4 space-y-2 text-gray-700">
                        <li>• Statistical analysis</li>
                        <li>• Data visualization</li>
                        <li>• Predictive modeling</li>
                        <li>• Machine learning</li>
                    </ul>
                </div>
                <div class="flex items-center p-6 pt-0">
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-300 disabled:pointer-events-none disabled:opacity-50 border border-gray-300 bg-white shadow-sm hover:bg-gray-50 h-9 px-4 py-2 w-full" onclick="window.location.href='<?php echo BASE_URL ?>/data_analyst.php'">
                        Learn More
                    </button>
                </div>
            </div>
        </section>
    </div>
  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>
</body>
</html>