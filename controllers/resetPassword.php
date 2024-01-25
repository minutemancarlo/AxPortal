<?php
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
$settings = new SystemSettings();
$db = new DatabaseHandler();

$baseURL = $settings->getBaseURL();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email=$_POST['email'];
$existingEmail = $db->select('users', 'email', "email = '$email'");
if ($existingEmail->num_rows > 0) {
  $where="email='".$email."'";
  $password=generateUniqueID();
  $postData=array(
    "password" => password_hash($password, PASSWORD_DEFAULT)
  );
  $insertResult = $db->update('users', $postData, $where);
  if ($insertResult === true) {


      // Define the URL endpoint
      $url = $baseURL.'email/send.php'; // Replace with the actual URL

      // Create an array with the POST data
      $data = array(
        'email' => $email,
        'subject' => "Password Reset",
        'password' => $password,
        'action' => "password",
        'name' => 'N/A'

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
          'message' => 'Success! Please check your email for your new password. Please change it to your preferred password on your profile'
      ];
      header('Content-Type: application/json');
      echo json_encode($response);
}
}
}

function generateUniqueID() {
    $prefix = substr(uniqid(), -5); // Get the last 5 characters of the unique ID
    $randomPart = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5); // Generate a random 5-character alphanumeric string with uppercase letters and numbers

    return strtoupper($prefix . $randomPart); // Convert the result to uppercase
}

 ?>
