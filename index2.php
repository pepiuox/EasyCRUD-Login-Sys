<?php

if (!isset($_SESSION)) {
    session_start();
}

require 'autoload.php';

$login = new UserClass();

if ($login->isLoggedIn() === true) {
    include ("views/loginTrue.php");
} else {
    include ("views/loginFalse.php");
}