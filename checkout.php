<?php
session_start();
require __DIR__ . '/bin/page_settings.php';
require __DIR__  . '/bin/functions.php';
require __DIR__ . '/xsert.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('bin/source_links.php'); ?>

<style>
   .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .card-subtitle {
            color: #666;
            font-size: 0.9rem;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .tabs {
            display: flex;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        
        .tab-button {
            flex: 1;
            min-width: 140px;
            padding: 12px 16px;
            border: none;
            background: transparent;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            font-size: 0.9rem;
        }
        
        .tab-button.active {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .info-box {
            background: #e6f7ff;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }
        
        .info-title {
            font-weight: 600;
            margin-bottom: 12px;
            color: #1890ff;
            font-size: 1.1rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }
        
        .info-label {
            color: #666;
            font-size: 0.9rem;
            margin-right: 10px;
        }
        
        .info-value {
            font-weight: 500;
            text-align: right;
            flex: 1;
            min-width: 150px;
        }
        
        .instructions {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 16px;
            margin-top: 20px;
        }
        
        .instructions-title {
            font-weight: 600;
            margin-bottom: 12px;
            color: #333;
            font-size: 1.1rem;
        }
        
        .instructions-list {
            padding-left: 20px;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        
        .instructions-list li {
            margin-bottom: 8px;
            color: #555;
        }
        
        .code-generator {
            margin: 24px 0;
        }
        
        .code-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .code-input-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        @media (min-width: 480px) {
            .code-input-group {
                flex-direction: row;
            }
        }
        
        .code-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            min-width: 0;
        }
        
        .code-input:focus {
            outline: none;
            border-color: #1890ff;
            box-shadow: 0 0 0 2px rgba(24, 144, 255, 0.2);
        }
        
        .generate-btn {
            background: #1890ff;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            white-space: nowrap;
        }
        
        .generate-btn:hover {
            background: #0c7cd5;
        }
        
        .code-hint {
            font-size: 0.8rem;
            color: #888;
            margin-top: 6px;
        }
        
        .bank-icon {
            background: #1890ff;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        
        .bank-icon i {
            color: white;
            font-size: 1.5rem;
        }
        
        .bank-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .highlight {
            background: #fff8e1;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            color: #ff8f00;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .card-header {
                padding: 16px;
            }
            
            .card-body {
                padding: 16px;
            }
            
            .card-title {
                font-size: 1.3rem;
            }
            
            .tab-button {
                min-width: 120px;
                padding: 10px 12px;
                font-size: 0.85rem;
            }
            
            .info-item {
                flex-direction: column;
            }
            
            .info-value {
                text-align: left;
                margin-top: 4px;
            }
            
            .generate-btn {
                padding: 12px;
            }
            
            .generate-btn span.text {
                display: none;
            }
            
            .generate-btn i {
                margin-right: 0;
            }
        }

        @media (max-width: 360px) {
            .tabs {
                flex-direction: column;
            }
            
            .tab-button {
                width: 100%;
            }
        }
</style>
</head>
<body>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
  <div class="container mx-auto px-4 py-8">
    <div class="container mx-auto px-4 py-8">
  <!-- Header -->
  <div class="flex items-center mb-6">
    <a href="cart.php">
      <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 p-0 mr-2">
        <svg class="lucide lucide-arrow-left h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="m12 19-7-7 7-7"></path>
          <path d="M19 12H5"></path>
        </svg>
        Back to Shopping
      </button>
    </a>
    <h1 class="text-2xl font-bold">Checkout</h1>
  </div>

  <!-- Main grid layout -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Cart Items Column -->
    <div class="lg:col-span-2">
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
                        <div class="info-item account">
                            <span class="info-label">Account Name:</span>
                            <span class="info-value">Mbago Musa</span>
                        </div>
                        <div class="info-item account">
                            <span class="info-label">Operator:</span>
                            <span class="info-value">Airtel Ug</span>
                        </div>
                        <div class="info-item account">
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
                            <!--<li>After payment, email your receipt to payments@example.com with the code</li>-->
                        </ul>
                    </div>
                </div>
                
                <div class="tab-content  active" id="bank-transfer">
                    <div class="bank-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3 class="bank-title">Bank Transfer Details</h3>
                    
                    <div class="info-box">
                        <h3 class="info-title">Please transfer the payment to:</h3>
                        <div class="info-item account">
                            <span class="info-label">Bank Name:</span>
                            <span class="info-value">KCB - Kenya Commercial Bank</span>
                        </div>
                        <div class="info-item account" >
                            <span class="info-label">Account Name:</span>
                            <span class="info-value">Mbago Musa</span>
                        </div>
                        <div class="info-item account">
                            <span class="info-label">Account Number:</span>
                            <span class="info-value">2329505574</span>
                        </div>
                        <div class="info-item account">
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
                            <!--<li>After payment, email your transfer receipt to payments@leagileresearch.com with the code</li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- Order Summary -->
    <div>
      <div class="rounded-xl border bg-card text-card-foreground shadow">
        <div class="flex flex-col space-y-1.5 p-6">
          <h3 class="font-semibold leading-none tracking-tight">Order Summary</h3>
        </div>
        <div id="order-summary" class="p-6 pt-0">
        <?php
        // Multipurpose order summary: donation or report
        function renderCartItem($conn) {
            $isDonation = isset($_GET['donation_id']);
            $isReport = isset($_GET['report_id']);
            if ($isDonation) {
                $donation_id = $_GET['donation_id'];
                $stmt = $conn->prepare("SELECT donor_name, donor_email, amount, created_at FROM donations WHERE id = :id LIMIT 1");
                $stmt->execute([':id' => $donation_id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    echo '<div class="space-y-1">';
                    echo '<div class="flex justify-between"><span class="text-muted-foreground">Donation by</span><span>' . htmlspecialchars($row['donor_name']) . '</span></div>';
                    echo '<div class="flex justify-between"><span class="text-muted-foreground">Email</span><span>' . htmlspecialchars($row['donor_email']) . '</span></div>';
                    echo '<div class="flex justify-between"><span class="text-muted-foreground">Date</span><span>' . htmlspecialchars($row['created_at']) . '</span></div>';
                    echo '<div class="flex justify-between font-medium text-lg"><span>Donation Amount</span><span>$' . number_format($row['amount'], 2) . '</span></div>';
                    echo '</div>';
                } else {
                    echo '<div class="text-red-600">Donation not found.</div>';
                }
            } elseif ($isReport) {
                $report_id = $_GET['report_id'];
                $stmt = $conn->prepare("SELECT title, price, created_at FROM reports WHERE id = :id LIMIT 1");
                $stmt->execute([':id' => $report_id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    echo '<div class="space-y-1">';
                    echo '<div class="flex justify-between"><span class="text-muted-foreground">Report</span><span>' . htmlspecialchars($row['title']) . '</span></div>';
                    echo '<div class="flex justify-between"><span class="text-muted-foreground">Date</span><span>' . htmlspecialchars($row['created_at']) . '</span></div>';
                    echo '<div class="flex justify-between font-medium text-lg"><span>Price</span><span>$' . number_format($row['price'], 2) . '</span></div>';
                    echo '</div>';
                } else {
                    echo '<div class="text-red-600">Report not found.</div>';
                }
            } else {
                echo '<div class="text-gray-500">No order found. Please start from the donation or report page.</div>';
            }
        }
        renderCartItem($conn);
        ?>
        </div>
      </div>
      </div>
      <!-- Terms -->
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

  </div>
</main>
<!-- Payment Confirmation Popup -->
<div id="payment-confirm-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; max-width:95vw; width:370px; margin:auto; padding:2rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); text-align:center;">
        <h2 style="font-size:1.2rem; font-weight:600; margin-bottom:1rem;">Confirm Your Payment</h2>
        <div style="margin-bottom:1rem;">
            <div><strong>Payment Method:</strong> <span id="modal-payment-method"></span></div>
            <div class="account" id="modal-account-details" style="margin:0.5rem 0 1rem 0; background:#f8fafc; border-radius:8px; padding:0.75rem; text-align:left; font-size:0.97em;"></div>
            <div><strong>Prompt Code:</strong> <span id="modal-prompt-code" style="font-family:monospace;"></span></div>
            <div><strong>Item Cost:</strong> <span id="modal-item-cost"></span></div>
        </div>
        <button id="modal-confirm-btn" style="background:#1890ff; color:#fff; border:none; border-radius:6px; padding:0.5rem 1.5rem; font-weight:500; margin-right:0.5rem;">Confirm</button>
        <button id="modal-cancel-btn" style="background:#eee; color:#333; border:none; border-radius:6px; padding:0.5rem 1.5rem; font-weight:500;">Cancel</button>
    </div>
</div>
<?php siteFooter() ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
  // Tab switching functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

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
            const bankGenerateBtn = document.getElementById('bank-generate-btn');
            const bankCodeInput = document.getElementById('bank-code-input');

            // Modal elements
            const paymentModal = document.getElementById('payment-confirm-modal');
            const modalPaymentMethod = document.getElementById('modal-payment-method');
            const modalPromptCode = document.getElementById('modal-prompt-code');
            const modalPay = document.getElementById('modal-pay-details');
            const modalItemCost = document.getElementById('modal-item-cost');
            const modalConfirmBtn = document.getElementById('modal-confirm-btn');
            const modalCancelBtn = document.getElementById('modal-cancel-btn');

            // Helper to get item cost from order summary
            function getItemCost() {
                const orderSummary = document.getElementById('order-summary');
                if (!orderSummary) return '';
                // Try to find the last .font-medium.text-lg span (the amount)
                const priceSpan = orderSummary.querySelector('.font-medium.text-lg span:last-child');
                return priceSpan ? priceSpan.textContent : '';
            }

            function showPaymentModal(method, code) {
                modalPaymentMethod.textContent = method;
                modalPromptCode.textContent = code;
                modalItemCost.textContent = getItemCost();
                // Set account details
                const accountDiv = document.getElementById('modal-account-details');
                if (method === 'Mobile Money') {
                    accountDiv.innerHTML = `
                        <div><strong>Account Name:</strong> Mbago Musa</div>
                        <div><strong>Operator:</strong> Airtel Ug</div>
                        <div><strong>Account Number:</strong> (+256) 701 339 667</div>
                    `;
                } else if (method === 'Bank Transfer') {
                    accountDiv.innerHTML = `
                        <div><strong>Bank Name:</strong> KCB - Kenya Commercial Bank</div>
                        <div><strong>Account Name:</strong> Mbago Musa</div>
                        <div><strong>Account Number:</strong> 2329505574</div>
                        <div><strong>SWIFT/BIC:</strong> KCBLUKAXXX</div>
                        <div><strong>Address:</strong> Forest Mall, Nakawa Kampala</div>
                    `;
                } else {
                    accountDiv.innerHTML = '';
                }
                paymentModal.style.display = 'flex';
            }
            function hidePaymentModal() {
                paymentModal.style.display = 'none';
            }

            // Mobile money handler
            mobileGenerateBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const generatedCode = generateTransactionCode();
                showPaymentModal('Mobile Money', generatedCode);

                // On confirm, fill input and do original logic
                modalConfirmBtn.onclick = function() {
                    mobileCodeInput.value = generatedCode;
                    hidePaymentModal();
                    // Visual feedback
                    const originalHtml = mobileGenerateBtn.innerHTML;
                    mobileGenerateBtn.innerHTML = '<i class="fas fa-check"></i> <span class="text">Code Generated</span>';
                    mobileGenerateBtn.style.background = '#52c41a';
                    setTimeout(() => {
                        mobileGenerateBtn.innerHTML = originalHtml;
                        mobileGenerateBtn.style.background = '';
                        cart.forEach(item => {
                            syncCartItemWithServer(item, generatedCode);
                        });
                    }, 2000);
                };
                modalCancelBtn.onclick = function() {
                    hidePaymentModal();
                };
            });

            // Bank transfer handler
            bankGenerateBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const generatedCode = generateTransactionCode();
                showPaymentModal('Bank Transfer', generatedCode);

                modalConfirmBtn.onclick = function() {
                    bankCodeInput.value = generatedCode;
                    hidePaymentModal();
                    // Visual feedback
                    const originalHtml = bankGenerateBtn.innerHTML;
                    bankGenerateBtn.innerHTML = '<i class="fas fa-check"></i> <span class="text">Code Generated</span>';
                    bankGenerateBtn.style.background = '#52c41a';
                    setTimeout(() => {
                        bankGenerateBtn.innerHTML = originalHtml;
                        bankGenerateBtn.style.background = '';
                        cart.forEach(item => {
                            syncCartItemWithServer(item, generatedCode);
                        });
                    }, 2000);
                };
                modalCancelBtn.onclick = function() {
                    hidePaymentModal();
                };
            });
            
            // Allow clicking on the input to generate code as well
            mobileCodeInput.addEventListener('click', () => {
                mobileGenerateBtn.click();
            });
            
            bankCodeInput.addEventListener('click', () => {
                bankGenerateBtn.click();
            });

    // JS cart rendering removed: now handled by PHP for this page

  });

 
// function renderCartItem(cart) { /* removed: now handled by PHP */ }

function handleDirectDownload() {
  if (localStorage.getItem('directDownload') === 'true') {
    // Optional: Highlight download section or auto-scroll
    const downloadSection = document.getElementById('download-section');
    if (downloadSection) {
      downloadSection.scrollIntoView({ behavior: 'smooth' });
      downloadSection.classList.add('ring-2', 'ring-blue-500', 'rounded-lg');
    }
    
    // Clear the flag
    localStorage.removeItem('directDownload');
  }
}

// Sync function
async function syncCartItemWithServer(item, action) {
  try {
    const urlParams = new URLSearchParams(window.location.search);
    const getType = urlParams.get('action_type');
    const donate_id = urlParams.get('donation_id');
    // First get user ID
    const user_id = await getCurrentUserId();
    if (!user_id) {
      throw new Error('User not authenticated');
    }

    const response = await fetch('fetch/payments.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        user_id: user_id, 
        item_id: item.id,
        action: action,
        actionType: getType,
        donate: donate_id
      })
    });
    
    if (!response.ok) throw new Error('Sync failed');
    
    const data = await response.json();
    if (!data.success) throw new Error(data.message || 'Sync failed');
    console.log("Syncing item:", item, "with code:", action);
  } catch (error) {
    console.error('Cart sync error:', error);
    // Queue for retry later
    //queueFailedSync(item, action);
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

</script>
</body>
</html>