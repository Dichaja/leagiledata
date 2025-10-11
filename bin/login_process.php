<?php
session_start();
require_once '../xsert.php'; // Make sure this connects to your DB properly

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['subscription_type'] = $user['subscription_type'];
            $_SESSION['subscription_end_date'] = $user['subscription_end_date'];
            header("Location: dash.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid credentials.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: login.php");
        exit();
    }
}

?>