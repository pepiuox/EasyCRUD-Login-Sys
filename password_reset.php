<?php
if (!isset($_SESSION)) {
    session_start();
}
require 'conn.php';
require 'autoload.php';
$level = new AccessLevel();
$login = new UserClass();

include 'top.php';
?>
</head>
<body>
    <?php
    include 'header.php';
    /* Call for login function */
    if ($login->isLoggedIn() === true) {
        header('Location: index.php'); // If user is already logged in redirect back to index.php
    } else {
        include ("views/passwordResetForm.php"); // Else prompt login form
    }
    ?>
</body>
</html>

