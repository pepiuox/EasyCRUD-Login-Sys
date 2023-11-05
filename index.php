<?php
if (!isset($_SESSION)) {
    session_start();
}
require 'conn.php';
require 'autoload.php';

$login = new UserClass();

if ($login->isLoggedIn() === true) {
    $_SESSION['access'] = '';

    extract($_POST);

    ob_start();

    $hostlk = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $lpath = "https";
    } else {
        $lpath = "http";
    }
    $lpath .= "://";
    $lpath .= $_SERVER['HTTP_HOST'];
    $lpath .= $_SERVER['PHP_SELF'];

    $fileName = basename($_SERVER['PHP_SELF']);
    $fl = $hostlk . $fileName;

    $path = basename($_SERVER['REQUEST_URI']);
    $file = basename($path);

    if ($file == $fileName) {
        header("Location: index.php?w=select");
        exit();
    }

    $level = new AccessLevel();
    $p = new Protect();
    if (isset($_GET['w']) && !empty($_GET['w'])) {
        $w = $p->secureStr($_GET['w']);
    }

    $c = new MyCRUD();
}
include 'top.php';
?>
<style>
    .pagination {
        list-style-type: none;
        margin: 0 auto;
        padding: 10px 0;
        display: inline-flex;
        justify-content: space-between;
        box-sizing: border-box;
    }

    .pagination li {
        box-sizing: border-box;
        padding-right: 10px;
    }

    .pagination li a {
        box-sizing: border-box;
        background-color: #e2e6e6;
        padding: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: bold;
        color: #616872;
        border-radius: 4px;
    }

    .pagination li a:hover {
        background-color: #d4dada;
    }

    .pagination .next a, .pagination .prev a {
        text-transform: uppercase;
        font-size: 12px;
    }

    .pagination .currentpage a {
        background-color: #518acb;
        color: #fff;
    }

    .pagination .currentpage a:hover {
        background-color: #518acb;
    }
</style>
</head>
<body>
    <?php
    include 'header.php';
    if ($login->isLoggedIn() === true) {
        include ("views/perucompras.php");
    } else {
        include ("views/loginFalse.php");
    }
    ?>
    <div style="height: 30px;"></div>
</body>
</html>

