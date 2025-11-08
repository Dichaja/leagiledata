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
    <div class="container mx-auto py-12 px-4">
  <div class="max-w-3xl mx-auto">

    <!-- Title and Intro -->
    <h1 class="text-3xl font-bold mb-6 text-center">Support Our Research</h1>
    <p class="text-lg text-gray-700 mb-8 text-center">
      Your donations help us continue providing high-quality research and support our experts in their work.
    </p>

    <!-- How Your Donation Helps (Moved Up) -->
    <div class="mb-12 bg-slate-50 p-6 rounded-lg">
      <h2 class="text-xl font-semibold mb-4">How Your Donation Helps</h2>
      <ul class="space-y-3 list-disc pl-5">
        <li>Fund new research initiatives in emerging fields</li>
        <li>Support our expert researchers and analysts</li>
        <li>Improve our platform and research tools</li>
        <li>Make research more accessible to those who need it most</li>
        <li>Sponsor educational programs and workshops</li>
      </ul>
    </div>

    <!-- Donation Form -->
    <div class="rounded-xl border bg-card text-card-foreground shadow-lg">
      <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="font-semibold tracking-tight text-2xl">Make a Donation</h3>
        <p class="text-sm text-muted-foreground">
          Choose an amount and donation type to support our research initiatives.
        </p>
      </div>

      <div class="p-6 pt-0">
        <form>
          <div class="space-y-6">

            <!-- Donation Type -->
            <div class="space-y-2">
              <label for="donation-type" class="text-sm font-medium">Donation Type</label>
              <div class="gap-2 flex flex-col sm:flex-row sm:space-x-6">
                <!-- One-time -->
                <div class="flex items-center space-x-2">
                  <button type="button" role="radio" aria-checked="true" value="one-time"
                    class="aspect-square h-4 w-4 rounded-full border border-primary text-primary shadow">
                    <svg class="h-3.5 w-3.5 fill-primary" viewBox="0 0 15 15"><path d="..."/></svg>
                  </button>
                  <label for="one-time" class="text-sm font-medium">One-time</label>
                </div>
                <!-- Monthly -->
                <div class="flex items-center space-x-2">
                  <button type="button" role="radio" aria-checked="false" value="monthly"
                    class="aspect-square h-4 w-4 rounded-full border border-primary text-primary shadow"></button>
                  <label for="monthly" class="text-sm font-medium">Monthly</label>
                </div>
                <!-- Annual -->
                <div class="flex items-center space-x-2">
                  <button type="button" role="radio" aria-checked="false" value="annual"
                    class="aspect-square h-4 w-4 rounded-full border border-primary text-primary shadow"></button>
                  <label for="annual" class="text-sm font-medium">Annual</label>
                </div>
              </div>
            </div>

            <!-- Amount -->
            <div class="space-y-2">
              <label for="amount" class="text-sm font-medium">Donation Amount ($)</label>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                <button type="button" class="btn-amount">$25</button>
                <button type="button" class="btn-amount">$50</button>
                <button type="button" class="btn-amount">$100</button>
                <button type="button" class="btn-amount">$250</button>
              </div>
              <div class="flex items-center space-x-3">
                <label for="custom-amount" class="text-sm font-medium whitespace-nowrap">Custom Amount:</label>
                <div class="relative w-full">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                  <input type="number" id="custom-amount" placeholder="Other amount"
                    class="pl-7 input-field w-full" />
                </div>
              </div>
            </div>

            <!-- Personal Info -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium">Personal Information</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <label for="first-name" class="text-sm font-medium">First Name</label>
                  <input id="first-name" required class="input-field w-full" />
                </div>
                <div class="space-y-2">
                  <label for="last-name" class="text-sm font-medium">Last Name</label>
                  <input id="last-name" required class="input-field w-full" />
                </div>
              </div>
              <div class="space-y-2">
                <label for="email" class="text-sm font-medium">Email</label>
                <input type="email" id="email" required class="input-field w-full" />
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end p-6 pt-6 px-0">
            <button type="submit" class="bg-primary text-white h-10 rounded-md px-8 text-sm font-medium">
              Donate $
            </button>
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
</body>
</html>