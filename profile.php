<?php
if (!isset($_SESSION)) {
    session_start();
}
require 'conn.php';
require 'autoload.php';
$level = new AccessLevel();
$login = new UserClass();
include 'functions/functions.php';
include 'top.php';
?>
</head>
<body>
    <?php
    include 'header.php';
    if ($login->isLoggedIn() === true) {
        include ("views/accountSettings.php"); // Else prompt login form
    } else {
        header('Location: index.php'); // If user is not logged in redirect back to index.php
    }
    ?>
</body>
</html>