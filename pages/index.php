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
                    <div class="accordion" id="accordionExample" hidden>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Generate Report
      </button>
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
                              <option value="1">Date Range</option>
                              <option value="2">Month</option>
                              <option value="3">Year</option>
                            </select>
                        </div>
                        <div class="mb-3 col-3" id="reportMonth" hidden>
                          <label for="">Select Month</label>
                          <select class="form-control" name="">
                            <?php
                            for ($month = 1; $month <= 12; $month++) {
                              $monthName = date('F', mktime(0, 0, 0, $month, 1));
                              echo "<option value='$month'>$monthName\n</option>";
                            }
                             ?>
                          </select>
                        </div>
                        <div class="mb-3 col-3">
                          <label for="">Select Date Range</label>
                            <input type="text" class="form-control datepicker-here" data-range="true" data-multiple-dates-separator="-" data-language="en" data-position="top left" aria-describedby="daterange" placeholder="Report Date Range">
                             <!-- <small id="daterange" class="form-text text-muted">You can select start and end date for date range selection</small> -->
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
                    <!-- <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="content">
                                    <div class="row">
                                        <div class="dfd text-center">
                                            <i class="blue large-icon mb-2 fas fa-thumbs-up"></i>
                                            <h4 class="mb-0">+21,900</h4>
                                            <p class="text-muted">FACEBOOK PAGE LIKES</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="content">
                                    <div class="row">
                                        <div class="dfd text-center">
                                            <i class="orange large-icon mb-2 fas fa-reply-all"></i>
                                            <h4 class="mb-0">+22,566</h4>
                                            <p class="text-muted">INSTAGRAM FOLLOWERS</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="content">
                                    <div class="row">
                                        <div class="dfd text-center">
                                            <i class="grey large-icon mb-2 fas fa-envelope"></i>
                                            <h4 class="mb-0">+15,566</h4>
                                            <p class="text-muted">E-MAIL SUBSCRIBERS</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="content">
                                    <div class="row">
                                        <div class="dfd text-center">
                                            <i class="olive large-icon mb-2 fas fa-dollar-sign"></i>
                                            <h4 class="mb-0">+98,601</h4>
                                            <p class="text-muted">TOTAL SALES</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>



    <?php echo $scripts; ?>

    <script src="../assets/js/script.js"></script>
    <script type="text/javascript">

      <?php //echo $sweetAlert; ?>
      <?php echo $ajax; ?>


    </script>

    <script type="text/javascript">
    // Initiate time picker
    mdtimepicker('.timepicker', { format: 'h:mm tt', hourPadding: 'true' });
    </script>
    <script src="../assets/js/pages/<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>.js"></script>
</body>

</html>
