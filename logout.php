<?php

if (!isset($_SESSION)) {
    session_start();
}

$_SESSION = array(); // Unset all of the session variables.

session_destroy(); // Destroy all session data.
header('Location: index.php');
