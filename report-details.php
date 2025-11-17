<?php
session_start();
require_once('bin/page_settings.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('bin/source_links.php'); ?>
 <style>
   /* Additional custom styles if needed */
        .report-image {
            max-height: 400px;
            object-fit: contain;
        }
        .sticky-sidebar {
            position: sticky;
            top: 20px;
        }
    </style>
</head>
<body class="bg-gray-50">
   <!-- Header Section -->
 <?php siteHeader() ?>
    <main class="container mx-auto px-4 py-8">
     <div id="loading-indicator" class="hidden">
       <!-- Your loading spinner/message -->
         <div class="flex justify-center items-center py-12">
           <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
         </div>
      </div>

<div id="error-container" class="hidden"></div>
<!-- Inside your content-container div -->
<div class="flex items-center mb-6">
    <a href="categories.php">
      <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 p-0 mr-2">
        <svg class="lucide lucide-arrow-left h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="m12 19-7-7 7-7"></path>
          <path d="M19 12H5"></path>
        </svg>
        Back to Shopping
      </button>
    </a>
  </div>
<div id="content-container" class="hidden">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Main Content -->
        <div class="lg:w-2/3">
            <!-- Report Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h1 id="report-title" class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Report Title</h1>
                <div id="report-meta" class="text-sm text-gray-500 mb-4">
                    <span id="report-category" class="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-semibold text-gray-700 mr-2">Category</span>
                    <span id="report-date">Published: Loading...</span>
                </div>
                <div class="flex justify-center mb-6">
                    <img id="report-image" src="" alt="Report Image" class="report-image w-full max-w-lg bg-gray-50 p-4 rounded">
                </div>
                <div id="report-description" class="prose max-w-none">
                    <p>Loading description...</p>
                </div>
            </div>

            <!-- Report Content Sections -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Report Specifications</h2>
                <!--<div id="report-specifications" class="prose max-w-none">
                    Loading specifications...
                </div>-->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Expert Illustration Column -->
        <div class="md:w-1/3 flex flex-col items-center">
            <div class="relative w-32 h-32 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Expert Consultation</h3>
            <p class="text-sm text-gray-600 text-center">Get personalized insights about this report from our network of specialists.</p>
        </div>

        <!-- Request Form Column -->
        <div class="md:w-2/3">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Request Expert Access</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">What would you like to discuss?</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" rows="3" placeholder="Briefly describe your questions or needs..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your name</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="urgent-request" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="urgent-request" class="ml-2 block text-sm text-gray-700">This is an urgent request</label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full md:w-auto flex justify-center items-center gap-2 px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Submit Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="mt-6 pt-6 border-t border-gray-100">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-800">Response Time</h4>
                    <p class="text-sm text-gray-600">Typically within 24 hours for standard requests</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-800">Verified Experts</h4>
                    <p class="text-sm text-gray-600">All specialists are vetted professionals in this field</p>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:w-1/3">
            <div class="sticky-sidebar">
                <!-- Purchase Box -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <span id="report-price" class="text-2xl font-bold text-gray-900">$0.00</span>
                        <span id="report-downloads" class="text-sm text-gray-500">0 downloads</span>
                    </div>
                    
                    <div class="space-y-3">
                        <button id="download-btn" class="download-btn w-full flex items-center justify-center gap-2 border border-gray-300 hover:bg-gray-50 text-gray-800 py-2 px-4 rounded-md transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" x2="12" y1="15" y2="3"></line>
                            </svg>
                            Download Now
                        </button>
                        <!-- Optimized direct checkout button -->
                        <form method="get" action="checkout.php">
                            <input type="hidden" name="report_id" id="checkout-report-id" value="">
                            <button type="submit" id="proceed-checkout-btn" class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition-colors mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                                Proceed to Checkout
                            </button>
                        </form>

                        <button id="add-to-cart-btn" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                            </svg>
                            Add to Cart
                        </button>
                    </div>
<script>
// Set the report_id value for the checkout form
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const reportId = urlParams.get('id');
    const checkoutInput = document.getElementById('checkout-report-id');
    if (checkoutInput && reportId) {
        checkoutInput.value = reportId;
    }
});
</script>
                    
                    <div class="mt-4 text-sm text-gray-500">
                        <p>Formats available: <span id="file-format">Loading...</span></p>
                        <p class="mt-2">File size: <span id="file-size"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const contentContainer = document.getElementById('content-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            const errorContainer = document.getElementById('error-container');
    
            // Get report ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            const reportId = urlParams.get('id');
            const tabParam = urlParams.get('tab');
            const defaultTab = tabParam === 'register' ? 'register' : 'login';
            selectFrm(defaultTab);

            const form = document.getElementById('registration-form');
            const passwordInput = document.getElementById('reg-password');
            const confirmPasswordInput = document.getElementById('reg-confirm-password');
            const strengthMeter = document.getElementById('strength-meter');
            const strengthText = document.getElementById('strength-text');
            const passwordMatch = document.getElementById('password-match');
            const successPopup = document.getElementById('success-popup');
            const popup = document.getElementById('popup');
            const errorPopup = document.getElementById('error-popup');
            const errorMessageText = document.getElementById('error-message-text');
            const popupClose = document.getElementById('popup-close');
            const errorPopupClose = document.getElementById('error-popup-close');
            const submitButton = document.getElementById('submit-button');
            const loginButton = document.getElementById('login-button');
            const buttonText = document.getElementById('button-text');
            const loginFrm = document.getElementById('login-form');
            const loader = document.getElementById('loader');
    
    // Show loading state
    function showLoading() {
        loadingIndicator.classList.remove('hidden');
        contentContainer.classList.add('hidden');
        errorContainer.classList.add('hidden');
    }
    
    // Show error state
    function showError(message) {
        errorContainer.innerHTML = `
            <div class="bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">${message}</p>
                    </div>
                </div>
            </div>
        `;
        loadingIndicator.classList.add('hidden');
        contentContainer.classList.add('hidden');
        errorContainer.classList.remove('hidden');
    }
    
    // Show content
    function showContent() {
        loadingIndicator.classList.add('hidden');
        errorContainer.classList.add('hidden');
        contentContainer.classList.remove('hidden');
    }
    
    // Fetch report details from API
    async function fetchReportDetails(id) {
        try {
            showLoading();
            
            const response = await fetch(`fetch/report-details.php?id=${id}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const report = await response.json();
            
            if (report.error) {
                throw new Error(report.error);
            }
            
            return report;
            
        } catch (error) {
            console.error('Error fetching report:', error);
            showError(error.message || 'Failed to load report details. Please try again later.');
            return null;
        }
    }
    
    // Populate the page with report data
    function renderReport(report) {
        if (!report) {
            showError('Report not found');
            return;
        }
        
        // Basic Info
        document.getElementById('report-title').textContent = report.title;
        document.getElementById('report-price').textContent = `$${report.price}`;
        document.getElementById('report-category').textContent = report.category;
        document.getElementById('report-downloads').textContent = `${report.download_count || 0} downloads`;
        document.getElementById('report-date').textContent = `Published: ${new Date(report.created_at).toLocaleDateString()}`;
        document.getElementById('file-format').textContent = report.file_type;
        document.getElementById('file-size').textContent = report.file_size;
        // Image
        const reportImage = document.getElementById('report-image');
        if (report.thumbnail) {
            reportImage.src = report.thumbnail;
            reportImage.alt = report.title;
        } else {
            reportImage.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e5e7eb'%3E%3Cpath d='M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z'/%3E%3Cpath d='M14 17H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z'/%3E%3C/svg%3E";
        }
        
        // Description
        document.getElementById('report-description').innerHTML = `<p>${report.description}</p>`;
        
        // Specifications
        const specsHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Technical Details</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><strong>Format:</strong> ${report.formats?.join(', ') || 'PDF'}</li>
                        <li><strong>Size:</strong> ${report.size || 'N/A'}</li>
                        <li><strong>Pages:</strong> ${report.recordCount || 'N/A'}</li>
                        <li><strong>Author:</strong> ${report.author || 'N/A'}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Access</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><strong>Download URL:</strong> <a href="${report.download_url}" class="text-blue-600 hover:underline" target="_blank">Direct link</a></li>
                        <li><strong>License:</strong> Commercial use permitted</li>
                    </ul>
                </div>
            </div>
        `;
        //document.getElementById('report-specifications').innerHTML = specsHtml;
        
        // Set up action buttons
        const downloadBtn = document.getElementById('download-btn');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        
        downloadBtn.dataset.id = reportId;
        downloadBtn.dataset.title = report.title;
        downloadBtn.dataset.price = report.price;
        downloadBtn.dataset.url = report.download_url;
        
        addToCartBtn.dataset.id = reportId;
        addToCartBtn.dataset.title = report.title;
        addToCartBtn.dataset.price = report.price;
        
        // Show content after rendering
        showContent();
    }

    
   
    // Handle add to cart
    document.getElementById('add-to-cart-btn').addEventListener('click', async function() {
        const reportId = this.dataset.id;
        const reportTitle = this.dataset.title;
        
        try {
            const response = await fetch('add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    itemId: reportId,
                    itemType: 'report',
                    itemTitle: reportTitle,
                    itemPrice: this.dataset.price
                })
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    alert(`Added to cart: ${reportTitle}`);
                    // Update cart counter if you have one
                    if (result.cartCount) {
                        document.getElementById('cart-count').textContent = result.cartCount;
                    }
                } else {
                    throw new Error(result.message || 'Add to cart failed');
                }
            } else {
                throw new Error('Add to cart failed');
            }
        } catch (error) {
            console.error('Add to cart error:', error);
            alert(`Failed to add ${reportTitle} to cart. Please try again.`);
        }
    });
    
    // Initialize page
    if (!reportId) {
        showError('No report ID specified in URL');
    } else {
        // Fetch and render report
        fetchReportDetails(reportId)
            .then(renderReport)
            .catch(error => {
                console.error('Initialization error:', error);
                showError('Failed to load report details');
            });
    }

async function getCurrentUserId() {
  try {
    const response = await fetch('fetch/auth.php');
     if (!response.ok) throw new Error('Failed to get user ID');
      const data = await response.json();
      return data.user_id;
  } catch (error) {
    console.error('Error getting user ID:', error);
    return null; // Handle this case in your UI
  }
}

function handleDownload(itemId) {
  // Find the download button that matches the itemId
  const downloadBtn = document.querySelector(`.download-btn[data-id="${itemId}"]`);
  
  if (downloadBtn) {
    // Get all data from button attributes
    const item = {
      id: downloadBtn.dataset.id,
      title: downloadBtn.dataset.title,
      price: parseFloat(downloadBtn.dataset.price),
      category: downloadBtn.dataset.category,
      thumbnail: downloadBtn.dataset.thumbnail,
      download_url: downloadBtn.dataset.downloadUrl || '',
      quantity: 1,
      fileType: downloadBtn.dataset.file_type
    };

    localStorage.setItem('cart', JSON.stringify([item]));
    localStorage.setItem('directDownload', 'true');
    window.location.href = `checkout.php?report_id=${encodeURIComponent(downloadBtn.dataset.id)}&amount=${encodeURIComponent(parseFloat(downloadBtn.dataset.price))}&action_type=report`;
  }
}

// Add event listeners to buttons
document.querySelectorAll('.download-btn').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.stopPropagation();        
        const user_id = await getCurrentUserId();
         if(!user_id){
            const successPopup = document.getElementById('success-popup');
            successPopup.classList.add('active');
         } else {
            handleDownload(btn.dataset.id);
         }
      });
});



// Form submission
submitButton.addEventListener('click', function(e) {
                e.preventDefault();
                

                let isValid = true,
                    popMsg='',
                    popH1='';
                const nameInput = document.getElementById('reg-name');
                if (!nameInput.value.trim()) {
                    document.getElementById('name-error').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('name-error').style.display = 'none';
                }
                
                // Email validation
                const emailInput = document.getElementById('reg-email');
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailInput.value)) {
                    document.getElementById('email-error').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('email-error').style.display = 'none';
                }
                
                // Password validation
                if (passwordInput.value.length < 8) {
                    document.getElementById('password-error').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('password-error').style.display = 'none';
                }
                
                // Password match validation
                if (passwordInput.value !== confirmPasswordInput.value) {
                    isValid = false;
                }
                
                // If form is valid, submit via AJAX
                if (isValid) {
                    // Show loading state
                    buttonText.style.display = 'none';
                    loader.style.display = 'block';
                    submitButton.disabled = true;
                    
                    // Create FormData object
                    const formData = new FormData(form);
                    
                    // Send AJAX request
                    fetch('bin/register_process.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading state
                        buttonText.style.display = 'block';
                        loader.style.display = 'none';
                        submitButton.disabled = false;
                        
                        if (data.success) {
                            form.reset();
                            strengthMeter.style.width = '0%';
                            strengthText.textContent = 'Password strength';
                            strengthText.style.color = '#666';
                            passwordMatch.style.display = 'none';
                            popMsg = data.message;                          
                          } else {
                            popMsg = data.message                            
                        }
                            successPopup.classList.remove('active'); 
                            popup.classList.add('active');
                            popup.querySelector('h2').textContent = 'Action Response';
                            popup.querySelector('p').textContent = popMsg;
                            popup.querySelector('button').id = 'error-popup-close';
                        console.log(data)
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Hide loading state
                        buttonText.style.display = 'block';
                        loader.style.display = 'none';
                        submitButton.disabled = false;
                        successPopup.classList.remove('active')
                        // Show error popup
                        errorMessageText.textContent = 'An error occurred during registration. Please try again.';
                        errorPopup.classList.add('active');
     });
  }
});

loginButton.addEventListener('click',function(e){
    e.preventDefault();
    const formData = new FormData(loginFrm);
    const successPopup = document.getElementById('success-popup');
    const popup = document.getElementById('popup');

    fetch('fetch/auth.php', {
            method: 'POST',
            body: formData
        })
    .then(response => response.json())
    .then(data => {
        loginFrm.reset();
        successPopup.classList.add('active');
         if (data.success) {          
           window.location.reload();
         }else{
          successPopup.classList.remove('active');
          popup.classList.add('active');
          popup.querySelector('h2').textContent = 'Something Went Wrong';
          popup.querySelector('p').textContent = data.message;
          popup.querySelector('button').id = 'error-popup-close';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    })
})
            
// Close success popup
popupClose.addEventListener('click', function() {
    popup.classList.remove('active');
});
            
// Close error popup
errorPopupClose.addEventListener('click', function() {
    errorPopup.classList.remove('active');
});

// Password strength calculation
passwordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const strength = calculatePasswordStrength(password);
                
                // Update strength meter
                strengthMeter.style.width = strength.percentage + '%';
                strengthMeter.style.background = strength.color;
                
                // Update text
                strengthText.textContent = strength.text;
                strengthText.style.color = strength.color;
  });
            

// Password confirmation check
confirmPasswordInput.addEventListener('input', function() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    passwordMatch.classList.add('no-match');
                    passwordMatch.classList.remove('match');
                    passwordMatch.querySelector('.no-match').style.display = 'inline';
                    passwordMatch.querySelector('.match').style.display = 'none';
                    passwordMatch.style.display = 'block';
                } else {
                    passwordMatch.classList.add('match');
                    passwordMatch.classList.remove('no-match');
                    passwordMatch.querySelector('.match').style.display = 'inline';
                    passwordMatch.querySelector('.no-match').style.display = 'none';
                    passwordMatch.style.display = 'block';
                }
            });       

function calculatePasswordStrength(password) {
                let strength = {
                    percentage: 0,
                    color: '#e74c3c',
                    text: 'Very Weak'
                };
                
                if (password.length === 0) {
                    return strength;
                }
                
                // Check password length
                if (password.length < 6) {
                    strength.percentage = 25;
                    strength.color = '#e74c3c';
                    strength.text = 'Weak';
                    return strength;
                }
                
                // Check for mixed case, numbers, and special characters
                const hasUpperCase = /[A-Z]/.test(password);
                const hasLowerCase = /[a-z]/.test(password);
                const hasNumbers = /\d/.test(password);
                const hasSpecialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
                
                let strengthValue = 0;
                if (hasUpperCase) strengthValue++;
                if (hasLowerCase) strengthValue++;
                if (hasNumbers) strengthValue++;
                if (hasSpecialChars) strengthValue++;
                if (password.length > 10) strengthValue++;
                
                switch(strengthValue) {
                    case 1:
                        strength.percentage = 25;
                        strength.color = '#e74c3c';
                        strength.text = 'Weak';
                        break;
                    case 2:
                        strength.percentage = 50;
                        strength.color = '#f39c12';
                        strength.text = 'Fair';
                        break;
                    case 3:
                        strength.percentage = 75;
                        strength.color = '#3498db';
                        strength.text = 'Good';
                        break;
                    case 4:
                    case 5:
                        strength.percentage = 100;
                        strength.color = '#27ae60';
                        strength.text = 'Strong';
                        break;
        }               
    return strength;
 }

});

function switchTab(tab) {
    const loginPanel = document.getElementById('login-panel');
    const registerPanel = document.getElementById('register-panel');
    const tabLogin = document.getElementById('tab-login');
    const tabRegister = document.getElementById('tab-register');

    if (tab === 'login') {
      loginPanel.classList.remove('hidden');
      registerPanel.classList.add('hidden');
      tabLogin.classList.add('bg-white', 'text-black', 'shadow');
      tabRegister.classList.remove('bg-white', 'text-black', 'shadow');
      tabRegister.classList.add('text-gray-500');
    } else {
      registerPanel.classList.remove('hidden');
      loginPanel.classList.add('hidden');
      tabRegister.classList.add('bg-white', 'text-black', 'shadow');
      tabLogin.classList.remove('bg-white', 'text-black', 'shadow');
      tabLogin.classList.add('text-gray-500');
    }
  }

// Function to switch tabs
function selectFrm(tabName) {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url);
            switchTab(tabName)
  }

</script>
<!-- footer section -->
<?php siteFooter() ?>
</body>
</html>