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
        header('Location: index.php'); // If user is already logged in redirect back to index.php
    } else {
        include 'views/register.php';
    }
    ?>
</body>
</html>