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
 ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Logs | <?php echo $websiteTitle; ?></title>
    <?php echo $styles; ?>
    <link href="../assets/css/master.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
      <?php include 'sidebar.php'; ?>
      <div id="body" class="active">
          <?php include 'navbar.php'; ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title">
                        <h3>System Logs</h3>
                        <div class="col-md-12 col-lg-12">
                           <div class="card">
                             <div class="card-header">
                               <div class="col-md-4 mb-3">
                                 <div class="input-group mb-3">
                                   <span class="input-group-text" id="basic-addon1"><i class="fas fa-filter"></i></span>
                                     <input type="text" class="datepicker-here form-control" name="filterdate" id="filterdate" data-language="en" placeholder="Select Date" aria-label="Filter" aria-describedby="basic-addon1">
                                     <button class="btn btn-primary" type="button" name="filterBtn" id="button-addon1">Go!</button>
                                 </div>
                               </div>
                             </div>
                             <div class="card-body">
                               <textarea class="form-control" name="logsContent" id="logsContent" rows="8" cols="80" readonly></textarea>
                             </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <?php echo $scripts; ?>
    <script src="../assets/js/script.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      <?php echo $sweetAlert; ?>
      <?php echo $ajax; ?>
       $('button[name="filterBtn"]').click(function() {
        event.preventDefault();

            var successCallback = function(response) {
              // console.log(response);
                var data = JSON.parse(JSON.stringify(response));
              if (data.success) {
                Toast.fire({
                  icon: 'success',
                  title: 'Fetching logs..',
                  timer: 2000,
                }).then(() => {
                   $("#logsContent").val(data.message);
                });
              } else {
                Toast.fire({
                icon: 'error',
                title: data.message
              });
              }
            };

            var errorCallback = function(xhr, status, error) {
              var errorMessage = xhr.responseText;
              console.log('AJAX request error:', errorMessage);
              Toast.fire({
              icon: 'error',
              title: "Unexpected Error Occured. Please check browser logs for more info."
            });
            };
            var data = {
              filterdate: $("#filterdate").val(),
            };
            loadContent('../controllers/logsController.php', data, successCallback, errorCallback);
        });
    });
    </script>
</body>

</html>
