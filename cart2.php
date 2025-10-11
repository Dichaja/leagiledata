<?php
session_start();
require_once('bin/page_settings.php');
?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Leagile Data Research Center</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="tail.css" />
  <link rel="icon" type="image/png" href="img_data/logo_fav.png" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <?php siteHeader() ?>

  <main class="flex-grow">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex items-center mb-6">
        <a href="/">
          <button class="inline-flex items-center justify-center h-9 p-0 mr-2 rounded-md text-sm font-medium hover:bg-accent hover:text-accent-foreground">
            <svg class="lucide lucide-arrow-left h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="m12 19-7-7 7-7"></path>
              <path d="M19 12H5"></path>
            </svg>
            Continue Shopping
          </button>
        </a>
        <h1 class="text-2xl font-bold">Your Cart</h1>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
          <div class="rounded-xl border bg-card text-card-foreground shadow">
            <div class="p-6">
              <h3 class="font-semibold tracking-tight flex items-center">
                <svg class="lucide lucide-shopping-cart mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="8" cy="21" r="1"></circle>
                  <circle cx="19" cy="21" r="1"></circle>
                  <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                Cart Items
              </h3>
            </div>
            <!-- ✅ Updated ID: cart-items -->
            <div id="cart-items" class="p-6 pt-0"></div>
          </div>
        </div>

        <!-- Order Summary -->
        <div>
          <div class="rounded-xl border bg-card text-card-foreground shadow">
            <div class="p-6">
              <h3 class="font-semibold tracking-tight">Order Summary</h3>
            </div>
            <!-- ✅ Added ID: order-summary -->
            <div id="order-summary" class="p-6 pt-0"></div>
            <div class="flex items-center p-6 pt-0">
              <button class="w-full h-10 px-8 rounded-md bg-primary text-primary-foreground hover:bg-primary/90">
                <svg class="lucide lucide-credit-card mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                  <line x1="2" x2="22" y1="10" y2="10"></line>
                </svg>
                Proceed to Checkout
              </button>
            </div>
          </div>

          <div class="mt-4 text-sm text-muted-foreground">
            <p class="mb-2">
              By proceeding to checkout, you agree to our 
              <a class="underline hover:text-primary" href="/terms">Terms of Service</a>.
            </p>
            <p>
              Need help? Contact our 
              <a class="underline hover:text-primary" href="/support">customer support</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script type="text/javascript">
    function renderCartItems() {
      const cartItemsContainer = document.getElementById("cart-items");
      const summaryContainer = document.getElementById("order-summary");

      if (!cartItemsContainer || !summaryContainer) return;

      const cart = JSON.parse(localStorage.getItem("cart")) || [];
      cartItemsContainer.innerHTML = '';
      let subtotal = 0;

      cart.forEach(item => {
        subtotal += item.price * item.qty;
        cartItemsContainer.innerHTML += `
          <div class="flex items-start py-4">
            <div class="h-16 w-16 rounded overflow-hidden mr-4 flex-shrink-0">
              <img src="${item.thumbnail}" alt="${item.title}" class="h-full w-full object-cover">
            </div>
            <div class="flex-grow">
              <div class="flex justify-between">
                <h3 class="font-medium text-sm md:text-base">${item.title}</h3>
                <button onclick="removeFromCart('${item.id}')">
                  <svg class="lucide lucide-x h-4 w-4 text-gray-500 hover:text-red-600" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
              </div>
              <p class="text-sm text-muted-foreground mb-2">${item.category}</p>
              <div class="flex items-center mt-2">
                <span class="mx-2 text-sm">${item.qty}</span>
              </div>
            </div>
            <div class="text-right ml-4">
              <p class="font-medium">$${(item.price * item.qty).toFixed(2)}</p>
            </div>
          </div>
          <div class="bg-border h-[1px] w-full my-2"></div>
        `;
      });

      const tax = subtotal * 0.08;
      const total = subtotal + tax;

      summaryContainer.innerHTML = `
        <div class="flex justify-between"><span class="text-muted-foreground">Subtotal</span><span>$${subtotal.toFixed(2)}</span></div>
        <div class="flex justify-between"><span class="text-muted-foreground">Tax (8%)</span><span>$${tax.toFixed(2)}</span></div>
        <div class="shrink-0 bg-border h-[1px] w-full my-2"></div>
        <div class="flex justify-between font-medium text-lg"><span>Total</span><span>$${total.toFixed(2)}</span></div>
      `;

      console.log("Cart Items:", cart);
    }

    document.addEventListener('DOMContentLoaded', renderCartItems);
  </script>

  <?php siteFooter() ?>
</body>
</html>
