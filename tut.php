<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Example</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .header {
            background: #4a6bdf;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .content {
            padding: 30px;
        }
        
        .email-preview {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }
        
        .email-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .email-body {
            padding: 20px;
            background: white;
        }
        
        .email-footer {
            background: #f8f9fa;
            padding: 15px 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .verification-button {
            display: inline-block;
            background: #4a6bdf;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }
        
        .code-example {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        
        h2 {
            margin: 20px 0 10px;
            color: #2d3748;
        }
        
        p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #4a5568;
        }
        
        .highlight {
            color: #ff6b6b;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Email Verification System</h1>
        </div>
        
        <div class="content">
            <h2>Email Verification Template</h2>
            <p>Below is an example of the verification email that will be sent to users after registration:</p>
            
            <div class="email-preview">
                <div class="email-header">
                    <strong>From:</strong> support@yourwebsite.com<br>
                    <strong>To:</strong> user@example.com<br>
                    <strong>Subject:</strong> Verify Your Email Address
                </div>
                
                <div class="email-body">
                    <h2>Welcome to Our Service!</h2>
                    <p>Hello [User Name],</p>
                    <p>Thank you for registering with us. To complete your registration, please verify your email address by clicking the button below:</p>
                    
                    <center>
                        <a href="#" class="verification-button">Verify Email Address</a>
                    </center>
                    
                    <p>Or copy and paste this link into your browser:</p>
                    <p style="word-break: break-all; color: #4a6bdf;">https://yourwebsite.com/verify.php?token=abc123def456ghi789jkl</p>
                    
                    <p>This verification link will expire in 24 hours for security reasons.</p>
                    
                    <p>If you did not create an account with us, please ignore this email.</p>
                    
                    <p>Best regards,<br>Your Website Team</p>
                </div>
                
                <div class="email-footer">
                    © 2023 Your Website. All rights reserved.
                </div>
            </div>
            
            <h2>PHP Implementation</h2>
            <p>Here's how to implement the email verification in your <span class="highlight">register_process.php</span>:</p>
            
            <div class="code-example">
                <pre><code>&lt;?php
session_start();
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../xsert.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirmPassword']);
    $id = gen_uuid();
    
    if ($password !== $confirm) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit();
    }

    if (!empty($name) && !empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already exists.']);
            exit();
        }

        // Generate verification token
        $verificationToken = bin2hex(random_bytes(32));
        $verificationExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (id, name, email, password, verification_token, verification_expiry, is_verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([$id, $name, $email, $hashedPassword, $verificationToken, $verificationExpiry]);

        // Send verification email
        $verificationLink = "https://yourwebsite.com/verify.php?token=" . $verificationToken;
        
        $to = $email;
        $subject = "Verify Your Email Address";
        
        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: support@yourwebsite.com" . "\r\n";
        
        // Email content
        $message = '&lt;!DOCTYPE html&gt;
        &lt;html&gt;
        &lt;head&gt;
            &lt;meta charset="UTF-8"&gt;
            &lt;title&gt;Verify Your Email&lt;/title&gt;
            &lt;style&gt;
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4a6bdf; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .button { display: inline-block; padding: 12px 24px; background: #4a6bdf; color: white; text-decoration: none; border-radius: 4px; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
            &lt;/style&gt;
        &lt;/head&gt;
        &lt;body&gt;
            &lt;div class="container"&gt;
                &lt;div class="header"&gt;
                    &lt;h1&gt;Verify Your Email Address&lt;/h1&gt;
                &lt;/div&gt;
                &lt;div class="content"&gt;
                    &lt;h2&gt;Welcome to Our Service!&lt;/h2&gt;
                    &lt;p&gt;Hello ' . $name . ',&lt;/p&gt;
                    &lt;p&gt;Thank you for registering with us. To complete your registration, please verify your email address by clicking the button below:&lt;/p&gt;
                    &lt;p style="text-align: center;"&gt;&lt;a href="' . $verificationLink . '" class="button"&gt;Verify Email Address&lt;/a&gt;&lt;/p&gt;
                    &lt;p&gt;Or copy and paste this link into your browser:&lt;br&gt;&lt;span style="word-break: break-all; color: #4a6bdf;"&gt;' . $verificationLink . '&lt;/span&gt;&lt;/p&gt;
                    &lt;p&gt;This verification link will expire in 24 hours for security reasons.&lt;/p&gt;
                    &lt;p&gt;If you did not create an account with us, please ignore this email.&lt;/p&gt;
                    &lt;p&gt;Best regards,&lt;br&gt;Your Website Team&lt;/p&gt;
                &lt;/div&gt;
                &lt;div class="footer"&gt;
                    &lt;p&gt;© 2023 Your Website. All rights reserved.&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/body&gt;
        &lt;/html&gt;';
        
        // Send email
        if (mail($to, $subject, $message, $headers)) {
            echo json_encode(['success' => true, 'message' => 'Account created successfully. Please check your email to verify your account.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Account created but verification email could not be sent.']);
        }
        
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }
}
?&gt;</code></pre>
            </div>
            
            <h2>Verification Page (verify.php)</h2>
            <p>Create a <span class="highlight">verify.php</span> file to handle the verification process:</p>
            
            <div class="code-example">
                <pre><code>&lt;?php
session_start();
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/xsert.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $conn->prepare("SELECT id, verification_expiry FROM users WHERE verification_token = ? AND is_verified = 0");
    $stmt->execute([$token]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if token is expired
        if (strtotime($user['verification_expiry']) < time()) {
            $message = "Verification link has expired. Please register again.";
        } else {
            // Mark user as verified
            $updateStmt = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            $message = "Email verified successfully. You can now login to your account.";
        }
    } else {
        $message = "Invalid or already used verification token.";
    }
} else {
    $message = "No verification token provided.";
}
?&gt;

&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Email Verification&lt;/title&gt;
    &lt;style&gt;
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .message { max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 8px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="message &lt;?php echo isset($user) && strtotime($user['verification_expiry']) >= time() ? 'success' : 'error'; ?&gt;"&gt;
        &lt;h2&gt;&lt;?php echo $message; ?&gt;&lt;/h2&gt;
        &lt;p&gt;&lt;a href="login.php"&gt;Go to Login Page&lt;/a&gt;&lt;/p&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
            </div>
        </div>
    </div>
</body>
</html>