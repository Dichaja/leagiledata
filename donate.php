<?php
session_start();
require_once('bin/page_settings.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('bin/source_links.php'); ?>
</head>
<style>
        /* Custom styles for the radio buttons */
        .custom-radio {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        
        .custom-radio input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        
        .radio-circle {
            position: relative;
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #3b82f6;
            transition: all 0.2s ease;
        }
        
        .radio-circle::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: white;
            transition: transform 0.2s ease;
        }
        
        .custom-radio input[type="radio"]:checked + .radio-circle {
            background-color: #3b82f6;
        }
        
        .custom-radio input[type="radio"]:checked + .radio-circle::after {
            transform: translate(-50%, -50%) scale(1);
        }
        
        .custom-radio input[type="radio"]:focus + .radio-circle {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        
        .custom-radio:hover .radio-circle {
            border-color: #2563eb;
        }
    </style>
<body>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
  <div class="container mx-auto px-4 py-8">
    <div class="container mx-auto py-12 px-4">
  <div class="max-w-3xl mx-auto">

    <!-- Title and Intro -->
    <h1 class="text-3xl font-bold mb-6 text-center">Support Our Research</h1>
    <p class="text-lg text-gray-700 mb-8 text-center">
      Your donations help us continue providing high-quality research and support our experts in their work.
    </p>

    <!-- How Your Donation Helps (Moved Up) 
    <div class="mb-12 bg-slate-50 p-6 rounded-lg">
      <h2 class="text-xl font-semibold mb-4">How Your Donation Helps</h2>
      <ul class="space-y-3 list-disc pl-5">
        <li>Fund new research initiatives in emerging fields</li>
        <li>Support our expert researchers and analysts</li>
        <li>Improve our platform and research tools</li>
        <li>Make research more accessible to those who need it most</li>
        <li>Sponsor educational programs and workshops</li>
      </ul>
    </div>-->

    <!-- Donation Form -->
    <div class="rounded-xl border bg-card text-card-foreground shadow-lg">
      <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="font-semibold tracking-tight text-2xl">Make a Donation</h3>
        <p class="text-sm text-muted-foreground">
          Choose an amount and donation type to support our research initiatives.
        </p>
      </div>

      <div class="p-6 pt-0">
  <form id="donationForm" class="space-y-6">
  <div class="space-y-4">
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Donation Type</label>
                <div role="radiogroup" id="donation-type" class="flex flex-col sm:flex-row sm:space-x-6 gap-3">
                    <!-- One-time option -->
                    <label class="custom-radio flex items-center space-x-2 cursor-pointer p-2 rounded-md hover:bg-gray-50 transition-colors">
                        <input type="radio" name="donationType" value="one-time" class="sr-only" checked>
                        <span class="radio-circle"></span>
                        <span class="text-sm font-medium text-gray-700">One-time</span>
                    </label>

                    <!-- Monthly option -->
                    <label class="custom-radio flex items-center space-x-2 cursor-pointer p-2 rounded-md hover:bg-gray-50 transition-colors">
                        <input type="radio" name="donationType" value="monthly" class="sr-only">
                        <span class="radio-circle"></span>
                        <span class="text-sm font-medium text-gray-700">Monthly</span>
                    </label>

                    <!-- Annual option -->
                    <label class="custom-radio flex items-center space-x-2 cursor-pointer p-2 rounded-md hover:bg-gray-50 transition-colors">
                        <input type="radio" name="donationType" value="annual" class="sr-only">
                        <span class="radio-circle"></span>
                        <span class="text-sm font-medium text-gray-700">Annual</span>
                    </label>
                </div>
        </div>
  <!-- Amount -->
  <div class="space-y-2">
    <label class="text-sm font-medium">Donation Amount ($)</label>

    <div id="preset-amounts" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
      <button type="button" data-value="25" class="btn-amount border px-4 py-2 rounded-md text-sm hover:bg-gray-100"> $25 </button>
      <button type="button" data-value="50" class="btn-amount border px-4 py-2 rounded-md text-sm hover:bg-gray-100"> $50 </button>
      <button type="button" data-value="100" class="btn-amount border px-4 py-2 rounded-md text-sm hover:bg-gray-100"> $100 </button>
      <button type="button" data-value="250" class="btn-amount border px-4 py-2 rounded-md text-sm hover:bg-gray-100"> $250 </button>
    </div>

    <!-- Custom -->
    <div class="flex items-center space-x-3">
      <label class="text-sm font-medium whitespace-nowrap">Custom Amount:</label>
        <div class="relative w-full">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
        <input type="number" id="custom-amount" name="amount" class="pl-7 w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-primary outline-none"  placeholder="Other amount">
      </div>
    </div>
  </div>

  <!-- Personal Info -->
  <div class="space-y-4">
    <h3 class="text-lg font-medium">Personal Information</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div class="space-y-2">
        <label class="text-sm font-medium">First Name</label>
        <input required class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-primary outline-none" id="first-name" name="first-name">
      </div>

      <div class="space-y-2">
        <label class="text-sm font-medium">Last Name</label>
        <input required class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-primary outline-none" id="last-name" name="last-name">
      </div>
    </div>

    <div class="space-y-2">
      <label class="text-sm font-medium">Email</label>
      <input type="email" required id="email" class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-primary outline-none" name="email">
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
    <div class="space-y-2">
      <label class="text-sm font-medium">Phone (optional)</label>
      <input class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-primary outline-none" id="phone" name="phone" placeholder="e.g. +2567...">
    </div>

    <div class="space-y-2">
      <label class="text-sm font-medium">Message (optional)</label>
      <input class="w-full mt-1 px-3 py-2 border rounded-md text-sm focus:ring focus:ring-primary outline-none" id="message" name="message" placeholder="Short note (optional)">
    </div>
  </div>

  <!-- Submit -->
  <div class="flex justify-end pt-6">
    <button id="donateBtn" type="submit" class="bg-primary text-white h-10 rounded-md px-8 text-sm font-medium">Donate </button>
  </div>
</form>
      </div>
    </div>

  </div>
</div>

  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>
<script>

const amountButtons = document.querySelectorAll('.btn-amount');
const customAmountInput = document.getElementById('custom-amount');
const donateBtn = document.getElementById('donateBtn');

    document.getElementById('donationForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Preset amount buttons
        ;
        //const donateBtn = document.getElementById('donateBtn');
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Processing...</span>';
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch('fetch/process_donation.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
          if (result.success) {
            // Redirect to checkout to make payment, passing donation id and amount
            const donationId = result.donation_id || '';
            const amt = result.amount || customAmountInput.value || '';
            // You can change the query params as needed by your checkout flow
            window.location.href = `checkout.php?donation_id=${encodeURIComponent(donationId)}&amount=${encodeURIComponent(amt)}&action_type=donate`;
          } else {
            alert('Error: ' + result.message);
          }
        } catch (error) {
            alert('An error occurred. Please try again.');
            console.error('Error:', error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

amountButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      // clear custom amount
      customAmountInput.value = "";

      // remove active state
      amountButtons.forEach(b => b.classList.remove('bg-primary', 'text-white'));

      // set active state
      btn.classList.add('bg-primary', 'text-white');
      customAmountInput.value = `${btn.dataset.value}`
      // update donate button
      donateBtn.textContent = `Donate $${btn.dataset.value}`;
    });
  });

  customAmountInput.addEventListener('input', () => {
    // Remove active styles from preset buttons
    amountButtons.forEach(b => b.classList.remove('bg-primary', 'text-white'));

    if (customAmountInput.value > 0) {
      donateBtn.textContent = `Donate $${customAmountInput.value}`;
    } else {
      donateBtn.textContent = "Donate";
    }
  });
</script>
</body>
</html>