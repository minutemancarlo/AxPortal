<?php

if (isset($_COOKIE['Password'])) {
    $username = $_COOKIE['Password'];
    echo "Hello, $username!";
} else {
    echo "Cookie not set!";
}
 ?>
