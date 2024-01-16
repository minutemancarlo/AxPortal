<?php
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';
$settings = new SystemSettings();
$session = new CustomSessionHandler();
// Assuming the form data is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_COOKIE['Password'])) {
      $_COOKIE['Password'] = password_hash($_COOKIE['Password'], PASSWORD_DEFAULT);  
  }
    // Retrieve the email and password from the form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $db = new DatabaseHandler();
    $result = $db->select("users", "*", "email = '$email'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];
        $role = $row['role'];
        $name = ucwords($row['name']);
        $email = $row['email'];
        $ID = $row['id'];

        // Verify the provided password against the stored password using password_verify()
        if (password_verify($password, $storedPassword)) {
            $isVerified = $row['is_verified'];
            $isActive = $row['is_active'];

            if ($isVerified == 1 && $isActive == 1) {
                $response = array(
                    'success' => true,
                    'message' => 'Login Successful'
                );
                $logMessage="User ".$email." login successful.";
                $settings->createLogFile("LoginController", $logMessage);
                $session->setSessionVariable("Role",$role);
                $session->setSessionVariable("Name",$name);
                $session->setSessionVariable("Id",$ID);
                $session->setSessionVariable("Email",$email);

            } else {
              if ($isActive == 0) {
                $response = array(
                    'success' => false,
                    'message' => 'Your account is deactivated. Contact Administrator to re-activate your account.'
                );
              }else{
                $response = array(
                    'success' => false,
                    'message' => 'Email Address not verified'
                );
              }
            }
        } else {
            $response = array(
                'success' => false,
                'message' => 'Invalid Credentials'
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Invalid Credentials'
        );
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
