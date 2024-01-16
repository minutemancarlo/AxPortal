<?php
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';

$settings = new SystemSettings();
$session = new CustomSessionHandler();
$settings->setDefaultTimezone();
$websiteTitle = $settings->getWebsiteTitle();
$brlogo = $settings->getBrlogo();
$logo = $settings->getLogo();
$sweetAlert = $settings->getSweetAlertInit();
$ajax = $settings->getAjaxInit();
$validate = $settings->validateForms();
if(!$session->isSessionVariableSet("Verified")){
header("Location: login.php");
}
$session->unsetSessionVariable("Verified");
 ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verified | <?php echo $websiteTitle; ?></title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/error.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div class="page vertical-align text-center">
            <div class="page-content vertical-align-middle">
              <div class="mb-4">
                <img class="brand" src="../assets/img/<?php echo $logo; ?>" width="90" height="90" alt="sitelogo">
                  <img class="brand" src="../assets/img/<?php echo $brlogo; ?>" alt="brlogo">
              </div>
              <h4>Online Auxiliary Services Request Portal</h4>
                <header>

                    <p>Your account has been verified.</p>
                </header>
                <!-- <p class="error-advise">The page you were looking for could not be found.</p> -->
                <a class="btn btn-primary btn-round mb-5" href="login.php">LOGIN</a>
                <footer class="page-copyright">
                    <p><?php echo $websiteTitle; ?> &copy; <?php echo date("Y"); ?>. All RIGHT RESERVED.</p>
                </footer>
            </div>
        </div>
    </div>
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
