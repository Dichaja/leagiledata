<?php
session_start();
require_once('../bin/page_settings.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Redirect to dashboard
header('Location: dashboard.php');
exit;
?>