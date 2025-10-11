
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
  

  // Handle subscription button clicks
    document.querySelectorAll('.subscribe-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const plan = this.dataset.plan;
            const price = parseFloat(this.dataset.price);
            const billing = this.dataset.billing ? parseFloat(this.dataset.billing) : price;
            
            // Create subscription item
            const subscriptionItem = {
                id: `sub-${plan}`,
                title: `Leagile Research Prime (${plan})`,
                price: price,
                category: "Subscription",
                thumbnail: "/images/premium-icon.png", // Add your image path
                download_url: "", // Not applicable for subscriptions
                is_subscription: true,
                plan_type: plan,
                billing_amount: billing
            };
            
            // Save as single-item cart
            localStorage.setItem('cart', JSON.stringify([subscriptionItem]));
            localStorage.setItem('isSubscription', 'true');
            
            // Redirect to checkout
            window.location.href = 'checkout.php';
        });
    });
    
    // Handle free trial button
    document.querySelector('.free-trial-btn').addEventListener('click', function() {
        // Create free trial item
        const trialItem = {
            id: 'sub-trial',
            title: 'Leagile Research Prime (30-day Trial)',
            price: 0,
            category: "Subscription",
            thumbnail: "/images/premium-icon.png",
            download_url: "",
            is_trial: true
        };
        
        localStorage.setItem('cart', JSON.stringify([trialItem]));
        localStorage.setItem('isTrial', 'true');
        
        window.location.href = 'checkout.php';
    });
  
})