<?php
class CustomSessionHandler {
    private $sessionTimeout = 1800; // Timeout in seconds - 10 minutes

    public function __construct() {
        // Start the session
        session_start();



    }

    public function setSessionVariable($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function unsetSessionVariable($key) {
        unset($_SESSION[$key]);
    }

    public function getSessionVariable($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function destroyAllSessionVariables() {
        $_SESSION = array();
    }

    public function isSessionVariableSet($key) {
       return isset($_SESSION[$key]);
   }

    public function checkSessionExpiration() {
        if (isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] > $this->sessionTimeout) {
            // Session has expired, destroy it
            session_destroy();
            header("Location: ../");
        }

        // Update last activity time
        $_SESSION['LAST_ACTIVITY'] = time();

    }

    public function checkSessionExpirationAsync() {
        if (isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] > $this->sessionTimeout) {
          return true;
        }else{
          return false;
        }
    }
}
 ?>
