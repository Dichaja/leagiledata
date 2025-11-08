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

// Get stats with proper joins and date fields
$downloads_stmt = $conn->prepare("SELECT r.title, r.category, r.description, r.file_size, r.file_type, d.download_status, d.download_at, d.created_at, r.download_url, u.email, u.usr_name, p.trans_id, p.id as payment_id, p.updated_at as action_date, d.id as download_id FROM reports r 
    JOIN report_downloads d ON r.id = d.item_id 
    LEFT JOIN payments p ON d.id = p.trans 
    JOIN users u ON u.id = d.user_id 
    ORDER BY d.created_at DESC");
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
        
        .view-modal {
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
        
        .view-modal-content {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        
        .detail-value {
            color: #666;
            text-align: right;
            max-width: 60%;
            word-wrap: break-word;
        }
        
        .delete-modal {
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
        
        .delete-modal-content {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
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
        <h1 class="text-2xl font-bold text-gray-800">Downloads Management</h1>
        <p class="text-gray-600">Manage user download requests and approvals</p>
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

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors h-9 w-9 hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                    <button class="hidden md:inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors h-9 w-9 hover:bg-gray-100">
                        <i class="fas fa-cog text-gray-600"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile search bar -->
            <div class="md:hidden mb-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    <input class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search downloads...">
                </div>
            </div>
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
                                <span>Download Requests</span>
                            </h3>
                            <p class="text-sm text-muted-foreground mt-1">Manage user download requests and approvals</p>
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
                                
                                // Format dates properly
                                $requested_date = $downloads['created_at'] ? date('M d, Y', strtotime($downloads['created_at'])) : '-';
                                $action_date = ($downloads['action_date'] && $downloads['download_status'] == 'approved') ? date('M d, Y', strtotime($downloads['action_date'])) : '-';
                                ?>
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <td class="p-2 align-middle font-medium"><?php echo htmlspecialchars($downloads['email']) ?></td>
                                        <td class="p-2 align-middle font-medium"><?php echo htmlspecialchars($downloads['title']) ?></td>
                                        <td class="p-2 align-middle font-medium"><?php echo htmlspecialchars($downloads['trans_id'] ?? 'N/A') ?></td>
                                        <td class="p-2 align-middle"><span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full"><?php echo htmlspecialchars($downloads['category']) ?></span></td>
                                        <td class="p-2 align-middle"><span class="status-badge <?php echo $bg_class ?>"><?php echo $status ?></span></td>
                                        <td class="p-2 align-middle"><?php echo $requested_date ?></td>
                                        <td class="p-2 align-middle"><?php echo $action_date ?></td>
                                        <td class="p-2 align-middle text-right">
                                          <div class="action-buttons justify-end">
                                            <?php if($downloads['download_status'] == 'pending'): ?>
                                            <button class="action-btn btn-approve" onclick="approvePay('<?php echo $downloads['download_id'] ?>')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <?php endif; ?>
                                            <button class="action-btn btn-reject" onclick="deleteReport('<?php echo $downloads['download_id'] ?>', '<?php echo htmlspecialchars($downloads['title']) ?>')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            <button class="action-btn btn-view" onclick="viewDetails('<?php echo $downloads['download_id'] ?>')">
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
                    <?php
                    // Reset the statement for mobile view
                    $downloads_stmt->execute();
                    while($download = $downloads_stmt->fetch(PDO::FETCH_ASSOC)) : 
                        $status_class = $download['download_status'] == 'approved' ? 'status-downloaded' : 'status-pending';
                        $requested_date = $download['created_at'] ? date('M d, Y', strtotime($download['created_at'])) : '-';
                    ?>
                        <div class="mobile-card">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold"><?php echo htmlspecialchars($download['title']) ?></h4>
                                <div class="flex items-center mt-1">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2"><?php echo htmlspecialchars($download['category']) ?></span>
                                    <span class="status-badge <?php echo $status_class ?>"><?php echo ucfirst($download['download_status']) ?></span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span>User: <?php echo htmlspecialchars($download['email']) ?></span><br>
                                    <span>Requested: <?php echo $requested_date ?></span>
                                </div>
                            </div>
                            <div class="dropdown relative">
                                <button class="dropdown-toggle p-1 rounded hover:bg-gray-100">
                                    <i class="fas fa-ellipsis-v text-gray-400"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between mt-4 gap-2">
                            <?php if($download['download_status'] == 'pending'): ?>
                            <button class="flex-1 py-2 bg-green-600 text-white rounded-md text-sm font-medium flex items-center justify-center" onclick="approvePay('<?php echo $download['payment_id'] ?>')">
                                <i class="fas fa-check mr-1"></i> Approve
                            </button>
                            <?php endif; ?>
                            <button class="flex-1 py-2 bg-red-600 text-white rounded-md text-sm font-medium flex items-center justify-center" onclick="deleteReport('<?php echo $download['download_id'] ?>', '<?php echo htmlspecialchars($download['title']) ?>')">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                            <button class="flex-1 py-2 bg-blue-600 text-white rounded-md text-sm font-medium flex items-center justify-center" onclick="viewDetails('<?php echo $download['download_id'] ?>')">
                                <i class="fas fa-eye mr-1"></i> View
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
            <p>Action completed successfully!</p>
        </div>
        <div class="modal-footer">
            <button class="control-btn approve-btn" onclick="reloadPage()">OK</button>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="view-modal" id="viewModal">
    <div class="view-modal-content">
        <div class="modal-header">
            <h3><b>Download Request Details</b></h3>
            <button class="modal-close" onclick="closeViewModal()">&times;</button>
        </div>
        <div class="modal-body" id="viewModalBody">
            <!-- Details will be populated here -->
        </div>
        <div class="modal-footer">
            <button class="control-btn approve-btn" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="delete-modal" id="deleteModal">
    <div class="delete-modal-content">
        <div class="modal-header">
            <h3><b>Confirm Delete</b></h3>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this download request?</p>
            <p><strong id="deleteReportTitle"></strong></p>
            <p class="text-sm text-red-600 mt-2">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="control-btn cancel-btn" onclick="closeDeleteModal()">Cancel</button>
            <button class="control-btn btn-reject" onclick="confirmDelete()">Delete</button>
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

        // Function to handle report deletion
        function deleteReport(id, title) {
            document.getElementById('deleteModal').style.display = 'flex';
            document.getElementById('deleteModal').dataset.id = id;
            document.getElementById('deleteReportTitle').textContent = title;
        }
        
        // Function to view report details
        async function viewDetails(id) {
            try {
                const response = await fetch(`${BASE_URL}/fetch/report-details.php?id=${id}`);
                const data = await response.json();
                
                if (data.success) {
                    const details = data.data;
                    const modalBody = document.getElementById('viewModalBody');
                    
                    modalBody.innerHTML = `
                        <div class="detail-row">
                            <span class="detail-label">User:</span>
                            <span class="detail-value">${details.user_name} (${details.email})</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Report Title:</span>
                            <span class="detail-value">${details.title}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">${details.category}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">File Type:</span>
                            <span class="detail-value">${details.file_type || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">File Size:</span>
                            <span class="detail-value">${details.file_size || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value">${details.download_status}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Transaction ID:</span>
                            <span class="detail-value">${details.trans_id || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Requested On:</span>
                            <span class="detail-value">${new Date(details.created_at).toLocaleDateString()}</span>
                        </div>
                        ${details.action_date ? `
                        <div class="detail-row">
                            <span class="detail-label">Action Date:</span>
                            <span class="detail-value">${new Date(details.action_date).toLocaleDateString()}</span>
                        </div>
                        ` : ''}
                        ${details.description ? `
                        <div class="detail-row">
                            <span class="detail-label">Description:</span>
                            <span class="detail-value">${details.description}</span>
                        </div>
                        ` : ''}
                    `;
                    
                    document.getElementById('viewModal').style.display = 'flex';
                } else {
                    alert('Failed to load details: ' + data.message);
                }
            } catch (error) {
                console.error('Error loading details:', error);
                alert('Failed to load details. Please try again.');
            }
        }
        
function closeModal() {
    document.getElementById('approvalModal').style.display = 'none';
}

function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Reload page
function reloadPage() {
    window.location.reload();
}

async function confirmApprove() {
    let payId = document.getElementById('approvalModal');
    let idVal = payId.dataset.id;

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

async function confirmDelete() {
    let deleteModal = document.getElementById('deleteModal');
    let idVal = deleteModal.dataset.id;

    try {
        const response = await fetch(`${BASE_URL}/fetch/downloads.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: await getCurrentUser(),
                item_id: idVal,
                action: 'delete'
            })
        });
        
        const data = await response.json();
        if (data.success) {
            closeDeleteModal();
            document.getElementById('successModal').style.display = 'flex';
        } else {
            alert('Failed to delete: ' + data.message);
        }
    } catch (error) {
        console.error('Delete error:', error);
        alert('Failed to delete. Please try again.');
    }
}
        
        // Close modal if clicked outside
        window.addEventListener('click', function(event) {
            const approvalModal = document.getElementById('approvalModal');
            const viewModal = document.getElementById('viewModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === approvalModal) {
                closeModal();
            }
            if (event.target === viewModal) {
                closeViewModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

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
        item_id: itemId,
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