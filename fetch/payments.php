<?php

require_once __DIR__ . '/../xsert.php';
require_once __DIR__ . '/../bin/functions.php';
require_once __DIR__ . '/../send_email.php';
require_once __DIR__ . '/../bin/sms_handler.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$id = gen_uuid();

if (!isset($input['user_id'], $input['item_id'], $input['action'], $input['actionType'], $input['donate'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

try {
    if ($input['action'] === 'add') {
        // Check if report exists
        $stmt = $conn->prepare("SELECT id FROM report_downloads WHERE user_id = :user_id AND item_id = :item_id");
        $stmt->execute([
            ':user_id' => $input['user_id'],
            ':item_id' => $input['item_id']
        ]);
        $qryReport = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($qryReport) {
            // Insert into payments
            $stmt = $conn->prepare("INSERT INTO payments 
                (id, trans, trans_id, acc_frm, acc_paid_to, pay_status, created_at, updated_at) 
                VALUES (:id, :trans, :trans_id, '', '', '00', NOW(), NOW())");
            $stmt->execute([
                ':id' => $id,
                ':trans' => $qryReport['id'],
                ':trans_id' => $input['action']
            ]);
        }

    } elseif (strpos($input['action'], 'TXN') === 0) {
        // This is a transaction code generation - send SMS notification
        session_start();
        $activeUserId = $_SESSION['user_id'] ?? null;
        $activeUserName = $_SESSION['user_name'] ?? '';
        $activeUserEmail = $_SESSION['user_email'] ?? '';
        $category = 'Report Download';
        $smsAmount = '';
        $smsItems = 1;
        if ($input['actionType'] == 'donate') {
            
            try{
                $donation_id = $input['donate'];
                $stmt = $conn->prepare("SELECT id, donor_name, donor_email, amount FROM donations WHERE id = :id ");
                $stmt->execute([':id' => $donation_id]);
                $donation = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$donation) {
                            throw new Exception("Donation not found for ID: $donation_id");
                }
            if ($donation) {
                $category = 'Donation';
                $smsAmount = number_format($donation['amount'], 2);
                $activeUserName = $donation['donor_name'];
                $activeUserEmail = $donation['donor_email'];
                $stmt = $conn->prepare("INSERT INTO payments (id, trans, trans_id, acc_frm, acc_paid_to, pay_status, created_at, updated_at) VALUES (:id, :trans, :trans_id, '', '', '00', NOW(), NOW())");

                $success = $stmt->execute([
                     ':id' => gen_uuid(),
                     ':trans' => $donation['id'],
                     ':trans_id' => $input['action']
                 ]);

    if (!$success) {
        throw new Exception("Failed to insert payment record");
    }

    echo json_encode(["success" => true, "message" => "Donation payment recorded"]);
               }
            }catch(Exception $e){
                echo json_encode(["error" => "Donate Error: " . $e->getMessage()]);
            }
        } else if(($input['actionType'] == 'report')) {
            // Get cart items for this user to calculate total
            $stmt = $conn->prepare("
                SELECT rd.*, r.title, r.price 
                FROM report_downloads rd 
                JOIN reports r ON rd.item_id = r.id 
                WHERE rd.user_id = :user_id AND rd.download_status = 'pending'
            ");
            $stmt->execute([':user_id' => $activeUserId]);
            $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $smsAmount = 0;
            $smsItems = count($cart_items);
            foreach ($cart_items as $item) {
                $smsAmount += $item['price'];
            }
            // Update or insert payment record with transaction code
            $stmt = $conn->prepare("SELECT id, item_price FROM report_downloads WHERE user_id = :user_id AND item_id = :item_id");
            $stmt->execute([
                ':user_id' => $activeUserId,
                ':item_id' => $input['item_id']
            ]);
            $qryReport = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($qryReport) {
                // Check if payment record exists
                $stmt = $conn->prepare("SELECT id FROM payments WHERE trans = :trans");
                $stmt->execute([':trans' => $qryReport['id']]);
                $existing_payment = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($existing_payment) {
                    // Update existing payment with transaction code
                    $stmt = $conn->prepare("UPDATE payments SET trans_id = :trans_id, updated_at = NOW() WHERE trans = :trans");
                    $stmt->execute([
                        ':trans_id' => $input['action'],
                        ':trans' => $qryReport['id']
                    ]);
                } else {
                    // Insert new payment record
                    $smsAmount = $qryReport['item_price'];
                    $stmt = $conn->prepare("INSERT INTO payments 
                        (id, trans, trans_id, acc_frm, acc_paid_to, pay_status, created_at, updated_at) 
                        VALUES (:id, :trans, :trans_id, '', '', '00', NOW(), NOW())");
                    $stmt->execute([
                        ':id' => $id,
                        ':trans' => $qryReport['id'],
                        ':trans_id' => $input['action']
                    ]);
                }
            }
        }
        //Send SMS notification to admin (general, with category)
        $sms_result = sendPaymentAlertSMS($activeUserEmail, $input['action'], $smsAmount, $category);
        
        // Log SMS activity
        logSMSActivity(
            '256773089254',
            "Payment alert for user: $activeUserName ($category)",
            $sms_result['status'],
            $sms_result
        );

    } elseif ($input['action'] === 'approve') {

        $accFrom = $input['account_type'] ?? '';
        $accNo   = $input['account_no'] ?? '';
        $accountDetails = $accFrom . ' - ' . $accNo;

        // Update payments
        $stmt = $conn->prepare("UPDATE payments SET acc_frm = :acc_frm, pay_status = '01', updated_at = NOW()  WHERE trans = :download_id");
        $stmt->execute([
            ':acc_frm' => $accountDetails,
            ':download_id' => $input['item_id']
        ]);

        // Update report_downloads
        $stmt = $conn->prepare("SELECT r.id, u.usr_name, u.email  FROM report_downloads r JOIN users u ON r.user_id = u.id JOIN payments p ON r.id = p.trans WHERE p.trans = :pay_id");
        $stmt->execute([':pay_id' => $input['item_id']]);
        $qryReport = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($qryReport) {

            $subject = 'Payment Approved - Access Your Downloads';

// link to the download page (can also include ?id= if you want to pass payment/report id)
$downloadLink = "https://leagileresearch.com/download.php?id=" . $id;

$body = '<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; background: #f9f9f9;">
            <div style="background: #4a6bdf; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
                <h1>Payment Approved</h1>
            </div>
            <div style="padding: 20px; background: #ffffff; color: #000; border: 1px solid #e0e0e0; border-top: none;">
                <h2>Hello ' . $qryReport['usr_name'] . ',</h2>
                <p>Good news! Your payment has been <strong>Successfully approved</strong>.</p>
                <p>You can now access your downloads by clicking the button below:</p>
                <p style="text-align: center;">
                    <a href="' . $downloadLink . '" 
                       style="display: inline-block; padding: 12px 24px; background: #4CAF50; color: white; 
                              text-decoration: none; border-radius: 4px; font-weight: bold;">
                        Access Downloads
                    </a>
                </p>
                <p>If the button doesn\'t work, copy and paste this link into your browser:</p>
                <p style="word-break: break-all; color: #4CAF50;">' . $downloadLink . '</p>
                <p>If you didn\'t make this payment, please contact our support team immediately.</p>
                <p>Best regards,<br>Leagile Research Team</p>
            </div>
            <div style="padding: 15px; text-align: center; font-size: 12px; color: #666; background: #f0f0f0; border-radius: 0 0 8px 8px;">
                <p>&copy; ' . date('Y') . ' Leagile Research. All rights reserved.</p>
            </div>
        </div>';
        
        /*if(send_mail($body, $email, $subject, $conn) =='success'){
            $status = true;  
          } else {
              $status = false;
              $error = send_mail($body, $email, $subject, $conn);
              $msg_status = 'Something Went Wrong!';
          }*/

            $stmt = $conn->prepare("UPDATE report_downloads SET download_status = 'approved' WHERE id = :id");
            $stmt->execute([':id' => $qryReport['id']]);
        }

    } elseif ($input['action'] === 'remove') {
        $stmt = $conn->prepare("UPDATE report_downloads 
            SET download_status = 'pending', cart_added_at = NULL, last_accessed_at = NOW() 
            WHERE user_id = :user_id AND item_id = :item_id");
        $stmt->execute([
            ':user_id' => $input['user_id'],
            ':item_id' => $input['item_id']
        ]);
    }

    echo json_encode(["success" => true, "message" => "Sync completed"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}