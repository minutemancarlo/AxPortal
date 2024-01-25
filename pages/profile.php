<?php
require_once '../classess/SessionHandler.php';
$session = new CustomSessionHandler();
if (!$session->isSessionVariableSet("Role")) {
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
$validate = $settings->validateForms();
$settings->setDefaultTimezone();
$baseURL = $settings->getBaseURL();
$roleValue = $session->getSessionVariable("Role");
$Name = $session->getSessionVariable("Name");
$email = $session->getSessionVariable("Email");
$roleName = $roleHandler->getRoleName($roleValue);
$menuTags = $roleHandler->getMenuTags($roleValue);
$brlogo = $settings->getBLogo();
$logo = $settings->getLogo();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">

    <title>Profile | <?php echo $websiteTitle; ?> </title>
    <?php echo $styles; ?>
    <link href="../assets/css/master.css" rel="stylesheet">
    <style>
        /* Additional styles for the card */
        .card {
            max-width: 400px;
            margin: 0 auto;
            background-color: #f2f2f2;
            position: relative;
        }

        /* Curved shape at the top */
        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background-color: #007bff;
            /* Adjust the background color as desired */
            border-radius: 50% 50% 0 0;
        }

        /* Center the card body content */
        .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
            position: relative;
            z-index: 1;
            /* Ensure the card body is above the curved shape */
        }

        /* Optional styles for the text */
        h3 {
            margin-bottom: 10px;
        }

        h4 {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div id="body" class="active">
            <?php include 'navbar.php'; ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title">
                        <h3>My Profile</h3>
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div style="font-size: 16px;" class="col-md-6">
                                        <div class="box box-success">
                                            <div class="box-body">
                                                <form action="" id="signupForm" method="POST" class="needs-validation" novalidate>
                                                    <div class="mb-3 text-start">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" name="action" value="update" hidden>
                                                        <input type="text" class="form-control" name="Name" id="Name" placeholder="" value="<?php echo ucwords($Name); ?>" required disabled>
                                                        <div class="invalid-feedback">
                                                            Please enter your name.
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 text-start">
                                                        <label for="email" class="form-label">Email address</label>
                                                        <sub class="text-muted"></sub>
                                                        <input type="email" class="form-control" name="Email" id="Email" placeholder="" value="<?php echo $email; ?>" required disabled>
                                                        <div class="invalid-feedback">
                                                            Please provide a valid email address.
                                                        </div>
                                                    </div>


                                                    <div class="mb-3 text-start">
                                                        <label for="password" class="form-label">Password</label>
                                                        <sub class="text-muted"> Leave it blank if your don't want to change. </sub>

                                                        <input type="password" class="form-control" name="Password" id="Password" pattern=".{8,}" placeholder="">
                                                        <div class="invalid-feedback password-error">
                                                            Please enter a password that is at least 8 characters long.
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 text-start">
                                                        <input type="password" class="form-control" id="ConfirmPassword" placeholder="Confirm password">
                                                        <div class="invalid-feedback confirm-password-error">
                                                            Please confirm your password.
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary shadow-2 mb-4">Update</button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
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


    <script>
        $(document).ready(function() {
            <?php echo $sweetAlert; ?>
            <?php echo $ajax; ?>
            <?php echo $validate; ?>

            $('#signupForm').on('submit', function() {
        event.preventDefault();
        var password = $('#Password').val();
        var confirmPassword = $('#ConfirmPassword').val();

        if(password.length!=0){
        if (password.length < 8) {
            $('#Password').addClass('is-invalid');
            $('.password-error').show();
            return false; // Prevent form submission
        } else {
            $('#Password').removeClass('is-invalid');
            $('.password-error').hide();
        }

        // Check if both password and confirm password fields are empty
        if (password === '' && confirmPassword === '') {
            $('#ConfirmPassword').addClass('is-invalid');
            $('.confirm-password-error').show();
            return false; // Prevent form submission
        }

        // Check if password and confirm password match
        if (password !== confirmPassword) {
            $('#ConfirmPassword').addClass('is-invalid');
            $('.confirm-password-error').show();
            return false; // Prevent form submission
        } else {
            $('#ConfirmPassword').removeClass('is-invalid');
            $('.confirm-password-error').hide();
        }
}



        var successCallback = function(response) {
          console.log(response);
            var data = JSON.parse(JSON.stringify(response));
          if (data.success) {
            Toast.fire({
              icon: 'success',
              title: data.message,
              timer: 2000,
            }).then(() => {
              location.reload();
              // window.location.href = window.origin+'/lms/admin';
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
         var formData = $(this).serialize();
        loadContent('../controllers/profileController.php', formData, successCallback, errorCallback);

    });





        });

        function generateBarcode(barcodeData) {
            // Options for barcode generation (change as needed)
            var options = {
                bcid: 'code128', // Barcode type (e.g., 'code128', 'ean13', 'qrcode', etc.)
                text: barcodeData, // Barcode data to encode
                scale: 3, // Scaling factor
                height: 8, // Height of the barcode
                includetext: true, // Show the data below the barcode
                textxalign: 'center', // Horizontal alignment of the data
            };

            // Generate the barcode
            bwipjs.toCanvas('barcodeContainer', options, function(err, canvas) {
                if (err) {
                    // Handle error
                    console.log("Error: " + err);
                } else {
                    // Barcode successfully generated
                    console.log("Barcode generated!");
                }
            });
        }
    </script>

</body>

</html>
