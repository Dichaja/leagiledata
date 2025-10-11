<div class="min-h-screen bg-gray-50 p-4">
  <div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Welcome, <span class="text-primary">User</span></h1>
      <div class="relative">
        <a href="/cart" class="relative">
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <circle cx="8" cy="21" r="1"></circle>
            <circle cx="19" cy="21" r="1"></circle>
            <path d="M2 2h2l3.6 7.59-1.35 2.45A1 1 0 007 14h10a1 1 0 00.9-.55l3.24-6.15H6"></path>
          </svg>
          <span
            class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5">3</span>
        </a>
      </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-white shadow rounded-xl p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p class="text-sm text-muted-foreground">Full Name</p>
          <p class="text-lg font-medium text-gray-900">John Doe</p>
        </div>
        <div>
          <p class="text-sm text-muted-foreground">Subscription Type</p>
          <p class="text-lg font-medium text-green-600">Premium</p>
        </div>
        <div>
          <p class="text-sm text-muted-foreground">Subscription Ends</p>
          <p class="text-lg font-medium text-gray-900">2025-07-01</p>
        </div>
        <div>
          <p class="text-sm text-muted-foreground">Email</p>
          <p class="text-lg font-medium text-gray-900">johndoe@example.com</p>
        </div>
      </div>
    </div>

    <!-- Optional Add-ons: Links or Widgets -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-lg font-semibold mb-2">Recent Activity</h2>
        <ul class="text-sm text-muted-foreground space-y-1">
          <li>✔ Purchased “Energy Report 2024”</li>
          <li>✔ Updated profile info</li>
        </ul>
      </div>

      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-lg font-semibold mb-2">Quick Links</h2>
        <ul class="text-sm space-y-1 text-blue-600">
          <li><a href="/orders" class="hover:underline">View Order History</a></li>
          <li><a href="/subscription" class="hover:underline">Manage Subscription</a></li>
          <li><a href="/logout" class="hover:underline">Sign Out</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php

$subject = 'Payment Approved - Access Your Downloads';

// link to the download page (can also include ?id= if you want to pass payment/report id)
$downloadLink = "https://leagileresearch.com/download.php?id=" . $id;

$body = '<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; background: #f9f9f9;">
            <div style="background: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
                <h1>Payment Approved</h1>
            </div>
            <div style="padding: 20px; background: #ffffff; color: #000; border: 1px solid #e0e0e0; border-top: none;">
                <h2>Hello ' . $name . ',</h2>
                <p>Good news! 🎉 Your payment has been <strong>successfully approved</strong>.</p>
                <p>You can now access your downloads by clicking the button below:</p>
                <p style="text-align: center;">
                    <a href="' . $downloadLink . '" 
                       style="display: inline-block; padding: 12px 24px; background: #4CAF50; color: white; 
                              text-decoration: none; border-radius: 4px; font-weight: bold;">
                        Access Downloads
                    </a>
                </p>
                <p>If the button doesn’t work, copy and paste this link into your browser:</p>
                <p style="word-break: break-all; color: #4CAF50;">' . $downloadLink . '</p>
                <p>If you didn’t make this payment, please contact our support team immediately.</p>
                <p>Best regards,<br>Leagile Research Team</p>
            </div>
            <div style="padding: 15px; text-align: center; font-size: 12px; color: #666; background: #f0f0f0; border-radius: 0 0 8px 8px;">
                <p>&copy; ' . date('Y') . ' Leagile Research. All rights reserved.</p>
            </div>
        </div>';
echo $body;
  ?>
