<?php
session_start();

require_once __DIR__ . '/xsert.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET) {
    $token   = trim($_GET['token'] ?? '');
    $id = $_GET['id'] ?? '';
    $verificationExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
    $status='01';

 try {
    $stmt = $conn->prepare("UPDATE users SET active_status = :status, verify_expiry = :verify_expiry WHERE verify_token = :token ");
      $stmt->execute([':status' => $status, ':token'=>$token, ':verify_expiry' => $verificationExpiry]);
       if ($stmt->rowCount() > 0) {
            $_SESSION['response'] = 'Account Verified Successfully!';
            header('Location: login.php');
        } else {
            function interpolateQuery($query, $params, $pdo) {
                 foreach ($params as $key => $value) {
                           $query = str_replace($key, $pdo->quote($value), $query);
                     }
                return $query;
               }

              /*$sql = "UPDATE users SET active_status = :status, verify_expiry = :verify_expiry WHERE verify_token = :token";
               $params = [':status' => $status, ':token'=>$token, ':verify_expiry' => $verificationExpiry];

                 echo interpolateQuery($sql, $params, $conn);*/
            echo 'Invalid or Expired Token.';
        }
     
   } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
 }

}

?>