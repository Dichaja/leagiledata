<?php
session_start();
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../xsert.php'; 
require_once __DIR__ . '/../send_email.php'; 

header('Content-Type: application/json');

try {

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
        //echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        //exit();
    }


    $name = trim($_POST['name']);
    $email =  trim($_POST['email']);
    $password = trim($_POST['password']);
    $id = gen_uuid();
    $active_status = '00';
    $msg_status = ''; 
    $status = '';
    $error = '';

 if (!empty($name) && !empty($email) && !empty($password)) {

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email Already Exists.']);
            exit();
      } else {

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $subscriptionType = 'Free';
        $subscriptionEndDate = date('Y-m-d', strtotime('+30 days'));

        $subject = 'Account Verification';
        $verificationToken = bin2hex(random_bytes(32));
        $verificationLink = "https://leagileresearch.com/verify.php?token=" . $verificationToken . "&id=" . $id; 

$body = '<div style="max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4a6bdf; color: white; padding: 20px; text-align: center;">
                <div class="header">
                    <h1>User Account Verification</h1>
                </div>
                <div style="padding: 20px; background: #f9f9f9;color:#000;">
                    <h2>Welcome to Our Service!</h2>
                    <p>Hello ' . $name . ',</p>
                    <p>Thank you for registering with us. To complete your registration, please verify your email address by clicking the button below:</p>
                    <p style="text-align: center;"><a href="' . $verificationLink . '" style="display: inline-block; padding: 12px 24px; background: #4a6bdf; color: white; text-decoration: none; border-radius: 4px;">Click Here To Verify</a></p>
                    <p>Or copy and paste this link into your browser:<br><span style="word-break: break-all; color: #4a6bdf;">' . $verificationLink . '</span></p>
                    <p>This verification link will expire in 24 hours for security reasons.</p>
                    <p>If you did not create an account with us, please ignore this email.</p>
                    <p>Best regards,<br>Your Website Team</p>
                </div>
                <div style="padding: 20px; text-align: center; font-size: 12px; color: #666;">
                    <p>&copy; '.date('Y').' Leagile Research. All rights reserved.</p>
                </div>
            </div>';
     
         $stmt = $conn->prepare("INSERT INTO users (id, usr_name, email, password, active_status, verify_token) VALUES (?, ?, ?, ?, ?, ?)");
         $stmt->execute([$id, $name, $email, $hashedPassword, $active_status, $verificationToken]);
         $msg_status = 'Account created successfully. Please check your email to verify your account.'; 
         /*if(send_mail($body, $email, $subject, $conn) =='success'){
            $status = true;  
          } else {
              $status = false;
              $error = send_mail($body, $email, $subject, $conn);
              $msg_status = 'Something Went Wrong!';
          }*/
       }
        echo json_encode(['success' => true, 'message' => $msg_status, 'errorStatus'=>$error]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'All fields are required.', 'errorStatus'=>'']);
        exit();
      }
    } catch (PDOException $e) {
         http_response_code(500);
         echo json_encode(['success' => false, 'message' => 'Something Went Wrong', 'errorStatus'=>$e->getMessage()]);
     }

?>