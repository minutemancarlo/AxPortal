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
// Extracting the POST data
$selectedValue = $_POST["selectedValue"];
$where = $_POST["where"];
$action = $_POST["action"];

// Start building the SQL query
if($action=="0"){
  $query = "SELECT IFNULL(b.project_name, 'Total') AS project_name,
  COALESCE(COUNT(CASE WHEN a.level = 0 AND a.is_rejected = 0 THEN 1 END), 0) AS pending_count,
  COALESCE(COUNT(CASE WHEN a.level BETWEEN 0 AND 2 AND a.is_rejected = 0 THEN 1 END), 0) AS for_approval_count,
  COALESCE(COUNT(CASE WHEN a.level BETWEEN 1 AND 2 AND a.is_rejected = 0 THEN 1 END), 0) AS in_progress_count,
  COALESCE(COUNT(CASE WHEN a.level = 3 AND a.is_rejected = 0 THEN 1 END), 0) AS approved_count,
  COALESCE(COUNT(CASE WHEN a.is_rejected = 1 THEN 1 END), 0) AS rejected_count
  FROM projects b
  LEFT JOIN requests a ON b.id = a.project_id";
}else if($action=="1"){
  $query = "SELECT IFNULL(b.category_name, 'Total') AS job_name,
  COALESCE(COUNT(CASE WHEN a.level = 0 AND a.is_rejected = 0 THEN 1 END), 0) AS pending_count,
  COALESCE(COUNT(CASE WHEN a.level BETWEEN 0 AND 2 AND a.is_rejected = 0 THEN 1 END), 0) AS for_approval_count,
  COALESCE(COUNT(CASE WHEN a.level BETWEEN 1 AND 2 AND a.is_rejected = 0 THEN 1 END), 0) AS in_progress_count,
  COALESCE(COUNT(CASE WHEN a.level = 3 AND a.is_rejected = 0 THEN 1 END), 0) AS approved_count,
  COALESCE(COUNT(CASE WHEN a.is_rejected = 1 THEN 1 END), 0) AS rejected_count
  FROM jobs b
  LEFT JOIN requests a ON b.id = a.job_id";
}


// Adding conditions based on the selected value
if ($selectedValue == "1") {
    // Date Range
    // Assuming $where is formatted as "start_date-end_date"
    $dates = explode("-", $where);
$start_date_parts = explode("/", $dates[0]);
$start_date = $start_date_parts[2] . '-' . $start_date_parts[0] . '-' . $start_date_parts[1];

// If end date is available, convert it; otherwise, default it to start date
if (isset($dates[1])) {
    $end_date_parts = explode("/", $dates[1]);
    $end_date = $end_date_parts[2] . '-' . $end_date_parts[0] . '-' . $end_date_parts[1];
} else {
    $end_date = $start_date;
}

    // Add WHERE clause for date range

    $query .= " WHERE a.created_on BETWEEN '$start_date' AND '$end_date'";
} elseif ($selectedValue == "2") {
    // Month
    // Assuming $where is formatted as "YYYY-MM"
    $query .= " WHERE MONTH(a.created_on) = MONTH('$where') AND YEAR(a.created_on) = ".date("Y");
} elseif ($selectedValue == "3") {
    // Year
    // Assuming $where is formatted as "YYYY"
    $query .= " WHERE YEAR(a.created_on) = '$where'";
}

// Complete the SQL query
if ($action=="0") {
  $query .= " GROUP BY b.project_name WITH ROLLUP";
}else if($action=="1"){
  $query .= " GROUP BY b.category_name WITH ROLLUP";
}

// Now, $query contains the SQL query with appropriate conditions based on the selected value and where clause.

// WHERE created_on BETWEEN '2022-01-01' AND '2022-01-31'

$result = $db->executeQuery($query);
// echo $query;
if ($result) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
  }



 ?>
