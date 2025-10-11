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
  <!-- Header -->
  <div class="flex items-center mb-6">
    <a href="/">
      <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 p-0 mr-2">
        <svg class="lucide lucide-arrow-left h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="m12 19-7-7 7-7"></path>
          <path d="M19 12H5"></path>
        </svg>
        Continue Shopping
      </button>
    </a>
    <h1 class="text-2xl font-bold">Your Cart</h1>
  </div>

  <!-- Main grid layout -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Cart Items Column -->
    <div class="lg:col-span-2">
      <div class="rounded-xl border bg-card text-card-foreground shadow">
        <div class=" p-6">
          <h3 class="font-semibold leading-none tracking-tight flex items-center">
            <svg class="lucide lucide-shopping-cart mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <circle cx="8" cy="21" r="1"></circle>
              <circle cx="19" cy="21" r="1"></circle>
              <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
            </svg>
            Cart Items (<span id="cart-count"></span>)
          </h3>
        </div>
         <div id="cart-items" class="p-6 pt-0">
           <!-- JS will inject items here -->
         </div>
    </div></div>
    <!-- Order Summary -->
    <div>
      <div class="rounded-xl border bg-card text-card-foreground shadow">
        <div class="flex flex-col space-y-1.5 p-6">
          <h3 class="font-semibold leading-none tracking-tight">Order Summary</h3>
        </div>
        <div id="order-summary" class="p-6 pt-0"></div>
        <div class="flex items-center p-6 pt-0">
          <button class="download-btn inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 rounded-md px-8 w-full">
            <svg class="lucide lucide-credit-card mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <rect width="20" height="14" x="2" y="5" rx="2"></rect>
              <line x1="2" x2="22" y1="10" y2="10"></line>
            </svg>
            Proceed to Checkout
          </button>
        </div>
      </div>
      </div>
      <div class="lg:col-div-2 mb-8 lg:mb-0 lg:pr-5">
        <div class="mb-8 space-y-4">
          <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-baseline mb-3">
                            <span class="text-3xl font-extrabold text-gray-900">UGX 100k</span>
                            <span class="ml-2 text-gray-600">/month</span>
                            <span class="ml-auto text-sm bg-indigo-50 text-indigo-700 px-2.5 py-0.5 rounded-full">Flexible</span>
                        </div>
                        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 subscribe-btn" data-plan="monthly" data-price="100000">
                            Subscribe Now
                        </button>
            </div>
       </div>
       <button class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-900 font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 mb-8 free-trial-btn">
                    Try Prime Free for 30 Days
                </button>
         </div>
         <div></div>
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
</main>
<script type="text/javascript">
  
  function renderCartItems() {
  const cartItemsContainer = document.getElementById("cart-items");
  const summaryContainer = document.getElementById("order-summary");

  if (!cartItemsContainer || !summaryContainer) return;

  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  
  // Clear previous content
  cartItemsContainer.innerHTML = '';
  let subtotal = 0;

  if (cart.length === 0) {
    // Show empty cart message
    cartItemsContainer.innerHTML = `
      <div class="p-6 text-center text-muted-foreground">
        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium">Your cart is empty</h3>
        <p class="mt-1 text-sm">Start shopping to add items to your cart.</p>
        <div class="mt-6">
          <a href="categories.php" class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary/90">
            Continue Shopping
          </a>
        </div>
      </div>
    `;
  } else {
    // Render cart items if they exist
    console.log(cart)
    var count=0;
    cart.forEach(item => {
      //removeFromCart(item.id)

      subtotal += item.price;
      if(item.id){
        count++;
      if (item.thumbnail) {
            var imgSrc =  item.thumbnail;
        } else {
           var imgSrc = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e5e7eb'%3E%3Cpath d='M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z'/%3E%3Cpath d='M14 17H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z'/%3E%3C/svg%3E";
        }
      cartItemsContainer.innerHTML += `
        <div class="flex items-start py-4">
          <div class="h-16 w-16 rounded overflow-hidden mr-4 flex-shrink-0">
            <img src="${imgSrc}" alt="${item.title}" class="h-full w-full object-cover">
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
            <p class="text-sm text-muted-foreground mb-2">${item.category}<br>${item.fileType}, ${item.fileSize}</p>
          </div>
          <div class="text-right ml-4">
            <p class="font-medium">$${(item.price).toFixed(2)}</p>
          </div>
        </div>
        <div class="bg-border h-[1px] w-full my-2"></div>
      `;
      }
    });
  }

  const tax = subtotal * 0.08;
  const total = subtotal + tax;

  summaryContainer.innerHTML = `
    <div class="flex justify-between"><span class="text-muted-foreground">Subtotal</span><span>$${subtotal.toFixed(2)}</span></div>
    <div class="shrink-0 bg-border h-[1px] w-full my-2"></div>
    <div class="flex justify-between font-medium text-lg"><span>Total</span><span>$${subtotal.toFixed(2)}</span></div>
  `;

  // Update cart count
  const cartCountElements = document.querySelectorAll('.cart-count');
  cartCountElements.forEach(el => {
    el.textContent = count;//cart.length;
  });
}


function removeFromCart(id) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  cart = cart.filter(item => item.id !== id);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCartItems();
  updateCartCount();
}

function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart") || []);
  const cartCounts = document.getElementById('cart-count');

    const count = cart.length;
    cartCounts.textContent = count;
    
}

// Call the function when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  renderCartItems();
  updateCartCount();
});
  </script>
<!-- footer section -->
<?php siteFooter() ?>
</body>
</html>