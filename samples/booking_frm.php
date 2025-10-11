<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Booking Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #7f8c8d;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .progress-bar {
            display: flex;
            background: #f8f9fa;
            padding: 1.5rem;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .progress-step:not(:last-child):after {
            content: '';
            position: absolute;
            top: 20px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: bold;
            z-index: 2;
            position: relative;
        }

        .step-label {
            font-size: 0.9rem;
            color: #999;
            font-weight: 500;
        }

        .progress-step.active .step-number {
            background: #3498db;
            color: white;
        }

        .progress-step.active .step-label {
            color: #3498db;
        }

        .progress-step.completed .step-number {
            background: #2ecc71;
            color: white;
        }

        .progress-step.completed:not(:last-child):after {
            background: #2ecc71;
        }

        .form-phase {
            padding: 2rem;
            display: none;
        }

        .form-phase.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .radio-group {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .radio-option {
            flex: 1;
        }

        .radio-option input {
            display: none;
        }

        .radio-option label {
            display: block;
            padding: 12px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .radio-option input:checked + label {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .days-options {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .day-option {
            text-align: center;
        }

        .day-option input {
            display: none;
        }

        .day-option label {
            display: block;
            padding: 10px 5px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: normal;
        }

        .day-option input:checked + label {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-prev {
            background: #95a5a6;
            color: white;
        }

        .btn-next, .btn-submit {
            background: #3498db;
            color: white;
        }

        .btn-prev:hover {
            background: #7f8c8d;
        }

        .btn-next:hover, .btn-submit:hover {
            background: #2980b9;
        }

        .btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        /* Payment Method Styles */
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .card-header {
            padding: 1.5rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .card-subtitle {
            color: #7f8c8d;
        }

        .card-body {
            padding: 1.5rem;
        }

        .tabs {
            display: flex;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .tab-button {
            padding: 12px 24px;
            background: none;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab-button.active {
            border-bottom-color: #3498db;
            color: #3498db;
            font-weight: 600;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .info-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .info-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .info-item {
            display: flex;
            margin-bottom: 0.8rem;
        }

        .info-label {
            font-weight: 600;
            width: 150px;
            color: #7f8c8d;
        }

        .info-value {
            flex: 1;
            color: #2c3e50;
        }

        .code-generator {
            margin-bottom: 1.5rem;
        }

        .code-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .code-input-group {
            display: flex;
            gap: 0.5rem;
        }

        .code-input {
            flex: 1;
            background: #f8f9fa;
            cursor: pointer;
        }

        .generate-btn {
            padding: 12px 16px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s;
        }

        .generate-btn:hover {
            background: #2980b9;
        }

        .code-hint {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 0.5rem;
        }

        .instructions {
            background: #fff9e6;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #f1c40f;
        }

        .instructions-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .instructions-list {
            padding-left: 1.5rem;
        }

        .instructions-list li {
            margin-bottom: 0.5rem;
        }

        .highlight {
            background: #f1c40f;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }

        .bank-icon {
            text-align: center;
            font-size: 3rem;
            color: #3498db;
            margin-bottom: 1rem;
        }

        .bank-title {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }

        /* Success Message */
        .success-message {
            text-align: center;
            padding: 3rem 2rem;
            display: none;
        }

        .success-icon {
            font-size: 4rem;
            color: #2ecc71;
            margin-bottom: 1.5rem;
        }

        .success-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .success-text {
            color: #7f8c8d;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .days-options {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .radio-group {
                flex-direction: column;
            }
            
            .code-input-group {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .days-options {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .tabs {
                flex-direction: column;
            }
            
            .tab-button {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Travel Booking Form</h1>
            <p>Complete your booking in two simple steps</p>
        </div>
        
        <div class="form-container">
            <div class="progress-bar">
                <div class="progress-step active" id="step1">
                    <div class="step-number">1</div>
                    <div class="step-label">Booking Details</div>
                </div>
                <div class="progress-step" id="step2">
                    <div class="step-number">2</div>
                    <div class="step-label">Payment</div>
                </div>
            </div>
            
            <form id="booking-form">
                <!-- Phase 1: Booking Details -->
                <div class="form-phase active" id="phase1">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <input type="text" id="nationality" name="nationality" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="travel-date">Travel Date</label>
                        <input type="date" id="travel-date" name="travel-date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="arrival-date">Estimated Date of Arrival</label>
                        <input type="date" id="arrival-date" name="arrival-date" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Number of Trip Days</label>
                        <div class="days-options">
                            <div class="day-option">
                                <input type="radio" id="1-day" name="trip-days" value="1" required>
                                <label for="1-day">1 Day</label>
                            </div>
                            <div class="day-option">
                                <input type="radio" id="2-days" name="trip-days" value="2">
                                <label for="2-days">2 Days</label>
                            </div>
                            <div class="day-option">
                                <input type="radio" id="3-days" name="trip-days" value="3">
                                <label for="3-days">3 Days</label>
                            </div>
                            <div class="day-option">
                                <input type="radio" id="4-days" name="trip-days" value="4">
                                <label for="4-days">4 Days</label>
                            </div>
                            <div class="day-option">
                                <input type="radio" id="5-days" name="trip-days" value="5">
                                <label for="5-days">5 Days</label>
                            </div>
                            <div class="day-option">
                                <input type="radio" id="6-days" name="trip-days" value="6">
                                <label for="6-days">6 Days</label>
                            </div>
                            <div class="day-option">
                                <input type="radio" id="7-days" name="trip-days" value="7">
                                <label for="7-days">7 Days</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="people">Number of People</label>
                        <input type="number" id="people" name="people" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Accommodation Type</label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" id="budget" name="accommodation" value="budget" required>
                                <label for="budget">Budget</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="mid-range" name="accommodation" value="mid-range">
                                <label for="mid-range">Mid Range</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="luxury" name="accommodation" value="luxury">
                                <label for="luxury">Luxury</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="additional-info">Additional Information</label>
                        <textarea id="additional-info" name="additional-info" rows="4"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-prev" disabled>Previous</button>
                        <button type="button" class="btn btn-next" id="to-phase2">Next: Payment</button>
                    </div>
                </div>
                
                <!-- Phase 2: Payment Options -->
                <div class="form-phase" id="phase2">
                    <div class="rounded-xl border bg-card text-card-foreground shadow" id="payment-method-container">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Payment Method</h2>
                                <p class="card-subtitle">Choose your preferred payment method</p>
                            </div>
                            
                            <div class="card-body">
                                <div class="tabs">
                                    <button class="tab-button" data-tab="mobile-money">Mobile Money</button>
                                    <button class="tab-button active" data-tab="bank-transfer">Bank Transfer</button>
                                </div>
                                
                                <div class="tab-content" id="mobile-money">
                                    <div class="info-box">
                                        <h3 class="info-title">Send Payment to Mobile Account:</h3>
                                        <div class="info-item">
                                            <span class="info-label">Account Name:</span>
                                            <span class="info-value">Mbago Musa</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Operator:</span>
                                            <span class="info-value">Airtel Ug</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Account Number:</span>
                                            <span class="info-value">(+256) 701 339 667</span>
                                        </div>
                                    </div>
                                    
                                    <div class="code-generator">
                                        <label class="code-label">Transaction Payment ID</label>
                                        <div class="code-input-group">
                                            <input type="text" class="code-input" id="mobile-code-input" readonly placeholder="Click to generate code">
                                            <button class="generate-btn" id="mobile-generate-btn">
                                                <i class="fas fa-bolt"></i> <span class="text">Get Prompt Code</span>
                                            </button>
                                        </div>
                                        <p class="code-hint">This code will be used as your transaction payment ID</p>
                                    </div>
                                    
                                    <div class="instructions">
                                        <h3 class="instructions-title">Important Instructions:</h3>
                                        <ul class="instructions-list">
                                            <li><span class="highlight">Click on The Get Prompt Code To Generate Code</span></li>
                                            <li>Your download link will be sent to your email after we receive the payment</li>
                                            <li>Please include the <span class="highlight">Transaction Payment ID</span> in your payment reference</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="tab-content active" id="bank-transfer">
                                    <div class="bank-icon">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <h3 class="bank-title">Bank Transfer Details</h3>
                                    
                                    <div class="info-box">
                                        <h3 class="info-title">Please transfer the payment to:</h3>
                                        <div class="info-item">
                                            <span class="info-label">Bank Name:</span>
                                            <span class="info-value">KCB - Kenya Commercial Bank</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Account Name:</span>
                                            <span class="info-value">Mbago Musa</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Account Number:</span>
                                            <span class="info-value">2329505574</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">SWIFT/BIC:</span>
                                            <span class="info-value">KCBLUKAXXX</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Address:</span>
                                            <span class="info-value">Forest Mall, Nakawa Kampala</span>
                                        </div>
                                    </div>
                                    <div class="code-generator">
                                        <label class="code-label">Transaction Payment ID</label>
                                        <div class="code-input-group">
                                            <input type="text" class="code-input" id="bank-code-input" readonly placeholder="Click to generate code">
                                            <button class="generate-btn" id="bank-generate-btn">
                                                <i class="fas fa-bolt"></i> <span class="text">Get Prompt Code</span>
                                            </button>
                                        </div>
                                        <p class="code-hint">This code will be used as your transaction payment ID</p>
                                    </div>
                                    
                                    <div class="instructions">
                                        <h3 class="instructions-title">Important Instructions:</h3>
                                        <ul class="instructions-list">
                                            <li><span class="highlight">Click on The Get Prompt Code To Generate Code</span></li>
                                            <li>Your download link will be sent to your email after we receive the payment</li>
                                            <li>Please include the <span class="highlight">Transaction Payment ID</span> in your payment reference</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-prev" id="to-phase1">Previous</button>
                        <button type="submit" class="btn btn-submit">Complete Booking</button>
                    </div>
                </div>
                
                <!-- Success Message -->
                <div class="success-message" id="success-phase">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="success-title">Booking Submitted Successfully!</h2>
                    <p class="success-text">Thank you for your booking. We have sent a confirmation to your email.</p>
                    <button type="button" class="btn btn-submit" id="new-booking">Make Another Booking</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form navigation
            const phase1 = document.getElementById('phase1');
            const phase2 = document.getElementById('phase2');
            const successPhase = document.getElementById('success-phase');
            const toPhase2Btn = document.getElementById('to-phase2');
            const toPhase1Btn = document.getElementById('to-phase1');
            const submitBtn = document.querySelector('.btn-submit');
            const newBookingBtn = document.getElementById('new-booking');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            
            // Move to phase 2
            toPhase2Btn.addEventListener('click', function() {
                // Validate phase 1
                if (validatePhase1()) {
                    phase1.classList.remove('active');
                    phase2.classList.add('active');
                    step1.classList.remove('active');
                    step1.classList.add('completed');
                    step2.classList.add('active');
                }
            });
            
            // Move back to phase 1
            toPhase1Btn.addEventListener('click', function() {
                phase2.classList.remove('active');
                phase1.classList.add('active');
                step2.classList.remove('active');
                step1.classList.add('active');
            });
            
            // Form submission
            document.getElementById('booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate phase 2
                if (validatePhase2()) {
                    // In a real application, you would send the form data to a server here
                    phase2.classList.remove('active');
                    successPhase.style.display = 'block';
                    step2.classList.remove('active');
                    step2.classList.add('completed');
                }
            });
            
            // New booking
            newBookingBtn.addEventListener('click', function() {
                document.getElementById('booking-form').reset();
                successPhase.style.display = 'none';
                phase1.classList.add('active');
                step1.classList.remove('completed');
                step2.classList.remove('completed');
                step1.classList.add('active');
                
                // Reset payment code inputs
                document.getElementById('mobile-code-input').value = '';
                document.getElementById('bank-code-input').value = '';
            });
            
            // Phase 1 validation
            function validatePhase1() {
                const requiredFields = phase1.querySelectorAll('input[required], select[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        field.style.borderColor = '#e74c3c';
                    } else {
                        field.style.borderColor = '#ddd';
                    }
                });
                
                // Validate email format
                const emailField = document.getElementById('email');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailField.value && !emailRegex.test(emailField.value)) {
                    isValid = false;
                    emailField.style.borderColor = '#e74c3c';
                }
                
                if (!isValid) {
                    alert('Please fill in all required fields correctly.');
                }
                
                return isValid;
            }
            
            // Phase 2 validation
            function validatePhase2() {
                const mobileCode = document.getElementById('mobile-code-input').value;
                const bankCode = document.getElementById('bank-code-input').value;
                
                if (!mobileCode && !bankCode) {
                    alert('Please generate a transaction payment ID for your selected payment method.');
                    return false;
                }
                
                return true;
            }
            
            // Tab switching functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');
                    
                    // Update buttons
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    // Update contents
                    tabContents.forEach(content => content.classList.remove('active'));
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Code generation functionality
            function generateTransactionCode() {
                const prefix = 'TXN';
                const timestamp = Date.now().toString().slice(-2);
                const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
                return `${prefix}${timestamp}${random}`;
            }
            
            // Mobile money code generation
            const mobileGenerateBtn = document.getElementById('mobile-generate-btn');
            const mobileCodeInput = document.getElementById('mobile-code-input');
            
            mobileGenerateBtn.addEventListener('click', () => {
                const generatedCode = generateTransactionCode();
                mobileCodeInput.value = generatedCode;
                
                // Visual feedback
                const originalHtml = mobileGenerateBtn.innerHTML;
                mobileGenerateBtn.innerHTML = '<i class="fas fa-check"></i> <span class="text">Code Generated</span>';
                mobileGenerateBtn.style.background = '#52c41a';
                
                setTimeout(() => {
                    mobileGenerateBtn.innerHTML = originalHtml;
                    mobileGenerateBtn.style.background = '';
                }, 2000);
            });
            
            // Bank transfer code generation
            const bankGenerateBtn = document.getElementById('bank-generate-btn');
            const bankCodeInput = document.getElementById('bank-code-input');
            
            bankGenerateBtn.addEventListener('click', () => {
                const generatedCode = generateTransactionCode();
                bankCodeInput.value = generatedCode;
                
                // Visual feedback
                const originalHtml = bankGenerateBtn.innerHTML;
                bankGenerateBtn.innerHTML = '<i class="fas fa-check"></i> <span class="text">Code Generated</span>';
                bankGenerateBtn.style.background = '#52c41a';
                
                setTimeout(() => {
                    bankGenerateBtn.innerHTML = originalHtml;
                    bankGenerateBtn.style.background = '';
                }, 2000);
            });
            
            // Allow clicking on the input to generate code as well
            mobileCodeInput.addEventListener('click', () => {
                mobileGenerateBtn.click();
            });
            
            bankCodeInput.addEventListener('click', () => {
                bankGenerateBtn.click();
            });
        });
    </script>
</body>
</html>