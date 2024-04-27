<?php
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';
require_once '../classess/RoleHandler.php';
$session = new CustomSessionHandler();
if(!$session->isSessionVariableSet('Name')){
  header("Location: ../auth/login.php");
}
$selectedValue = $_POST["selectedValue"];
$where = $_POST["where"];
$action = $_POST["action"];
$settings=new SystemSettings();
$db = new DatabaseHandler();
$query="SELECT b.name,e.dept_desc,c.project_name,d.category_name,a.feedback,a.workstatus,f.name as assignee,f.position from requests a
inner join users b on a.user_id=b.id
inner join projects c on a.project_id=c.id
inner join jobs d on a.job_id=d.id
inner join departments e on a.department_id=e.id
left join workers f on a.assignee = f.worker_id
";
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


$result = $db->executeQuery($query);


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sample PDF with Table</title>
<style>
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid black;
    padding: 8px;
    text-align: left;
  }
  .logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
  }
  .logo-container img {
    max-width: 200px; /* adjust as needed */
    margin-right: 20px;
  }
</style>
</head>
<body>

<!-- Add Image logo here  -->
<div class="logo-container">


  <!-- <img src="<?php //echo $_SERVER["DOCUMENT_ROOT"].'/axportal/controllers/DMMMSU-Logo-Final-min.jpg';?>" width="90" height="90" alt="Logo"/> -->

  <div>
    <h3 style="text-align:center"><strong>Don Mariano Marcos Memorial State University</strong></h3>
    <h4 style="text-align:center; padding: 0;">Auxiliary Unit</h4>
  </div>
</div>

<table>
  <thead style="background: #3498db;color: white">
    <tr>
      <th>Requester</th>
      <th>Unit/Department</th>
      <th>Project Name</th>
      <th>Requested Job</th>
      <th>Remarks</th>
      <th>Work Status</th>
      <th>Assignee</th>
      <th>Position</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($result) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
          $workstatus = ($row['workstatus'] == 0 || $row['workstatus'] == NULL) ? "Pending" : (($row['workstatus'] == 1) ? "In Progress" : "Completed");

            echo "  <tr>
                <td>".ucwords($row['name'])."</td>
                <td>".$row['dept_desc']."</td>
                <td>".$row['project_name']."</td>
                <td>".$row['category_name']."</td>
                <td>".$row['feedback']."</td>
                <td>".$workstatus."</td>
                <td>".$row['assignee']."</td>
                <td>".$row['position']."</td>
              </tr>";
        }

      }

     ?>
  </tbody>
</table>
<br>
<br>
<p>Generated On: <strong><?php echo date("F d, Y"); ?></strong></p>
<p>Prepared By: </p>
<p>_________________</p>
<p> <strong><?php echo ucwords($session->getSessionVariable('Name')); ?></strong></p>
<?php
// Include dompdf library
require_once 'dompdf/autoload.inc.php';
// echo $_SERVER["DOCUMENT_ROOT"].'/axportal/controllers/DMMMSU-Logo-Final-min.jpg';
use Dompdf\Dompdf;
use Dompdf\Options;
$dompdf = new Dompdf();


// Load HTML content
$dompdf->loadHtml(ob_get_clean());

// Set paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render PDF (output to browser or file)
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream(date("mdYs")."_report.pdf");
?>

</body>
</html>
