<?php
class SystemSettings {
    private $timezone;
    private $websiteTitle;
    private $scripts;
    private $styles;
    private $sweetAlert;
    private $ajax;
    private $validate;
    private $baseURL;
    private $bLogo;
    private $brlogo;
    private $logo;
    private $config;

    public function __construct() {
        $this->config = parse_ini_file('../config.ini', true);
        $this->loadSettings();
        $this->logFilePath = $this->getLogFilePath();
    }

    private function loadSettings() {
        $configs=$this->getConfig();
        $this->timezone = $configs['website']['timezone'];
        $this->websiteTitle = $configs['website']['name'];
        $this->baseURL=$configs['website']['base_url'];
        $this->bLogo=$configs['website']['blogo'];
        $this->brlogo=$configs['website']['brlogo'];
        $this->logo=$configs['website']['logo'];
        $this->styles = '
        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet">
        <link href="../assets/vendor/fontawesome/css/solid.min.css" rel="stylesheet">
        <link href="../assets/vendor/fontawesome/css/brands.min.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/flagiconcss/css/flag-icon.min.css" rel="stylesheet">
        <link href="../assets/vendor/airdatepicker/css/datepicker.min.css" rel="stylesheet">
        <link href="../assets/vendor/mdtimepicker/mdtimepicker.min.css" rel="stylesheet">
        <link href="../assets/vendor/datatables/datatables.min.css" rel="stylesheet">
        <link href="../assets/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet">
        
        ';
        $this->scripts = '
        <script src="../assets/vendor/jquery/jquery.min.js"></script>
        <script src="../assets/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/vendor/chartsjs/Chart.min.js"></script>
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/vendor/airdatepicker/js/datepicker.min.js"></script>
        <script src="../assets/vendor/airdatepicker/js/i18n/datepicker.en.js"></script>
        <script src="../assets/vendor/mdtimepicker/mdtimepicker.min.js"></script>
        <script src="../assets/vendor/datatables/datatables.min.js"></script>
        <script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
        ';
        $this->sweetAlert = "
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });";

      $this->ajax = "
      function loadContent(url, data, successCallback, errorCallback) {
          // Show loading SweetAlert2
        let loadingAlert = Swal.fire({
        allowOutsideClick: false,
         showConfirmButton: false,
         text: 'Loading. Please wait...',
        didOpen: () => {
            Swal.showLoading();
        }
      });

        $.ajax({
          url: url,
          method: 'POST',
          dataType: 'json',
          data: data,
          success: successCallback,
          error: errorCallback
        });
      }";

        $this->validate = "(function() {
            'use strict';
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation');
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();";
    }

    public function getTimezone() {
        return $this->timezone;
    }

    public function getConfig() {
     return $this->config;
   }



    public function getWebsiteTitle() {
        return $this->websiteTitle;
    }

    public function getBLogo() {
        return $this->bLogo;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function getBrlogo() {
        return $this->brlogo;
    }

    public function getScripts() {
        return $this->scripts;
    }

    public function getStyles() {
        return $this->styles;
    }

    public function getSweetAlertInit() {
        return $this->sweetAlert;
    }

    public function getAjaxInit() {
        return $this->ajax;
    }

    public function getBaseURL() {
        return $this->baseURL;
    }

    public function validateForms() {
        return $this->validate;
    }

    public function setDefaultTimezone() {
       date_default_timezone_set($this->timezone);
   }

    public function createLogFile($module, $logMessage) {
        $this->setDefaultTimezone();
        // Get the current time in 12-hour format
        $currentTime = date('h:i A');

        // Format the log entry
        $logEntry = "$currentTime [$module] - $logMessage" . PHP_EOL;

        // Check if the log folder exists, if not, create it
        $logFolder = dirname($this->logFilePath);
        if (!file_exists($logFolder)) {
            mkdir($logFolder, 0777, true);
        }

        // Write the log entry to the log file
        file_put_contents($this->logFilePath, $logEntry, FILE_APPEND);
    }

    private function getLogFilePath() {
        $this->setDefaultTimezone();
        // Get the current year, month, and date
        $currentYear = date('Y');
        $currentMonth = date('F');
        $currentDate = date('m-d-Y');
        // Get the project root folder
        $rootFolder = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'AxPortal';

        // Create the log folder path based on the current year, month, and date
        $logFolderPath =   $rootFolder . DIRECTORY_SEPARATOR . "Logs" . DIRECTORY_SEPARATOR . $currentYear . DIRECTORY_SEPARATOR . $currentMonth;

        // Create the log file path based on the log folder path and the current date
        $logFilePath = $logFolderPath . DIRECTORY_SEPARATOR . $currentDate . '.txt';

        // Return the full log file path
        return $logFilePath;
    }
}
