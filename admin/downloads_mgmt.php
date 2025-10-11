<?php
session_start();
require __DIR__  . '/../bin/page_settings.php';
require __DIR__  . '/../bin/functions.php';
require __DIR__ . '/../xsert.php';
require_once __DIR__ . '/../send_email.php'; 

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    // Get user details
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $user_email = $_SESSION['user_email'];
} else {
   header('Location: ' . BASE_URL . '/login.php');
    exit;
}


// Get stats
$downloads_stmt = $conn->prepare("SELECT r.title, r.category, d.download_status, d.download_at, r.download_url, u.email, d.created_at, p.trans_id, p.id FROM reports r, report_downloads d LEFT JOIN payments p ON d.id = p.trans , users u WHERE r.id = d.item_id AND u.id = d.user_id");
$downloads_stmt->execute();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<script>
    window.BASE_URL = "<?= BASE_URL ?>"; 
</script>
    <?php include  __DIR__  . '/../bin/source_links.php'; ?>
<style type="text/css">
    .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .admin-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .control-btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .approve-btn {
            background-color: #4CAF50;
            color: white;
        }
        
        .approve-btn:hover {
            background-color: #3d8b40;
        }
        
        .cancel-btn {
            background-color: #f44336;
            color: white;
        }
        
        .cancel-btn:hover {
            background-color: #d32f2f;
        }
        .dropdown {
            position: relative;
        }
        
        .dropdown-toggle {
            background: none;
            border: none;
            cursor: pointer;
        }
        
        @media (min-width: 768px) {
            .md\:flex-row {
                flex-direction: row;
            }
            
            .md\:items-center {
                align-items: center;
            }
            
            .md\:block {
                display: block;
            }
            
            .md\:p-6 {
                padding: 24px;
            }
        }
        
        @media (max-width: 767px) {
            .md\:hidden {
                display: block;
            }
            
            .hidden {
                display: none;
            }
            
            .admin-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .control-btn {
                justify-content: center;
            }
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .btn-approve {
            background-color: #4CAF50;
            color: white;
        }
        
        .btn-reject {
            background-color: #f44336;
            color: white;
        }
        
        .btn-view {
            background-color: #2196F3;
            color: white;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        
        .modal-body {
            margin-bottom: 24px;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
</style>
</head>
<body>
<script>
    window.BASE_URL = "<?= BASE_URL ?>"; 
</script>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
  <div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Your Downloads</h1>
        <p class="text-gray-600">2 Item(s)</p>
      </div>
      <div class="container mx-auto py-2 md:py-6 px-2 md:px-4 max-w-6xl w-full bg-white rounded-xl shadow-lg">
        <!-- Header with Tabs -->
        <div class="sticky-header py-4 md:py-0">
            <div class="flex justify-between items-center mb-4 md:mb-6">
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="p-2 rounded-md text-gray-500">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Tab Navigation 
                <div role="tablist" class="mobile-tab-scroll h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground inline-flex space-x-1 md:grid md:grid-cols-7 w-auto">
                    <!-- Dashboard Tab 
                    <button type="button" role="tab" class="justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium transition-all flex items-center gap-2 tab-inactive">
                        <i class="fas fa-chart-bar text-xs md:text-sm"></i>
                        <span class="hidden sm:inline">Dashboard</span>
                    </button>

                    <!-- Downloads Tab (Active) 
                    <button type="button" role="tab" class="justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium transition-all flex items-center gap-2 tab-active">
                        <i class="fas fa-download text-xs md:text-sm"></i>
                        <span class="hidden sm:inline">Downloads</span>
                    </button>

                    <!-- Wishlist Tab 
                    <button type="button" role="tab" class="justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium transition-all flex items-center gap-2 tab-inactive">
                        <i class="fas fa-heart text-xs md:text-sm"></i>
                        <span class="hidden sm:inline">Wishlist</span>
                    </button>

                    <!-- History Tab 
                    <button type="button" role="tab" class="justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium transition-all flex items-center gap-2 tab-inactive">
                        <i class="fas fa-history text-xs md:text-sm"></i>
                        <span class="hidden sm:inline">History</span>
                    </button>

                    <!-- For You Tab 
                    <button type="button" role="tab" class="justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium transition-all flex items-center gap-2 tab-inactive">
                        <i class="fas fa-star text-xs md:text-sm"></i>
                        <span class="hidden sm:inline">For You</span>
                    </button>

                    <!-- Services Tab 
                    <button type="button" role="tab" class="justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium transition-all flex items-center gap-2 tab-inactive">
                        <i class="fas fa-database text-xs md:text-sm"></i>
                        <span class="hidden sm:inline">Services</span>
                    </button>

                    <!-- Profile Tab 
                    <button type="button" role="tab" class="justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium transition-all flex items-center gap-2 tab-inactive">
                        <i class="fas fa-user text-xs md:text-sm"></i>
                        <span class="hidden sm:inline">Profile</span>
                    </button>
                </div>

                <!-- Action Buttons 
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors h-9 w-9 hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                    <button class="hidden md:inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors h-9 w-9 hover:bg-gray-100">
                        <i class="fas fa-cog text-gray-600"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile search bar 
            <div class="md:hidden mb-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    <input class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search downloads...">
                </div>
            </div>-->
        </div>

        <!-- Downloads Panel (Active) -->
        <div role="tabpanel" class="mt-2 space-y-4">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <!-- Panel Header -->
                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                        <div>
                            <h3 class="font-semibold leading-none tracking-tight flex items-center gap-2">
                                <i class="fas fa-download text-blue-600"></i>
                                <span>Downloaded Reports</span>
                            </h3>
                            <p class="text-sm text-muted-foreground mt-1">Access your purchased and downloaded research reports</p>
                        </div>
                        <div class="hidden md:flex items-center gap-2">
                            <div class="relative">
                                <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400"></i>
                                <input class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors pl-9 w-[200px]" placeholder="Search downloads...">
                            </div>
                            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors border border-input bg-background shadow-sm hover:bg-accent h-9 w-9">
                                <i class="fas fa-filter text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block p-4 md:p-6 pt-0">
                    <div class="rounded-md border overflow-hidden">
                        <div class="relative w-full overflow-auto">
                            <table class="w-full caption-bottom text-sm">
                                <thead class="table-header">
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">User</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Report Title</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Transaction ID</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Category</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Status</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Requested On</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Action Date</th>
                                        <th class="h-10 px-2 align-middle font-medium text-muted-foreground text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Repeat this row for each download item -->
                              <?php
                               while($downloads = $downloads_stmt->fetch(PDO::FETCH_ASSOC)) : 

                                $btn_class = '';
                                $bg_class = '';
                                $status='';
                                $activity='';

                                switch ($downloads['download_status']) {
                                    case 'approved':
                                        $status = 'Approved';
                                        $bg_class = 'status-downloaded';
                                        $btn_class = 'downloadReport-btn';
                                        break;
                                    case 'pending':
                                        $status = 'Pending';
                                        $bg_class = 'status-pending';
                                        $activity = 'disabled';
                                        $btn_class = 'disabledReport-btn';
                                        break;
                                    default:
                                        $status = 'Pending';
                                        $bg_class = 'status-pending';
                                        $activity = 'disabled';
                                }
                                ?>
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <td class="p-2 align-middle font-medium"><?php echo $downloads['email'] ?></td>
                                        <td class="p-2 align-middle font-medium"><?php echo $downloads['title'] ?></td>
                                        <td class="p-2 align-middle font-medium"><?php echo $downloads['trans_id'] ?></td>
                                        <td class="p-2 align-middle"><span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full"><?php echo $downloads['category'] ?></span></td>
                                        <td class="p-2 align-middle"><span class="status-badge <?php echo $bg_class ?>"><?php echo $status ?></span></td>
                                        <td class="p-2 align-middle"><?php echo date('M/d/Y',strtotime($downloads['created_at']))?></td>
                                        <td class="p-2 align-middle">-</td>
                                        <td class="p-2 align-middle text-right">
                                          <div class="action-buttons justify-end">
                                            <button class="action-btn btn-approve" onclick="approvePay('<?php echo $downloads['id'] ?>')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button class="action-btn btn-reject" onclick="rejectReport(1)">
                                                <i class="fas fa-times"></i>Delete
                                            </button>
                                            <button class="action-btn btn-view" onclick="viewDetails(1)">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                          </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden p-4 space-y-3">

                    <!-- Mobile Card 1 -->
                    <div class="mobile-card">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold">Global AI Market Trends 2024</h4>
                                <div class="flex items-center mt-1">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">Technology</span>
                                    <span class="status-badge status-downloaded">Downloaded</span>
                                    <span class="text-xs text-gray-500">1/15/2024</span>
                                </div>
                            </div>
                            <div class="dropdown relative">
                                <button class="dropdown-toggle p-1 rounded hover:bg-gray-100">
                                    <i class="fas fa-ellipsis-v text-gray-400"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <button class="flex-1 mr-2 py-2 bg-blue-600 text-white rounded-md text-sm font-medium flex items-center justify-center">
                                <i class="fas fa-download mr-1"></i> Download
                            </button>                            
                        </div>
                    </div>
                    <?php
                      while($download = $downloads_stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <div class="mobile-card">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold"><?php $download['title']?></h4>
                                <div class="flex items-center mt-1">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2"><?php echo $download['category'] ?></span>
                                    <span class="text-xs text-gray-500">1/15/2024</span>
                                </div>
                            </div>
                            <div class="dropdown relative">
                                <button class="dropdown-toggle p-1 rounded hover:bg-gray-100">
                                    <i class="fas fa-ellipsis-v text-gray-400"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <button class="flex-1 mr-2 py-2 bg-blue-600 text-white rounded-md text-sm font-medium flex items-center justify-center">
                                <i class="fas fa-download mr-1"></i> Download
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
<!-- Approval Modal -->
    <div class="modal" id="approvalModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><b>Approve Payment</b></h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="mt-4">
                    <label for="rejectionReason">Paid By</label>
                    <select id="accountType" class="w-full mt-2 p-2 border rounded-md">
                        <option value="">Select</option>
                        <option value="bank">Bank</option>
                        <option value="mobile money">Mobile Money</option>
                    </select>
                </div>
            </div>
            <div class="modal-body">
                <div class="mt-4">
                    <label for="approvalNotes">Account Number:</label>
                    <input type="text" id="accountNo" class="w-full mt-2 p-2 border rounded-md"  />
                </div>
            </div>
            <div class="modal-footer">
                <button class="control-btn cancel-btn" onclick="closeModal()">Cancel</button>
                <button class="control-btn approve-btn" onclick="confirmApprove()">Confirm Approval</button>
            </div>
        </div>
    </div>
    
<!-- Success Modal -->
<div class="modal" id="successModal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><b>Success</b></h3>
            <button class="modal-close" onclick="closeSuccessModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Payment approved successfully!</p>
        </div>
        <div class="modal-footer">
            <button class="control-btn approve-btn" onclick="reloadPage()">OK</button>
        </div>
    </div>
</div>


    <!-- Rejection Modal -->
    <div class="modal" id="rejectionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Reject Report</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject <strong>"Global AI Market Trends 2024"</strong>?</p>
                <div class="mt-4">
                    <label for="rejectionReason">Reason for rejection:</label>
                    <select id="rejectionReason" class="w-full mt-2 p-2 border rounded-md">
                        <option value="">Select a reason</option>
                        <option value="Inappropriate content">Inappropriate content</option>
                        <option value="Low quality">Low quality</option>
                        <option value="Duplicate">Duplicate report</option>
                        <option value="Other">Other</option>
                    </select>
                    <textarea id="rejectionNotes" class="w-full mt-2 p-2 border rounded-md" rows="3" placeholder="Provide additional details..." style="display: none;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="control-btn cancel-btn" onclick="closeModal()">Cancel</button>
                <button class="control-btn btn-reject" onclick="confirmReject()">Confirm Rejection</button>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle functionality
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            alert('Mobile menu would open here. This is a demonstration.');
        });

        // Add interactive effects to buttons
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('touchstart', function() {
                this.classList.add('opacity-70');
            });
            
            button.addEventListener('touchend', function() {
                this.classList.remove('opacity-70');
            });
        });

        // Simple interaction for filter buttons
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // Filter functionality (simplified for demo)
                const filterText = this.textContent.trim();
                if (filterText === 'All') {
                    document.querySelectorAll('tbody tr').forEach(row => {
                        row.style.display = 'table-row';
                    });
                } else {
                    document.querySelectorAll('tbody tr').forEach(row => {
                        const status = row.querySelector('.status-badge').textContent;
                        if (status === filterText) {
                            row.style.display = 'table-row';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            });
        });
        
        // Download button interaction
        document.querySelectorAll('.download-btn:not(.disabled)').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const title = row.querySelector('td:first-child').textContent;
                
                // Show a confirmation message
                const Toast = {
                    init() {
                        this.hideTimeout = null;
                        
                        this.el = document.createElement('div');
                        this.el.className = 'toast';
                        this.el.style.position = 'fixed';
                        this.el.style.bottom = '20px';
                        this.el.style.left = '50%';
                        this.el.style.transform = 'translateX(-50%)';
                        this.el.style.backgroundColor = '#16a34a';
                        this.el.style.color = 'white';
                        this.el.style.padding = '12px 20px';
                        this.el.style.borderRadius = '8px';
                        this.el.style.zIndex = '1000';
                        this.el.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
                        this.el.style.opacity = '0';
                        this.el.style.transition = 'opacity 0.3s';
                        document.body.appendChild(this.el);
                    },
                    
                    show(message) {
                        clearTimeout(this.hideTimeout);
                        
                        this.el.textContent = message;
                        this.el.style.opacity = '1';
                        
                        this.hideTimeout = setTimeout(() => {
                            this.el.style.opacity = '0';
                        }, 3000);
                    }
                };
                
                Toast.init();
                Toast.show(`Downloading: ${title}`);
                
                // In a real application, this would trigger the actual download
            });
        });

// Function to handle report approval
document.addEventListener('DOMContentLoaded', ()=>{

})


  function approvePay(id) {
            document.getElementById('approvalModal').style.display = 'flex';
            document.getElementById('approvalModal').dataset.id = id;
        }     
 // expose to global
window.approvePay = approvePay;

        // Function to handle report rejection
        function rejectReport(id) {
            document.getElementById('rejectionModal').style.display = 'flex';
        }
        
        // Function to view report details
        function viewDetails(id) {
            alert('View details for report ID: ' + id);
        }
        

function closeModal() {
    document.getElementById('approvalModal').style.display = 'none';
    document.getElementById('rejectionModal').style.display = 'none';
}


function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
}

// Reload page
function reloadPage() {
    window.location.reload();
}


async function confirmApprove() {
    let payId = document.getElementById('approvalModal');
    let idVal = payId.dataset.id;  // <-- string UUID

    try {
        const res = await syncCartItemWithServer2(idVal, 'approve'); // pass string
        if (res) {
            closeModal(); // close approval modal
            document.getElementById('successModal').style.display = 'flex';
        }
    } catch (error) {
        console.error('Cart sync errors:', error);
    }
}

        
        // Function to confirm rejection
        function confirmReject() {
            alert('Report rejected successfully!');
            closeModal();
        }
        
        // Show rejection notes when "Other" is selected
        document.getElementById('rejectionReason').addEventListener('change', function() {
            const notesField = document.getElementById('rejectionNotes');
            notesField.style.display = this.value === 'Other' ? 'block' : 'none';
        });
        
        // Close modal if clicked outside
        window.addEventListener('click', function(event) {
            const approvalModal = document.getElementById('approvalModal');
            const rejectionModal = document.getElementById('rejectionModal');
            
            if (event.target === approvalModal) {
                closeModal();
            }
            if (event.target === rejectionModal) {
                closeModal();
            }
        });
        
        /*Select all checkboxes
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.report-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });*/

// Sync function
async function syncCartItemWithServer2(itemId, action) {
  try {
    const accountType = document.getElementById('accountType').value;
    const accountNo = document.getElementById('accountNo').value;

    const user_id = await getCurrentUser();
    if (!user_id) {
      throw new Error('User not authenticated');
    }

    const response = await fetch(`${BASE_URL}/fetch/payments.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        user_id: user_id, 
        item_id: itemId,          // <-- plain string now
        action: action,
        account_type: accountType,
        account_no: accountNo
      })
    });
    
    if (!response.ok) throw new Error('Sync failed');
    
    const data = await response.json();
    if (!data.success) throw new Error(data.message || 'Sync failed');

    console.log("Syncing item:", itemId, "with code:", action);
    return data; // return something usable
  } catch (error) {
    console.error('Cart sync error:', error);
  }
}

async function getCurrentUser() {
  try {
    const response = await fetch(`${BASE_URL}/fetch/auth.php`);
     if (!response.ok) throw new Error('Failed to get user ID');
      const data = await response.json();
      return data.user_id;
  } catch (error) {
    console.error('Error getting user ID:', error);
    return null; // Handle this case in your UI
  }
}

    </script>
  </div>
</main>
<!-- footer section -->
<?php siteFooter() ?>

</html>