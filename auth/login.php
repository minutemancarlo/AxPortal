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
if($session->isSessionVariableSet("Role")){
  header("Location: ../pages/");
}
 ?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | <?php echo $websiteTitle; ?></title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet">
        <link href="../assets/vendor/fontawesome/css/solid.min.css" rel="stylesheet">
        <link href="../assets/vendor/fontawesome/css/brands.min.css" rel="stylesheet">
        <link href="../assets/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet">
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
                    <h6 class="mb-4 text-muted">Login to your account</h6>
                    <form action="" id="loginForm" method="POST" class="needs-validation" novalidate>
                      <div class="mb-3 text-start">
                        <label for="email" class="form-label">Email address</label>
                          <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" required>
                            <span class="input-group-text"  ><i class="fa fa-envelope"></i></span>
                            <div class="invalid-feedback">
                              Please provide a valid email address.
                            </div>
                          </div>

                      </div>
                      <div class="mb-3 text-start">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group mb-3">
                          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                            <span class="input-group-text"  id="togglePassword"><i class="fa fa-eye-slash" id="eyeIcon"></i></span>
                            <div class="invalid-feedback">
                              Please enter your password.
                            </div>
                        </div>

                      </div>

                    
                      <button type="submit" class="btn btn-primary shadow-2 mb-4">Login</button>
                    </form>
                    <p class="mb-2 text-muted">Forgot password? <a href="forgot-password.php">Reset</a></p>
                    <p class="mb-0 text-muted">Don't have account yet? <a href="../auth/signup.php">Signup</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
    <script type="text/javascript">

      <?php echo $sweetAlert; ?>
      <?php echo $ajax; ?>
      <?php echo $validate; ?>

    </script>
    <script src="../assets/js/pages/<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>.js"></script>


</body>

</html>
