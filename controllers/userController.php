<?php
// Include the necessary files and initialize the database connection
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';
$settings=new SystemSettings();
$db = new DatabaseHandler();

// Check if the action POST variable is set
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'select') {
        // Select data from the member table using DatabaseHandler select method
        $result = $db->select('users');

        // Check if the query was successful
        if ($result) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            // Return the data as JSON
            echo json_encode($data);
        } else {
            // Return an error message if the query fails
            echo json_encode(array('error' => 'Unable to fetch data from the database.'));
        }
    }

    if ($action === "update") {
            // Get the data from the POST variables
            $memberID = $_POST["id"];
            $role = $_POST["role"];
            $isActive = $_POST["is_active"];
            $session=new CustomSessionHandler();

            if ($memberID==$session->getSessionVariable('Id')) {
              $response = array("success" => false, "message" => "You cannot modify your own account.");
              echo json_encode($response);
              exit();
            }
            // Create a new instance of the DatabaseHandler class
            $db = new DatabaseHandler();

            // Prepare the data for update
            $data = array(
                "role" => $role,
                "is_active" => $isActive
            );

            // Create the WHERE clause for the update query
            $where = "id = '$memberID'";

            // Perform the update operation
            $updateResult = $db->update("users", $data, $where);
            if ($updateResult) {
            // Check if the update was successful
                $response = array("success" => true, "message" => "User info updated successfully.");
            } else {
                $response = array("success" => false, "message" => "Failed to update user info.");
            }

            // Send the JSON response
            echo json_encode($response);

    }
} else {
    // Return an error message if the action POST variable is missing
    echo json_encode(array('error' => 'Action parameter is missing.'));
}
?>
