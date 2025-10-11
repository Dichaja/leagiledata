//new
async function fetchReports(urlParams) {
  try { 
    const response = await fetch('fetch/get_reports.php');

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const reports = await response.json();
    const container = document.getElementById('report-cards');
    container.innerHTML = ''; // Clear existing

    reports.forEach(report => {
      const card = document.createElement('div');
      card.className = 'transition-all duration-300 ease-in-out min-w-[250px] max-w-[350px] flex-shrink-0 cursor-pointer';

      // Make the entire card clickable
      card.addEventListener('click', (e) => {
        // Don't navigate if clicking on buttons
        if (e.target.closest('.download-btn') || e.target.closest('.add-to-cart')) {
          return;
        }
        navigateToDetails(report);
      });

      if (report.thumbnail) {
            var imgSrc =  report.thumbnail;
        } else {
           var imgSrc = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e5e7eb'%3E%3Cpath d='M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z'/%3E%3Cpath d='M14 17H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z'/%3E%3C/svg%3E";
        }

      card.innerHTML = `<!-- Report Card Template -->
<div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md border border-gray-200 transition-all duration-200 hover:border-blue-200 hover:cursor-pointer">
    <!-- Image Container -->
    <div class="relative mb-2 sm:mb-3">
        <img src="${imgSrc}" alt="${report.title}" class="w-full h-32 sm:h-40 object-contain bg-gray-50 p-2">
    </div>
    
    <!-- Price -->
    <div class="mb-1 sm:mb-2 flex items-center">
        <span class="text-sm font-bold text-gray-900">$${report.price}</span>
    </div>
    
    <!-- Title -->
    <h3 class="text-xs sm:text-sm font-medium line-clamp-2 mb-1 sm:mb-2 min-h-[2.5em]">
        ${report.title}
    </h3>
    
    <!-- Stats -->
    <div class="flex items-center justify-between text-xs text-gray-500 mb-2 sm:mb-3">
        <div class="flex items-center">
            <span>${report.file_size}</span>
            <span class="ml-1">${report.file_type}</span>
        </div>
        <span>${report.download_count ? report.download_count.toLocaleString() : '0'} downloads</span>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex gap-2">
        <!-- Download Button -->
        <button class="download-btn flex-1 flex items-center justify-center gap-1 border border-gray-300 hover:bg-gray-50 text-gray-800 py-1.5 px-2 sm:px-3 rounded-md text-xs sm:text-sm transition-colors" data-id="${report.id}" data-title="${report.title}" data-price="${report.price}" data-category="${report.category}" data-thumbnail="${report.thumbnail}" data-type="${report.file_type}" data-size="${report.file_size}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" x2="12" y1="15" y2="3"></line>
            </svg>
            Download
        </button>
        
        <!-- Add to Cart Button -->
        <button class="flex-1 flex items-center justify-center gap-1 bg-blue-600 hover:bg-blue-700 text-white py-1.5 px-2 sm:px-3 rounded-md text-xs sm:text-sm transition-colors add-to-cart" data-id="${report.id}" data-title="${report.title}" data-price="${report.price}" data-category="${report.category}" data-thumbnail="${report.thumbnail}" data-type="${report.file_type}" data-size="${report.file_size}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="8" cy="21" r="1"></circle>
                <circle cx="19" cy="21" r="1"></circle>
                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
            </svg>
            Add
        </button>
    </div>
</div> `;
      container.appendChild(card);
    }); 
   
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

document.querySelectorAll('.add-to-cart').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        //handleAddToCart(btn.dataset.id);
      });
    });

document.querySelectorAll(".add-to-cart").forEach(btn => {
    btn.addEventListener("click", async () => {
        const item = {
            id: btn.dataset.id,
            title: btn.dataset.title,
            price: parseFloat(btn.dataset.price),
            thumbnail: btn.dataset.thumbnail,
            category: btn.dataset.category,
            fileType: btn.dataset.type,
            fileSize: btn.dataset.size
        };

        let cart = JSON.parse(localStorage.getItem("cart") || "[]");
        
        // Check if item already exists in cart
        const itemExists = cart.some(cartItem => cartItem.id === item.id);
        
        if (!itemExists) {
            cart.push(item);  
            localStorage.setItem("cart", JSON.stringify(cart));
            updateCartCount();
            try {
                   const success = await syncCartItemWithServer(item, 'add');
                     if (success) {
                        alert("Added to cart!");
                      }
                   } catch (err) {
                          console.error('Download failed:', err);
                   }
        } else {
            alert("This item is already in your cart!");
        }
    });
});

  } catch (error) {
    console.error("Error fetching reports:", error);
    document.getElementById('report-cards').innerHTML = '<p class="text-red-500">Error loading reports. Please try again later.</p>';
  }
}

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

async function handleDownload(itemId) {
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
      fileType: downloadBtn.dataset.type
    };

    localStorage.setItem('cart', JSON.stringify([item]));
    localStorage.setItem('directDownload', 'true');

    try {
      const success = await syncCartItemWithServer(item, 'add');
      if (success) {
        window.location.href = 'checkout.php';
      }
        console.log(downloadBtn)
    } catch (err) {
      console.error('Download failed:', err);
    }
  }
}

async function handleDownloads(reportId) {
  try {
    const response = await fetch(`fetch/downloads.php?id=${reportId}`);
    
    if (!response.ok) {
      throw new Error('Download failed');
    }
    
    // Get filename from content-disposition header or create one
    const contentDisposition = response.headers.get('content-disposition');
    const filename = contentDisposition 
      ? contentDisposition.split('filename=')[1].replace(/"/g, '')
      : `report-${reportId}.pdf`;
    
    // Create download link and trigger click
    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    
    // Log download (optional)
    await fetch(`fetch/log_download.php?id=${reportId}`, { method: 'POST' });
    
  } catch (error) {
    console.error('Download error:', error);
    alert('Failed to download report. Please try again.');
  }
}

async function handleMyDownload(file) {
    
    try {
    // Assuming your file path pattern is like: downloads/{id}.pdf
    const filePath = `${window.BASE_URL}/${file}`;

    /*const a = document.createElement('a');
    a.href = filePath;
    a.download = `report-${file}`;  // you can customize filename
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);*/
     

    const a = document.createElement('a');
    a.href = filePath;
    a.download = `report-${file}`; // Suggested filename
    a.style.display = 'none';

  document.body.appendChild(a);
  a.click(); // Trigger download
  document.body.removeChild(a);
    // Optionally, log download action
    //await fetch(`fetch/log_download.php?id=${reportId}`, { method: 'POST' });

  } catch (error) {
    console.error('Download error:', error);
    alert('Failed to download report. Please try again.');
  }
}



function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart") || []);
  const cartCounts = document.querySelectorAll('.cart-count');
  
  cartCounts.forEach(counter => {
    const count = cart.length;
    counter.textContent = count;
    counter.dataset.count = count; // Sync with data attribute
    
    // Toggle visibility and background
    if (count > 0) {
      counter.classList.remove('cart-count-hidden');
      counter.classList.add('bg-yellow-400');
    } else {
      counter.classList.add('cart-count-hidden');
      counter.classList.remove('bg-yellow-400');
    }
  });
}

// Navigation function
function navigateToDetails(report) {
  // You can use either URL parameters or localStorage to pass data
  const params = new URLSearchParams();
  params.set('id', report.id);
  params.set('title', encodeURIComponent(report.title));
  params.set('price', report.price);
  params.set('category', encodeURIComponent(report.category));
  params.set('thumbnail', encodeURIComponent(report.thumbnail));
  params.set('downloads', report.download_count || 0);
  
  // Navigate to details page
  window.location.href = `report-details.php?${params.toString()}`;
  
  // Alternative: Store in localStorage and navigate
  // localStorage.setItem('currentReport', JSON.stringify(report));
  // window.location.href = 'report-details.html';
}

// Sync function
async function syncCartItemWithServer(item, action) {
  try {

    // First get user ID
    const user_id = await getCurrentUserId();
    if (!user_id) {
      throw new Error('User not authenticated');
    }

    const response = await fetch('fetch/downloads.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        user_id: user_id, 
        item_id: item.id,
        item_price: item.price,
        action: action
      })
    });
    
    if (!response.ok) throw new Error('Sync failed');
    
    const data = await response.json();
    if (!data.success) throw new Error(data.message || 'Sync failed');
      return true;
  } catch (error) {
    console.error('Cart sync error:', error);
    // Queue for retry later
    queueFailedSync(item, action);
  }
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

// Function to queue failed syncs
function queueFailedSync(item, action) {
  const failedSyncs = JSON.parse(localStorage.getItem('failedSyncs')) || [];
  failedSyncs.push({ item, action, timestamp: Date.now() });
  localStorage.setItem('failedSyncs', JSON.stringify(failedSyncs));
}

// Call the function when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const tabParam = urlParams.get('tab');
  const defaultTab = tabParam === 'register' ? 'register' : 'login';
  fetchReports(urlParams);
            
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
            
// Form submission
submitButton.addEventListener('click', function(e) {
                e.preventDefault();
                

                let isValid = true;
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
                            successPopup.classList.remove('active');
                            popup.classList.add('active');
                            popup.querySelector('button').id = 'error-popup-close';                            
                        } else {
                            errorMessageText.textContent = data.message;
                            errorPopup.classList.add('active');
                        }
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

