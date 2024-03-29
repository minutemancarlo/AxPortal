<?php
require_once '../classess/SystemSettings.php';
require_once '../classess/SessionHandler.php';

$settings = new SystemSettings();
$session = new CustomSessionHandler();
$settings->setDefaultTimezone();
$websiteTitle = $settings->getWebsiteTitle();
$brlogo = $settings->getBrlogo();
$logo = $settings->getLogo();
$styles = $settings->getStyles();
$scripts = $settings->getScripts();
$sweetAlert = $settings->getSweetAlertInit();
$ajax = $settings->getAjaxInit();
$validate = $settings->validateForms();
 ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Forgot Password | <?php echo $websiteTitle; ?></title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/auth.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body text-center">
                  <div class="mb-4">
                    <img class="brand" src="../assets/img/<?php echo $logo; ?>" width="90" height="90" alt="sitelogo">
                      <img class="brand" src="../assets/img/<?php echo $brlogo; ?>" alt="brlogo">
                  </div>
                  <h4>Online Auxiliary Services Request Portal</h4>
                    <h6 class="mb-4 text-muted">Reset Password</h6>
                    <p class="text-muted text-start">Enter your email address and your new password will be emailed to you.</p>
                    <form action="" id="resetForm" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">Email adress</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                        </div>
                        <button type="submit" class="btn btn-primary shadow-2 mb-4">Send me new password</button>
                    </form>
                    <p class="mb-0 text-muted">Don’t have an account? <a href="signup.php">Sign up</a> or <a href="login.php">Log in</a> instead.</p>
                          
                </div>
            </div>
        </div>
    </div>
    <?php echo $scripts; ?>
    <script src="../assets/js/pages/<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>.js" ></script>
    <script type="text/javascript">

      <?php echo $sweetAlert; ?>
      <?php echo $ajax; ?>
      <?php echo $validate; ?>

    </script>
</body>

</html>
