<?php
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';
require_once '../classess/RoleHandler.php';
$session = new CustomSessionHandler();
$settings=new SystemSettings();
$db = new DatabaseHandler();
$roleHandler = new RoleHandler();
$baseURL = $settings->getBaseURL();
if (isset($_POST['action'])) {
  switch ($_POST['action']) {
    case 'update-workstatus':
    unset($_POST['action']);
    $postData=$_POST;
    $where="rid='".$_POST['rid']."'";
    $updateResult = $db->update('requests', $postData,$where);
    if($updateResult){
      $response = array("success" => true, "message" => "Work Status Updated");
    }else{
      $response = array("success" => false, "message" => "Failed to update workstatus.");
    }
    echo json_encode($response);

    break;
    case 'update-assignee':
    unset($_POST['action']);
    $postData=$_POST;
    $where="rid='".$_POST['rid']."'";
    $updateResult = $db->update('requests', $postData,$where);
    if($updateResult){
      $response = array("success" => true, "message" => "Asignee Updated");
    }else{
      $response = array("success" => false, "message" => "Failed to update assignee.");
    }
    echo json_encode($response);

    break;
    case 'insert':
    unset($_POST['action']);
    $_POST['rid']=date("Y").generateUniqueID().date("m").date('d');
    $postData=$_POST;
    $insertResult = $db->insert('requests', $postData);
    if($insertResult){
      $result = $db->select("requests a inner join users b on a.user_id = b.id", "*", "a.rid = '".$_POST['rid']."'");
      if ($result->num_rows > 0) {

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
                  sendEmail($url,$data);

              } else {
                  // Additional logic for other cases if needed
              }
          }
      }



      $response = array("success" => true, "message" => "Request Submitted");
  } else {
      $response = array("success" => false, "message" => "Failed to insert request.");
  }

  // Send the JSON response
  echo json_encode($response);
      break;

      case 'update':
      unset($_POST['action']);
      $level=$_POST['approval_status']==1?1:0;
      if($level==0){
        $_POST['is_rejected']=1;
      }
      unset($_POST['approval_status']);
      unset($_POST['user_id']);
      if($_POST['level']<3){
      $_POST['level']=(int)$_POST['level']+$level;
      }
      $postData=$_POST;
      $where="rid='".$_POST['rid']."'";
      $updateResult = $db->update('requests', $postData,$where);
      if($updateResult){
        $history = array(
          'request_id' => $_POST['rid'],
          'approver_id' => $session->getSessionVariable("Id")
        );
        //emails

        $result = $db->select("requests a inner join users b on a.user_id = b.id", "*", "a.rid = '".$_POST['rid']."'");
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $requestNo = $row['rid'];
                if ($row['level']) {
                    // Your existing code for processing each record and sending emails


                    switch ($row['level']) {
                      case '1':
                        $updatedBy = 'Auxiliary';
                        $statusRequest = $row['is_rejected']==0?"Approved":"Rejected";
                        $status =  $row['is_rejected']==0?"In-Progress":"Rejected";
                        break;
                        case '2':
                          $updatedBy = 'Chancellor';
                          $statusRequest = $row['is_rejected']==0?"Approved":"Rejected";
                          $status =  $row['is_rejected']==0?"In-Progress":"Rejected";
                          break;
                          case '3':
                            $updatedBy = 'Auxiliary';
                            $statusRequest = $row['is_rejected']==0?"Approved":"Rejected";
                            $status =  $row['is_rejected']==0?"In-Progress":"Rejected";
                            break;
                            case '4':
                              $updatedBy = 'Auxiliary';
                              $statusRequest = $row['is_rejected']==0?"Approved":"Rejected";
                              $status =  $row['is_rejected']==0?"Approved":"Rejected";
                              break;

                    }

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
                        'status' => $status
                    );
                    sendEmail($url,$data);

                } else {
                    // Additional logic for other cases if needed
                }
            }
        }

        //emails




        if($level==0){
          $response = array("success" => true, "message" => "Request Updated");
        }else{
          $insertResult = $db->insert('request_history', $history);
          if($insertResult){
            $response = array("success" => true, "message" => "Request Updated");
          }else{
            $response = array("success" => false, "message" => "Failed to update request.");
          }
        }
    } else {
        $response = array("success" => false, "message" => "Failed to update request.");
    }

    // Send the JSON response
    echo json_encode($response);
        break;

        case 'feedbackUpdate':
        unset($_POST['action']);
        $postData=$_POST;
        $where="rid='".$_POST['rid']."'";
        $updateResult = $db->update('requests', $postData,$where);
        if($updateResult){
          $response = array("success" => true, "message" => "Request Feedback Updated.");
      } else {
          $response = array("error" => false, "message" => "Failed to update request.");
      }

      // Send the JSON response
      echo json_encode($response);
          break;

    case 'select-per-user-request':
    // getUserRequests($userid,$status, $is_rejected)
    // 0 - Super User
    // 1 - Auxiliary
    // 2 - End-User
    // 3 - Councilor


      $is_enduser=$session->getSessionVariable("Role")==2?true:false;
      $userrole=(int)$session->getSessionVariable("Role");
      $where='';
      if($is_enduser){
          $where="user_id=".$session->getSessionVariable("Id")." and ";
          $pending=getCount($where."level=0 and is_rejected=0");
          $auxiliary=getCount($where."level=1 and is_rejected=0");
          $councilor=getCount($where."level=2 and is_rejected=0");
          $finalApproval=getCount($where."level=3 and is_rejected=0");
          $approved=getCount($where."level=4 and is_rejected=0");
          $rejected=getCount($where."is_rejected=1");
      }else{
        if($userrole==0){
          $pending=getCount($where."level!=3 and is_rejected=0");
        }else{

          $pending=getCount($where."level=0 and is_rejected=0");
          // echo $where."level=".$userrole." and is_rejected=0";
        }
        $auxiliary=getCount($where."level=1 and is_rejected=0");
        $councilor=getCount($where."level=2 and is_rejected=0");
        $finalApproval=getCount($where."level=3 and is_rejected=0");
        $approved=getCount($where."level=4 and is_rejected=0");
        $rejected=getCount($where."is_rejected=1");
      }

      $statusCounts = array(
        'Pending' => $pending,
        'Auxiliary' => $auxiliary,
        'Chancellor' => $councilor,
        'FinalApproval' => $finalApproval,
        'Approved' => $approved,
        'Rejected' => $rejected
      );

      echo json_encode($statusCounts);
      break;

      case 'select-by-request':
      $result = $db->select("request_history a inner join users b on a.approver_id=b.id", "a.updated_on,b.name", "request_id='".$_POST["rid"]."' order by a.id asc");
      if ($result) {
          $data = array();
          while ($row = $result->fetch_assoc()) {
              $data[] = $row;
          }
          echo json_encode($data);
        }
        break;

      case 'select-all-requests-user':
      $userid=$session->getSessionVariable("Id");
      $role=$session->getSessionVariable("Role");
      $table = 'requests a inner join projects b on a.project_id=b.id inner join jobs c on a.job_id=c.id inner join users d on a.user_id=d.id inner join departments dept on a.department_id=dept.id';
      switch ($role) {
        case 0:
        //Superuser
          $where = 'level in (0,1,2,3) or is_rejected=1 order by a.created_on desc';
          break;
        case 1:
        //Auxiliary
          $table = $table." left join request_history e on e.approver_id=". $userid ." and e.id=a.rid";
          $where = 'level in (0,1,2,3) or is_rejected=1 order by a.created_on desc';
          break;
        case 2:
        //End User
        $where = 'user_id='.$userid.' order by a.created_on desc';
          break;
        case 3:
        //Councilor
          $table = $table." left join request_history e on e.approver_id=". $userid ." and e.id=a.rid";
          $where = 'level in (1,2,3) or is_rejected=1 order by a.created_on desc';
          break;
      }
      $columns = "*,
    CASE
        WHEN a.level = 1 THEN 'Auxiliary'
        WHEN a.level = 2 THEN 'Chancellor'
    END AS status_name,
    CASE
        WHEN a.is_rejected=1 THEN ''
        WHEN a.level = 0 THEN 'Auxiliary'
        WHEN a.level = 1 THEN 'Chancellor'
        WHEN a.level = 2 THEN 'Auxiliary'
    END AS next_approver_name";

      $result = $db->select($table, $columns, $where);
      if ($result) {
          $data = array();
          while ($row = $result->fetch_assoc()) {
              $data[] = $row;
          }
          echo json_encode($data);
        }
        break;

        case 'select-request':
        $columns='a.*,b.project_name,c.category_name,d.name';
        $table='requests a inner join projects b on a.project_id=b.id inner join jobs c on a.job_id=c.id inner join users d on a.user_id=d.id left join request_history e on a.rid=e.request_id';
        $where='';
        $result = $db->select($table, $columns, $where);
        if ($result) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
          }
        break;
}
}


function getCount($condition){
  $db = new DatabaseHandler();
  $result = $db->select("requests", "*", $condition);
  $rowCount = $result->num_rows;
  return $rowCount;
}

function sendEmail($url,$data){
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
}

function generateUniqueID() {
    $prefix = substr(uniqid(), -5); // Get the last 5 characters of the unique ID
    $randomPart = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5); // Generate a random 5-character alphanumeric string with uppercase letters and numbers

    return strtoupper($prefix . $randomPart); // Convert the result to uppercase
}

 ?>
