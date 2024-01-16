<?php
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
$settings = new SystemSettings();
$db = new DatabaseHandler();

$baseURL = $settings->getBaseURL();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = $_POST;
    $email = $postData['email'];
    $name = $postData['name'];
    $postData['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
    $postData['verification_token'] = generateVerificationToken();
    $token=$postData['verification_token'];

    $existingEmail = $db->select('users', 'email', "email = '$email'");
    if ($existingEmail->num_rows > 0) {
        $response = [
            'success' => false,
            'message' => 'Email already registered.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }else{
      $insertResult = $db->insert('users', $postData);
      if ($insertResult === true) {


          // Define the URL endpoint
          $url = $baseURL.'email/send.php'; // Replace with the actual URL

          // Create an array with the POST data
          $data = array(
            'email' => $email,
            'subject' => "Email Verification",
            'link' => $baseURL."auth/verify.php?q=".$token,
            'name' => ucwords($name),
            'action' => "register"
          );

          // Initialize cURL session
          $ch = curl_init();

          // Set cURL options
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          // Execute cURL request and get the response
          $response = curl_exec($ch);

          // Check for cURL errors
          if (curl_errno($ch)) {
            $error = curl_error($ch);
            // Handle the error as needed
          } else {
            // Process the response
            // $responseData = json_decode($response, true);
            // Access the response data using $responseData array
          }

          // Close cURL session
          curl_close($ch);
          // Successful insert
          $response = [
              'success' => true,
              'message' => 'Success! Please check your email for verification.'
          ];
      } else {
          // Insert failed
          $response = [
              'success' => false,
              'message' => 'Unexpected error occurred.'
          ];
      }
      header('Content-Type: application/json');
      echo json_encode($response);
    }

}

function generateVerificationToken() {
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$tokenLength = 32;
$token = '';

$maxCharIndex = strlen($characters) - 1;

// Generate random characters to form the token
for ($i = 0; $i < $tokenLength; $i++) {
    $token .= $characters[rand(0, $maxCharIndex)];
}

return $token;
}



 ?>
