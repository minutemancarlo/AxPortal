<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';

// $lockFilePath = __DIR__ . '/lockfile.txt';



// Try to obtain a lock
// $lockFile = fopen($lockFilePath, 'w');

// if (flock($lockFile, LOCK_EX | LOCK_NB)) {
    // Lock acquired, execute the script

    $settings = new SystemSettings();
    $db = new DatabaseHandler();
    $baseURL = $settings->getBaseURL();

    $result = $db->select("requests a inner join users b on a.user_id = b.id", "*", "is_sent = 0");

    if ($result->num_rows > 0) {
        echo $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $requestNo = $row['rid'];
            if ($row['level'] == 0) {
                // Your existing code for processing each record and sending emails
                $statusRequest = "Created";
                $updatedBy = "End-User";
                $dateTime  = date('m/d/Y h:i:s A T', strtotime($row['created_on']));
                $email = $row['email'];
                $subject = 'Request Status Update';
                $action='status_update';
                $name=$row['name'];

                // Define the URL endpoint
                $url = $baseURL . 'email/send.php'; // Replace with the actual URL

                // Create an array with the POST data
                $data = array(
                    'email' => $email,
                    'subject' => $subject,
                    'link' => '',
                    'name' => ucwords($name),
                    'action' => $action,
                    'statusRequest' => $statusRequest,
                    'updatedBy' => $updatedBy,
                    'dateTime' => $dateTime,
                    'requestNo' => $requestNo,
                    'status' => 'Pending'
                );

                print_r($data);

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
            } else {
                // Additional logic for other cases if needed
            }
        }
    } else {
        echo "No rows found.";
    }

    // Release the lock
    // flock($lockFile, LOCK_UN);
// } else {
//     echo "Script is already running.";
// }
// file_put_contents('C:\wamp64\www\axportal\email\service_logs\log.txt', date('Y-m-d H:i:s') . " Script executed\n", FILE_APPEND);
fclose($lockFile);
?>
