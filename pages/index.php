<?php
require_once '../classess/SessionHandler.php';
$session = new CustomSessionHandler();
if(!$session->isSessionVariableSet("Role")){
  header("Location: ../");
}
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/RoleHandler.php';
$settings = new SystemSettings();
$db = new DatabaseHandler();
$roleHandler = new RoleHandler();
$websiteTitle = $settings->getWebsiteTitle();
$styles = $settings->getStyles();
$scripts = $settings->getScripts();
$sweetAlert = $settings->getSweetAlertInit();
$ajax = $settings->getAjaxInit();
$settings->setDefaultTimezone();
$baseURL = $settings->getBaseURL();
$brlogo = $settings->getBLogo();
$logo = $settings->getLogo();
$roleValue = $session->getSessionVariable("Role");
$roleName = $roleHandler->getRoleName($roleValue);
$menuTags = $roleHandler->getMenuTags($roleValue);
$newUsers = $roleHandler->getNewUsers();
$cards = $roleHandler->getCards($roleValue,0,0,0,0);
 ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="baseurl" content="<?php echo $baseURL; ?>">
    <title>Dashboard | <?php echo $websiteTitle; ?></title>

    <?php echo $styles; ?>
    <link href="../assets/css/master.css" rel="stylesheet">
    <style>
        /* inline style for mdtimepicker demo */
        .mdtp__wrapper.inline {display: block !important;position: relative;box-shadow: none;border: 1px solid #E0E0E0;max-width: 300px;margin: 0 !important;padding: 0 !important;transform: inherit;left: 0;top: 0;}
        .mdtp__wrapper.inline .mdtp__time_holder {width: auto;}
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div id="body" class="active">
            <?php include 'navbar.php'; ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 page-header">
                            <div class="page-pretitle">Overview</div>
                            <h2 class="page-title">Dashboard</h2>
                        </div>
                    </div>
                    <?php echo $cards; ?>
                    <!-- REPORT GENERATION -->
  <div class="accordion" id="accordionExample" <?php if ($roleValue==2){echo "hidden";} ?> >
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Generate Report </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          <div class="row">
            <div class="col-md-6">
              <form class="form-control">
                <br>
                <div class="row">
                  <div class="mb-3 col-3">
                    <label for="">Report Filter Type</label>
                    <select class="form-control" id="reportFilter">
                      <option value="1" selected>Date Range</option>
                      <option value="2">Month</option>
                      <option value="3">Year</option>
                    </select>
                  </div>
                  <div class="mb-3 col-3" id="reportYear" hidden>
                    <label for="">Select Year</label>
                    <select class="form-control" id="reportYearSelect">
    <?php
    // Get the current year
    $currentYear = date('Y');

    // Loop to generate options for 5 years before and 5 years after the current year
    for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
        // Output each year as an option
        echo '<option value="' . $i . '">' . $i . '</option>';
    }
    ?>
</select>

                  </div>
                  <div class="mb-3 col-3" id="reportMonth" hidden>
                    <label for="">Select Month</label>
                    <select class="form-control" name="" id="reportMonthSelect"> <?php
                                        for ($month = 1; $month <= 12; $month++) {
                                          $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                          echo "
											<option value='$month'>$monthName\n</option>";
                                        }
                                        ?> </select>
                  </div>
                  <div class="mb-3 col-3" id="reportDateRange">
                    <label for="">Select Date Range</label>
                    <input type="text" class="form-control datepicker-here" data-range="true"
                    data-multiple-dates-separator="-" data-language="en" data-position="top left" aria-describedby="daterange"
                    placeholder="Report Date Range">
                  </div>
                  <div class="mb-3 col-3 align-items-bottom">
                    <br>
                    <button type="button" id="generatePDFButton" class="btn btn-primary" >Generate</button>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

                    <!-- REPORT GENERATION -->
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="content">
                                    <div class="head">
                                        <h5 class="mb-0">Service Requests Categories</h5>
                                        <p class="text-muted">All Service Categories Requested</p>
                                    </div>
                                    <div class="canvas-wrapper">
                                        <table class="table table-striped" id="serviceTable" width="100%">

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="content">
                                    <div class="head">
                                        <h5 class="mb-0">Job Types/Services</h5>
                                        <p class="text-muted">All Job Services Requested</p>
                                    </div>
                                    <div class="canvas-wrapper">
                                        <table class="table table-striped" id="jobTable" width="100%">

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container" id="reportprint" hidden>
      <div class="p-3">

        <h6 class="text-center"> <img src="../assets/img/axportal.png" height="50px;" alt="">
          <br>
          <br>
          <br>
           <strong id="reportTitle"> </strong></h6>
           <br>
           <br>
           <br>
        <!-- <div class="row text-center">
          <div class="col-md-8">
            <h3 class="float-start">Projects Summary</h3>
            <table class="table table-striped bordered" id="reportTable_projects" width="100%">

            </table>
            <div style="page-break-before: always;">
              <h3 class="float-start">Jobs Summary</h3>
              <table class="table table-striped bordered" id="reportTable_jobs" width="100%">

              </table>
            </div>

          </div>

        </div> -->

        <div class="row text-center">
          <div class="col-md-8">
            <h3 class="float-start">Projects Summary</h3>
            <table class="table table-striped bordered" id="reportTable_projects" width="100%">
            </table>
          </div>
        </div>
        <div style="page-break-before: always;">

        </div>
        <br><br><br>
        <div class="row text-center">
          <div class="col-md-8">

            <h3 class="float-start">Jobs Summary</h3>
            <table class="table table-striped bordered" id="reportTable_jobs" width="100%">

            </table>
          </div>

        </div>
        <div class="float-start">
          <p>Generated on <?php echo date("m-d-Y"); ?></p>
        </div>
        <div class="float-end">
          <p>Generated by <?php echo $session->getSessionVariable("Name"); ?></p>
        </div>
      </div>
    </div>

    <?php echo $scripts; ?>

    <script src="../assets/js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <script type="text/javascript">

      <?php //echo $sweetAlert; ?>
      <?php echo $ajax; ?>


    </script>


    <script type="text/javascript">
    // Initiate time picker
    var selectedValue;
    mdtimepicker('.timepicker', { format: 'h:mm tt', hourPadding: 'true' });
    $(document).ready(function(){

      $("#reportFilter").change(function(){
          selectedValue = $(this).val();
          switch(selectedValue) {
              case '1':
                  $('#reportMonth').prop('hidden',true);
                  $('#reportDateRange').prop('hidden',false);
                  $('#reportYear').prop('hidden',true);

                  break;
              case '2':
                    $('#reportMonth').prop('hidden',false);
                    $('#reportDateRange').prop('hidden',true);
                    $('#reportYear').prop('hidden',true);
                  break;
              case '3':
                  $('#reportMonth').prop('hidden',true);
                  $('#reportDateRange').prop('hidden',true);
                  $('#reportYear').prop('hidden',false);
                  break;
          }
      });

// Move the PDF generation code outside of the AJAX success callback
$("#generatePDFButton").click(function() {
var where;
var reportTitle;
var selectedValue = $("#reportFilter").val()
var baseURL = $('meta[name="baseurl"]').attr('content');
  switch(selectedValue) {
      case '1':
        where = $('.datepicker-here').val();
        reportTitle = where;

          break;
      case '2':
        where = $('#reportMonthSelect').val();
        reportTitle = $('#reportMonthSelect option:selected').text() + " 2024";
        where = '2024-' + where + '-01';
          break;
      case '3':
        where = $('#reportYearSelect').val();
        reportTitle = where;
          break;
  }
  $('#reportTitle').html("Summary Report for " + reportTitle);
var table1=false;
var table2=false;
var form = document.createElement('form');
form.method = 'POST'; // Set form method to POST
form.action = baseURL + 'controllers/dom.php'; // Set form action URL

// Create input fields for each data parameter
var selectedValueInput = document.createElement('input');
selectedValueInput.type = 'hidden'; // Hidden input field
selectedValueInput.name = 'selectedValue'; // Set input name
selectedValueInput.value = selectedValue; // Set input value
form.appendChild(selectedValueInput); // Append input field to form

var whereInput = document.createElement('input');
whereInput.type = 'hidden'; // Hidden input field
whereInput.name = 'where'; // Set input name
whereInput.value = where; // Set input value
form.appendChild(whereInput); // Append input field to form

var actionInput = document.createElement('input');
actionInput.type = 'hidden'; // Hidden input field
actionInput.name = 'action'; // Set input name
actionInput.value = 0; // Set input value
form.appendChild(actionInput); // Append input field to form

// Append form to document body
document.body.appendChild(form);

// Submit the form
form.submit();

// Remove the form from the document body after submission
document.body.removeChild(form);



  $.ajax({
      url: baseURL+'controllers/reportController.php',
      method: 'POST',
      data: { selectedValue: selectedValue, where: where, action: 1 },
      dataType: 'json',
      success: function(response) {


      },
      error: function(xhr, status, error) {
          console.error('Error:', error);
          // Optionally handle error case here
      }
  });



});




});

    </script>

    <script src="../assets/js/pages/<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>.js"></script>
</body>

</html>
