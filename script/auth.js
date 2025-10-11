document.addEventListener('DOMContentLoaded', function() {

            const urlParams = new URLSearchParams(window.location.search);
            
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
                            // Show success popup
                            successPopup.classList.add('active');
                            
                            // Reset form
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
        successPopup.classList.add('active');
         if (data.success) {          
          console.log(data);
         }else{
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