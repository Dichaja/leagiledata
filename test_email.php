<?php 

$to = "workusent@gmail.com"; 
$subject = "SMTP Test"; 
$message = "This is a test email from WAMP server"; 
$headers = "From: your.email@gmail.com"; 

if(mail($to, $subject, $message, $headers)) { 
	echo "Email sent successfully!"; 
} else { 
	echo "Failed to send email."; 
} 

?>