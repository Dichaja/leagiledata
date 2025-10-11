<?php
session_start();
require_once('bin/page_settings.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('bin/source_links.php'); ?>

<style>

    :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --primary-dark: #1e40af;
            --accent: #60a5fa;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .step-number {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }
        
        .faq-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .faq-item:hover {
            border-left-color: var(--primary);
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
    <div class="container mx-auto py-8 px-4 max-w-4xl">
    <!-- Header Section -->
        <header class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-md mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                    <path d="M15 18a3 3 0 1 0-6 0"></path>
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"></path>
                    <circle cx="12" cy="13" r="2"></circle>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Data Collection Requirements</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Submit your specific data requirements and we'll create a tailored dataset for your research project.</p>
        </header>

        <!-- Requirements Guidance Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 card-hover">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                            <path d="M16 22h2a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4"></path>
                            <path d="M4 18a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                            <path d="M10 18a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                            <path d="M16 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800">How to Prepare Your Requirements</h2>
                </div>
                <p class="text-gray-600 mb-8">To ensure we collect exactly the data you need, please include these four key elements:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                        <div class="step-number w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm mb-3">1</div>
                        <h3 class="font-semibold text-gray-800 mb-2">Research Objectives</h3>
                        <p class="text-sm text-gray-600">Clearly define what questions you're trying to answer with this data.</p>
                    </div>
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                        <div class="step-number w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm mb-3">2</div>
                        <h3 class="font-semibold text-gray-800 mb-2">Target Population</h3>
                        <p class="text-sm text-gray-600">Specify the demographic or group from which you need data collected.</p>
                    </div>
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                        <div class="step-number w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm mb-3">3</div>
                        <h3 class="font-semibold text-gray-800 mb-2">Data Points</h3>
                        <p class="text-sm text-gray-600">List the specific variables or information you need to collect.</p>
                    </div>
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                        <div class="step-number w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm mb-3">4</div>
                        <h3 class="font-semibold text-gray-800 mb-2">Timeline & Budget</h3>
                        <p class="text-sm text-gray-600">Indicate your project timeframe and budget constraints.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Requirements Submission Form -->
        <section class="mb-12">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-sm border border-blue-100 p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Submit Your Data Requirements</h2>
                <p class="text-gray-600 mb-6">Please provide detailed information about your data needs in the field below.</p>
                
                <div class="space-y-6">
                    <div>
                        <label for="requirements-text" class="block text-sm font-medium text-gray-700 mb-2">Detailed Requirements</label>
                        <textarea 
                            id="requirements-text" 
                            class="w-full h-40 p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder="Describe your data requirements, including data points needed, target population, timeline, and any specific formats required...">
                        </textarea>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="btn-primary text-white font-medium py-3 px-6 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            Submit Requirements
                        </button>
                        <button class="bg-white text-gray-700 font-medium py-3 px-6 rounded-xl border border-gray-300 shadow-sm hover:bg-gray-50 transition-all duration-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download Template
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    Frequently Asked Questions
                </h2>
                <p class="text-gray-600 mb-6">Common questions about our data collection process</p>
                
                <div class="space-y-4">
                    <div class="faq-item p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">How do you ensure data quality and accuracy?</h3>
                        <p class="text-gray-600 text-sm">We implement rigorous quality control measures including data validation, cross-checking, and statistical verification to ensure reliable data collection.</p>
                    </div>
                    <div class="faq-item p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">Can you collect data from specific demographic groups?</h3>
                        <p class="text-gray-600 text-sm">Yes, we can target specific demographics based on your research requirements using our diverse participant pools and screening criteria.</p>
                    </div>
                    <div class="faq-item p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">What data formats do you provide?</h3>
                        <p class="text-gray-600 text-sm">We deliver data in Excel, CSV, SQL, JSON, or custom formats. All datasets are cleaned and processed for analysis.</p>
                    </div>
                    <div class="faq-item p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">How do you handle data privacy and confidentiality?</h3>
                        <p class="text-gray-600 text-sm">We adhere to GDPR and CCPA regulations with anonymization, secure storage, and encrypted transfer protocols.</p>
                    </div>
                </div>
            </div>
        </section>
  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>
<script>
        // Simple animation for FAQ items
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Toggle active state (you can expand this for accordion functionality)
                    this.classList.toggle('bg-blue-50');
                });
            });
        });
    </script>
</body>
</html>