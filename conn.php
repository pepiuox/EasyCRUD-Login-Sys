<?php

/* Credentials */

define('DBHOST', 'localhost'); // Add your host
define('DBUSER', 'root'); // Add your username
define('DBPASS', 'truelove'); // Add your password
define('DBNAME', 'forever'); // Add your database name

/*
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', 'truelove');
define('DBNAME', 'empresas');
*/
/* MySQLi Procedural */


$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

/* If connection fails for some reason */
if ($conn->connect_error) {
    die('Error, Database connection failed: ('. $conn->connect_errno .') '. $conn->connect_error);
}

$link = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

if ($link->connect_error) {
    die('Error, Database connection failed : ('. $link->connect_errno .') '. $link->connect_error);
}
$base = 'http://'.$_SERVER['HTTP_HOST'].'/EasyCRUD-Login-Sys/';
?>