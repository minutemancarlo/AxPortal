<?php
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';
require_once '../classess/RoleHandler.php';
$session = new CustomSessionHandler();
$settings=new SystemSettings();
$db = new DatabaseHandler();
$roleHandler = new RoleHandler();

if (isset($_POST['action'])) {
  switch ($_POST['action']) {
    case 'insert':
    unset($_POST['action']);
    $_POST['rid']=generateUniqueID();
    $postData=$_POST;
    $insertResult = $db->insert('requests', $postData);
    if($insertResult){
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
      unset($_POST['approval_status']);
      $_POST['level']=(int)$_POST['level']+$level;
      $postData=$_POST;
      $where="rid='".$_POST['rid']."'";
      $updateResult = $db->update('requests', $postData,$where);
      if($updateResult){
        $history = array(
          'request_id' => $_POST['rid'],
          'approver_id' => $_POST['user_id']
        );
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

    case 'select-per-user-request':
    // getUserRequests($userid,$status, $is_rejected)
      $pending=$roleHandler->getUserRequests($session->getSessionVariable("Id"),0,0);
      $auxiliary=$roleHandler->getUserRequests($session->getSessionVariable("Id"),1,0);
      $councilor=$roleHandler->getUserRequests($session->getSessionVariable("Id"),2,0);
      $finalApproval=$roleHandler->getUserRequests($session->getSessionVariable("Id"),3,0);
      $approved=$roleHandler->getUserRequests($session->getSessionVariable("Id"),4,0);
      $rejected=$roleHandler->getUserRequests($session->getSessionVariable("Id"),0,1);

      $statusCounts = array(
        'Pending' => $pending,
        'Auxiliary' => $auxiliary,
        'Councilor' => $councilor,
        'FinalApproval' => $finalApproval,
        'Approved' => $approved,
        'Rejected' => $rejected
      );

      echo json_encode($statusCounts);
      break;

      case 'select-all-requests-user':
      $userid=$session->getSessionVariable("Id");
      $role=$session->getSessionVariable("Role");
      $table = 'requests a inner join projects b on a.project_id=b.id inner join jobs c on a.job_id=c.id';
      switch ($role) {
        case 0:
        //Superuser
          $where = 'level in (0,1,2,3,4) and is_rejected=0 order by a.created_on desc';
          break;
        case 1:
        //Auxiliary
          $where = 'level in (0,2,4) and is_rejected=0 order by a.created_on desc';
          break;
        case 2:
        //End User
        $where = 'user_id='.$userid.' order by a.created_on desc';
          break;
        case 3:
        //Councilor
          $where = 'level=1 and is_rejected=0 order by a.created_on desc';
          break;
      }
      $columns = "*,
    CASE
        WHEN a.level = 1 THEN 'Auxiliary'
        WHEN a.level = 2 THEN 'Councilor'
        WHEN a.level = 3 THEN 'For Final Approval'
        WHEN a.level = 4 THEN 'Completed'
    END AS status_name,
    CASE
        WHEN a.level = 0 THEN 'Auxiliary'
        WHEN a.level = 1 THEN 'Councilor'
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

function generateUniqueID() {
    $prefix = substr(uniqid(), -5); // Get the last 5 characters of the unique ID
    $randomPart = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5); // Generate a random 5-character alphanumeric string with uppercase letters and numbers

    return strtoupper($prefix . $randomPart); // Convert the result to uppercase
}

 ?>
