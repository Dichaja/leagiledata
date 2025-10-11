<?php
session_start();
require_once __DIR__ . '/../bin/functions.php';
require_once __DIR__ . '/../xsert.php'; 

header("Content-Type: application/json");

$msgStatus = '';
$userId = $_SESSION['user_id'] ?? '';
$error = '';
$status = '';
$role = null;


if (!isset($_SESSION['user_id'])) {
    //http_response_code(401);
   $msgStatus = "00";
   $userId = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST) {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $msgStatus = 'Please fill in all fields';
    } else {
        try {
            // Check user existence
            $stmt = $conn->prepare("SELECT id, usr_name, email, password, active_status FROM users WHERE email = ? ");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                if($user['active_status']!='01'){
                   $msgStatus = 'Please Verify Account to Continue';
                   $status = false;
                }else{                 
                 $_SESSION['user_id']    = $user['id'];
                 $_SESSION['user_name']  = $user['usr_name'];
                 $_SESSION['user_email'] = $user['email'];

                 // Check for Admin
                 $qry = $conn->prepare("SELECT * FROM admin_users WHERE user_id = ? ");
                 $qry->execute([$user['id']]);
                 $qryAdmin = $qry->fetch(PDO::FETCH_ASSOC);
                   if ($qryAdmin && isset($qryAdmin['usr_type'])) {
                         $role = $qryAdmin['usr_type'];
                        if ($role === 'Super') {
                             $_SESSION['admin_usr'] = $user['id'];
                          }
                      }

                 $userId = $user['id'];
                 $id = gen_uuid(); 
                 $activity_type = 'login';
                 $description   = 'User logged in';

                 $activity_stmt = $conn->prepare("INSERT INTO user_activities (id, user_id, activity_type, activity_id) VALUES (?, ?, ?, ?)");
                 $activity_stmt->execute([$id, $user['id'], $activity_type, $description]);
                 $status = true;
               }
            } else {
                $msgStatus = 'Invalid Email or Password';
                $status = false;
            }

        } catch (PDOException $e) {
            // Log or display error
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

echo json_encode(['success'=>$status,"user_id" => $userId, 'message'=>$msgStatus, 'error_status'=>$error, 'role'=>$role]);

?>