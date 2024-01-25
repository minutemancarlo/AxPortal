<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require 'phpmailer/vendor/autoload.php';
require_once '../classess/SystemSettings.php';
$settings = new SystemSettings();
$config = parse_ini_file('../config.ini', true);

// Access email settings
$emailHost = $config['email']['host'];
$emailUsername = $config['email']['username'];
$emailPort = $config['email']['port'];
$emailPassword = $config['email']['password'];



$email = $_POST['email'];
$subject = $_POST['subject'];
$link = $_POST['link'];
$action=$_POST['action'];
$name=$_POST['name'];




    //Create a new PHPMailer instance
    $mail = new PHPMailer();

    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = 0;
    $mail->IsSMTP();
    $mail->Host = $emailHost;
    $mail->Port = $emailPort;//465

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    $mail->SMTPAuth = true;
    $mail->Username = $emailUsername;//'noreply@dev.x10.bz';
    $mail->Password = $emailPassword;//'2JJzkKnfLW';


    //Set who the message is to be sent from
    $mail->setFrom($emailUsername, "AxPortal");
    //Set an alternative reply-to address
    $mail->addReplyTo($emailUsername, "AxPortal");
    //Set who the message is to be sent to
    $mail->addAddress($email);

    //Set the subject line
    $mail->Subject = $subject;

    ob_start();
    switch ($action) {
      case 'register':
        include 'email_verification.php';
        break;

      case 'status_update':
      $statusRequest = $_POST['statusRequest'];
      $updatedBy = $_POST['updatedBy'];
      $dateTime  = $_POST['dateTime'];
      $requestNo = $_POST['requestNo'];
      $status = $_POST['status'];
        include 'status_update.php';
        break;
        
      case 'password':
        $tempPassword = $_POST['password'];
        include 'reset-password.php';
        break;
    }



    $myvar = ob_get_clean();
    $mailContent = $myvar;
    $mail->Body = $mailContent;

    $mail->AltBody = 'This is a plain-text message body';
    //Attach an image file
    // $mail->addAttachment('../mail/images/phpmailer.png');

    if (!$mail->send()) {
      $logMessage=$subject." could not be sent to ".$email.". Info: ".$mail->ErrorInfo;
      $settings->createLogFile("EmailApi", $logMessage);
        // $response = array(
        //     'status' => 'error',
        //     'message' => 'Email could not be sent'
        //     // 'error' => $mail->ErrorInfo
        // );
    } else {
      $logMessage=$subject." sent to ".$email;
      $settings->createLogFile("EmailApi", $logMessage);
  // $response = array(
  //     'status' => 'success',
  //     'message' => 'Email sent successfully'
  // );
    }


// Convert the response to JSON
// $jsonResponse = json_encode($response);

// Set the JSON response headers
// header('Content-Type: application/json');
// echo $jsonResponse;


?>
