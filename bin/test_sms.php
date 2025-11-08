<?php


function sendSMSNotification($message, $phone_number = '256773089254') {
    // SMS API credentials
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

    try {
        // Initialize cURL
        $ch = curl_init('http://apidocs.speedamobile.com/api/SendSMS');
        
        // Use JSON format as in working function
        $jsonData = json_encode($data);
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        
        // Execute request
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
        // Debugging (optional)
        // echo "<pre>Response: $response\nHTTP Code: $http_code\n</pre>";

        // Check for cURL errors
        if ($curl_error) {
            error_log("SMS cURL Error: " . $curl_error);
            return array('success' => false, 'error' => 'Connection error: ' . $curl_error);
        }
        
        // Check HTTP response code
        if ($http_code !== 200) {
            error_log("SMS HTTP Error: " . $http_code . " - " . $response);
            return array('success' => false, 'error' => 'HTTP Error: ' . $http_code);
        }
        
        // Return raw response
        return array('success' => true, 'response' => $response);
        
    } catch (Exception $e) {
        error_log("SMS Exception: " . $e->getMessage());
        return array('success' => false, 'error' => $e->getMessage());
    }
}


function messenger($contact, $message)
{

  $api_id = 'API134386739767';
  $pwd = 'leagile@2025';
  $sender_id = 'bulksms';

  $data = array(
    'api_id' => $api_id,
    'api_password' => $pwd,
    'sms_type' => 'P',
    'encoding' => 'T',
    'sender_id' => $sender_id,
    'phonenumber' => $contact,
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

  if ($result === false) {
    send_mail('HTTP request failed.','obacheisaac@gmail.com','Zzimba Online<halo@zzimbaonline.com>',$result);
    $result = 'err';
  }

  return $result . ' - ' . $message;
}

$message = 'PAYMENT ALERT: New payment request from User: Vado, Email: ob@gmail.com, Code: TXR0001, Amount: 10000, Items: Leagile. Please check admin dashboard for details. - Leagile Research Center';
$msg = 'A new payment request has been submitted by Vado (ob@gmail.com) with code TXR0001 for 10,000 related to Leagile';
//$response = sendSMSNotification($message, '256773089254');
$response = messenger('256706103992',$msg);
print_r($response);
?>