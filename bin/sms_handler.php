<?php
/**
 * SMS Handler for Leagile Research Center
 * Handles SMS notifications for payment alerts
 */

function sendSMSNotification($message, $phone_number) {

  $api_id = 'API134386739767';
  $pwd = 'leagile@2025';
  $sender_id = 'bulksms';

  $data = array(
    'api_id' => $api_id,
    'api_password' => $pwd,
    'sms_type' => 'P',
    'encoding' => 'T',
    'sender_id' => $sender_id,
    'phonenumber' => $phone_number,
    'textmessage' => $message,
    'templateid' => 'null',
    'V1' => 'null',
    'V2' => 'null',
    'V3' => 'null',
    'V4' => 'null',
    'V5' => 'null',
  );

  $data_string = json_encode($data);

  $context_options = array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-Type: application/json',
      'content' => $data_string,
    ),
  );

  $context = stream_context_create($context_options);
  $url = 'http://apidocs.speedamobile.com/api/SendSMS';
  $result = @file_get_contents($url, false, $context);

  // Determine SMS status
  $status = ($result === false) ? 'failed' : 'sent';

  // Log activity
  logSMSActivity($phone_number, $message, $status, $result);

  /* Optional: Handle failure email alert
  if ($result === false) {
    send_mail(
      'SMS Sending Failed',
      'obacheisaac@gmail.com',
      'Zzimba Online<halo@zzimbaonline.com>',
      "Failed to send SMS to {$phone_number}\n\nMessage: {$message}"
    );
  }*/

  echo $result;
}

function sendPaymentAlertSMS($user_name, $user_email, $transaction_code, $total_amount, $items_count) {
    $message = "PAYMENT ALERT\n";
    $message .= "Email: {$user_email}\n";
    $message .= "Code: {$transaction_code}\n";
    $message .= "Amount: $ {$total_amount}\n";
    $message .= "Please check admin dashboard for details.\n";
    $message .= "Leagile Research Center";

    return sendSMSNotification($message,'256773089254');

    echo $message;
}

/**
 * Log SMS activity
 */
function logSMSActivity($phone_number, $message, $status, $response) {
    $log_entry = array(
        'timestamp' => date('Y-m-d H:i:s'),
        'phone' => $phone_number,
        'message' => substr($message, 0, 100) . '...',
        'status' => $status,
        'response' => $response
    );
    
    $log_file = __DIR__ . '/../logs/sms_log.txt';
    
    // Create logs directory if it doesn't exist
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    // Write to log file
    file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
}

?>