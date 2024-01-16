<?php
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SessionHandler.php';

$session = new CustomSessionHandler();

$db = new DatabaseHandler();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $verificationToken = $_GET['q']; // Get the verification token from the GET parameter

    // Retrieve the email associated with the verification token from the users table
    $result = $db->select('users', 'email', "verification_token = '$verificationToken'");

    if ($result->num_rows === 1) {
        // Email and verification token match, update the is_verified field to 1
        $emailRow = $result->fetch_assoc();
        $email = $emailRow['email'];

        $updateData = array('is_verified' => 1, 'is_active' => 1);

        $updateResult = $db->update('users', $updateData, "email = '$email'");

        if ($updateResult === true) {
            // Verification successful
            $session->setSessionVariable("Verified",true);
            $message = 'Email verification successful. Your account has been verified.';
            header("Location: verified.php");
        } else {
            // Update failed
            $message = 'Unexpected error occurred during email verification.';
        }
    } else {
        // Email and verification token don't match or token not found
        $message = 'Invalid verification token. Please try again or contact support.';
    }
} else {
    // Invalid request method or missing "q" parameter
    $message = 'Invalid request.';
}

// Display the verification result message
echo $message;
?>
