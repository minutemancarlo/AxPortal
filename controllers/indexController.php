<?php
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';
require_once '../classess/RoleHandler.php';
$session = new CustomSessionHandler();
$settings=new SystemSettings();
$db = new DatabaseHandler();
$roleHandler = new RoleHandler();
$userid=$session->getSessionVariable("Id");
$role=$session->getSessionVariable("Role");
$where='';
$ext='';
switch ($role) {
  case 1:
    //Auxiliary
    $ext='and (a.level=0 or a.level=2 or a.level=4)';
    break;
  case 2:
    //End-User
    $ext='where a.user_id='.$userid;
    break;
  case 3:
    //Councilor
    $ext='and (a.level=2 or a.level=4)';
    break;
}
if (isset($_POST['action'])) {
  switch ($_POST['action']) {
    case 'service':
    $result = $db->select('projects b
LEFT JOIN
    requests a ON b.id = a.project_id '.$ext.'
GROUP BY
    b.project_name WITH ROLLUP', "IFNULL(b.project_name, 'Total') AS project_name,
    COUNT(*) AS total_requests,COUNT(*) AS total_requests,
    COALESCE(COUNT(CASE WHEN a.level = 0 and a.is_rejected=0 THEN 1 END), 0) AS pending_count,
    COALESCE(COUNT(CASE WHEN a.level BETWEEN 1 AND 3 and a.is_rejected=0 THEN 1 END), 0) AS in_progress_count,
    COALESCE(COUNT(CASE WHEN a.level = 4 and a.is_rejected=0 THEN 1 END), 0) AS approved_count,
    COALESCE(COUNT(CASE WHEN a.is_rejected=1 THEN 1 END), 0) AS rejected_count", '');
    if ($result) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
      }
      break;
    case 'jobs':
    $result = $db->select('jobs b
LEFT JOIN
    requests a ON b.id = a.job_id '.$ext.'
GROUP BY
    b.category_name', 'b.category_name,
    COALESCE(COUNT(CASE WHEN a.level = 0 and a.is_rejected=0 THEN 1 END), 0) AS pending_count,
    COALESCE(COUNT(CASE WHEN a.level BETWEEN 1 AND 3 and a.is_rejected=0 THEN 1 END), 0) AS in_progress_count,
    COALESCE(COUNT(CASE WHEN a.level = 4 and a.is_rejected=0 THEN 1 END), 0) AS approved_count,
    COALESCE(COUNT(CASE WHEN a.is_rejected=1 THEN 1 END), 0) AS rejected_count', '');
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


 ?>
