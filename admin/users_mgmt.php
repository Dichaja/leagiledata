<?php
session_start();
require_once('../bin/page_settings.php');
require_once('../bin/functions.php');
require_once('../xsert.php');

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$sql = "SELECT id, usr_name, email, active_status, created_at FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

function statusLabel($status) {
    if ($status === '01') return '<span class="px-2 py-1 rounded status-active">Active</span>';
    return '<span class="px-2 py-1 rounded status-inactive">Inactive</span>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    if (isset($_POST['delete'])) {
        $delStmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delStmt->execute([$userId]);
    } elseif (isset($_POST['disable'])) {
        $disStmt = $conn->prepare("UPDATE users SET active_status = '' WHERE id = ?");
        $disStmt->execute([$userId]);
    } elseif (isset($_POST['activate'])) {
        $actStmt = $conn->prepare("UPDATE users SET active_status = '01' WHERE id = ?");
        $actStmt->execute([$userId]);
    }
    header('Location: users_mgmt.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>
    <?php include('../bin/source_links.php'); ?>
    <style>
        .status-active { background-color: #d1fae5; color: #065f46; }
        .status-inactive { background-color: #fee2e2; color: #991b1b; }
        .actions button {
            margin-right: 5px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.95rem;
        }
        .actions button[name="delete"] {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fee2e2;
        }
        .actions button[name="disable"] {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fef3c7;
        }
        .actions button[name="activate"] {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #d1fae5;
        }
    </style>
</head>
<body>
    <?php siteHeader() ?>
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">User Management</h1>
                <p class="text-gray-600">View, activate, disable, or delete user accounts</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $row): ?>
                        <tr>
                            <td class="px-4 py-2 text-gray-800 font-semibold"><?= htmlspecialchars($row['usr_name']) ?></td>
                            <td class="px-4 py-2 text-gray-700"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="px-4 py-2"><?= statusLabel($row['active_status']) ?></td>
                            <td class="px-4 py-2 text-gray-500 text-sm"><?= htmlspecialchars($row['created_at']) ?></td>
                            <td class="px-4 py-2 actions">
                                <form method="post" style="display:inline">
                                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                    <?php if ($row['active_status'] === '01'): ?>
                                        <button type="submit" name="disable">Disable</button>
                                    <?php else: ?>
                                        <button type="submit" name="activate">Activate</button>
                                    <?php endif; ?>
                                    <button type="submit" name="delete" onclick="return confirm('Delete user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php siteFooter() ?>
</body>
</html>
