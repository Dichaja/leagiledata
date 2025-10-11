<?php

//error_reporting(E_ALL ^ E_NOTICE);
//ini_set("include_path", '/home4/zzimba/php:' . ini_get("include_path") );

//include('Mail.php');
//require 'Mail/mime.php';
require_once __DIR__ . '/bin/functions.php';
require_once __DIR__ . '/xsert.php'; 

function send_mail($body,$email,$subject, $conn){

$to = $email;

  $html_content = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Verify Your Email</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4a6bdf; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .button { display: inline-block; padding: 12px 24px; background: #4a6bdf; color: white; text-decoration: none; border-radius: 4px; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>'.$body.'</body>
        </html>';

/*$host = "www.zzimbaonline.com";
$username = "zzimba";
$password = "JJcD@4Rhpv@TyEi";

$headers = array ('From' => $from,
'To' => $to,
'Subject' => $subject);

// create MIME object
$mime = new Mail_mime;

// add body parts
$text = 'Text version of email';
$mime->setTXTBody($text);

$html = '<html><body>HTML version of email</body></html>';
$mime->setHTMLBody($html_content);

// get MIME formatted message headers and body
$body = $mime->get();
$headers = $mime->headers($headers);
$smtp = Mail::factory('smtp',
array ('host' => $host,
'auth' => true,
'username' => $username,
'password' => $password));

if (PEAR::isError($mail)) {
   $status = $mail->getMessage();
}else{
   $status = 'success';
}

  try{
        $mail = $smtp->send($to, $headers, $body); 
        $status = 'success'; 
      } catch (Exception $e) {
         $status = 'error: ' . $e->getMessage();
    }
   return $status;*/
}


?>