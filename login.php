<?php
session_start();
require_once __DIR__ . '/bin/page_settings.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dash/dash.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/bin/source_links.php' ?>
<script>
    window.BASE_URL = "<?= BASE_URL ?>"; 
</script>
</head>
<body>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
  <div class="container mx-auto px-4 py-8">
    <div class="rounded-xl shadow-lg bg-white border border-gray-200 max-w-md mx-auto mt-10">
  <!-- Header -->
  <div class="text-center p-6 border-b">
    <h3 class="text-2xl font-bold">Welcome back</h3>
    <p class="text-sm text-gray-500 mt-1">Sign in to your account or create a new one</p>
  </div>

  <!-- Tabs -->
  <div class="p-6 pt-0">
    <div class="grid grid-cols-2 bg-gray-100 rounded-lg overflow-hidden mb-6">
      <button class="py-2 text-sm font-medium bg-white text-black shadow" id="tab-login" onclick="switchTab('login')">Login</button>
      <button class="py-2 text-sm font-medium text-gray-500 hover:text-black" id="tab-register" onclick="switchTab('register')">Register</button>
    </div>

    <!-- Login Panel -->
    <div id="login-panel">
        <!-- Error message -->
          <?php if (!empty($error)): ?>
              <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
              </div>
        <?php endif; ?>
      <form method="POST" action="login.php" class="space-y-4" id="login-form">
        <div>
          <label for="login-email" class="text-sm font-medium">Email</label>
          <input type="email" id="login-email" name="email" required placeholder="name@example.com"
            class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" />
        </div>
        <div>
          <label for="login-password" class="text-sm font-medium">Password</label>
          <input type="password" id="login-password" name="password" required placeholder="********"
            class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" />
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium" id="login-button">Sign in</button>
      </form>

      <!-- Divider 
                <div class="my-6">
                    <div class="flex items-center justify-center">
                        <span class="w-full border-t"></span>
                        <span class="px-2 text-xs text-gray-400">Or continue with</span>
                        <span class="w-full border-t"></span>
                    </div>
                    <button disabled class="w-full mt-4 border px-4 py-2 rounded-md text-sm text-gray-500 hover:bg-gray-50">
                        Google
                    </button>
                </div>-->
    </div>

    <!-- Register Panel -->
    <div id="register-panel" class="hidden">
      <form id="registration-form" class="space-y-4">
                <div>
                 <label for="reg-name" class="text-sm font-medium">User Name</label>
                 <input type="text" id="reg-name" name="name" placeholder="John Doe"  class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" required/>
                 <div class="error-message" id="name-error">Please enter your full name</div>
                </div>
                <div>
                    <label for="reg-email" class="text-sm font-medium">Email</label>
                    <input type="email" id="reg-email" name="email" placeholder="you@example.com" class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" required>
                    <div class="error-message" id="email-error">Please enter a valid email address</div>
                </div>
                
                <div>
                    <label for="reg-password" class="text-sm font-medium">Password</label>
                    <input type="password" id="reg-password" name="password" placeholder="••••••••" class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" required />
                    <div class="password-strength">
                        <div class="strength-meter" id="strength-meter"></div>
                    </div>
                    <div class="strength-text" id="strength-text">Password strength</div>
                    <div class="error-message" id="password-error">Password must be at least 8 characters</div>
                </div>
                <div>
                    <label for="reg-confirm-password" class="text-sm font-medium">Confirm Password</label>
                    <input type="password" id="reg-confirm-password" name="confirmPassword" class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-blue-500 outline-none" required>
                    <div class="password-match" id="password-match">
                        <span class="match">Passwords match</span>
                        <span class="no-match">Passwords do not match</span>
                    </div>
                </div>
        <div class="flex items-start space-x-2">
          <input type="checkbox" id="agree" required class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="agree" class="text-sm text-gray-600">
            I agree to the <a href="#" class="underline text-blue-600">terms of service</a> and
            <a href="#" class="underline text-blue-600">privacy policy</a>.
          </label>
        </div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium" id="submit-button">
           <span id="button-text">Create account</span>
          <div class="loader" id="loader"></div>
        </button>
      </form>
    </div>
  </div> 
</div>

<div class="success-popup" id="success-popup">
        <div class="popup-content pop-width-small">
            <h2>Registration Successful!</h2>
            <p>We've sent a verification email to your inbox. Please check your email to verify your account.</p>
            <button id="popup-close" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium popup-content-button">OK</button>
      </div>
</div>
    
<!--<div class="error-popup" id="error-popup">
        <div class="popup-content">
            <h2>Registration Failed</h2>
            <p id="error-message-text">An error occurred during registration.</p>
            <button id="error-popup-close" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium">OK</button>
        </div>
    </div>-->

<!-- Tab switch script -->
<script>
 
document.addEventListener('DOMContentLoaded', function() {
            
            const urlParams = new URLSearchParams(window.location.search);
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
                            successPopup.classList.add('active');
                            form.reset();
                            strengthMeter.style.width = '0%';
                            strengthText.textContent = 'Password strength';
                            strengthText.style.color = '#666';
                            passwordMatch.style.display = 'none';
                        } else {
                            // Show error popup with custom message
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
                        
                        // Show error popup
                        errorMessageText.textContent = 'An error occurred during registration. Please try again.';
                        errorPopup.classList.add('active');
     });
  }
});

loginButton.addEventListener('click',function(e){
    e.preventDefault();
    const formData = new FormData(loginFrm);
    fetch('fetch/auth.php', {
            method: 'POST',
            body: formData
        })
    .then(response => response.json())
    .then(data => {
         if (data.success) { 
           if(data.role)
               window.location.href =  `${BASE_URL}/admin/downloads_mgmt.php`;
            else      
               window.location.reload();
         }else{
          successPopup.classList.add('active');
          successPopup.querySelector('h2').textContent = 'Something Went Wrong';
          successPopup.querySelector('p').textContent = data.message;
          successPopup.querySelector('id').textContent = 'error-popup-close';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    })
})
            
// Close success popup
popupClose.addEventListener('click', function() {
    window.location.href = 'login.php';
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

  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>
</body>
</html>