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
    <title>Sign up | <?php echo $websiteTitle; ?></title>
    <?php echo $styles; ?>
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
                  <h6 class="mb-4 text-muted">Create new account</h6>
                <form action="" id="signupForm" method="POST" class="needs-validation" novalidate>
                      <div class="mb-3 text-start">
                          <label for="name" class="form-label">Name</label>
                          <input type="text" class="form-control" name="name" id="Name" placeholder="Enter Name" required>
                          <div class="invalid-feedback">
                            Please enter your name.
                          </div>
                      </div>
                      <div class="mb-3 text-start">
                          <label for="email" class="form-label">Email address</label>
                          <input type="email" class="form-control" name="email" id="Email" placeholder="Enter Email" required>
                          <div class="invalid-feedback">
                            Please provide a valid email address.
                          </div>
                      </div>

                      <div class="mb-3 text-start">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="Password" pattern=".{8,}" placeholder="Password" required>
                        <div class="invalid-feedback password-error">
                          Please enter a password that is at least 8 characters long.
                        </div>
                      </div>
                      <div class="mb-3 text-start">
                        <input type="password" class="form-control"  id="ConfirmPassword" placeholder="Confirm password" required>
                        <div class="invalid-feedback confirm-password-error">
                          Please confirm your password.
                        </div>
                      </div>

                      <!-- <div class="mb-3 text-start">
                          <div class="form-check">
                            <input class="form-check-input"  type="checkbox" value="" id="check1">
                            <label class="form-check-label" for="check1">
                              I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" tabindex="-1">terms and conditions</a>.
                            </label>
                          </div>
                      </div> -->
                      <button type="submit" class="btn btn-primary shadow-2 mb-4">Register</button>
                  </form>
                  <p class="mb-0 text-muted">Already have an account? <a href="login.php">Log in</a></p>
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
