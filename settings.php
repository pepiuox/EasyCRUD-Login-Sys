<?php
if (!isset($_SESSION)) {
    session_start();
}
$mypage = $_SERVER['PHP_SELF'];
$page = $mypage;
require 'conn.php';
require 'autoload.php';
include 'top.php';
?>
</head>
<body>
    <?php
    include 'header.php';
    if ($session->logged_in) {
        include 'views/configurations.php';
    } else {
        header("location: http://$bUrl/");
    }
    include 'footer.php';
    ?> 
