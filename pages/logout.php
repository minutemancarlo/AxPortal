<?php
require_once '../classess/SessionHandler.php';

$session = new CustomSessionHandler();

$session->destroyAllSessionVariables();
header("Location: ../");
 ?>
