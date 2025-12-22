<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 06-Feb-2013				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
//require_once 'bootstrap.php';

/*
+-------------------------------------------------------+
| Module Authentication                                 |
+-------------------------------------------------------+
| */
$moduleId = 3;                                 /*  |
| */
require_once 'foo/session/moduleAuth.php';     /*  |
+-------------------------------------------------------+
*/
if (userModuleAuth($moduleId, $user_id, $loginname, $sessionid, $mysqli) !== TRUE) {
    die('<div align="center" style="font-size:120%;color:Red;">Module Access Denied.</div>');
}

// Variables
$a = $_GET['a'];

// Static Variables
$mX = array(
    "Jan" => '01',
    "Feb" => '02',
    "Mar" => '03',
    "Apr" => '04',
    "May" => '05',
    "Jun" => '06',
    "Jul" => '07',
    "Aug" => '08',
    "Sep" => '09',
    "Oct" => '10',
    "Nov" => '11',
    "Dec" => '12'
);

?><!-- Rajarshi Das -->

<head>
    <title>Concert :: MIS</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type='text/css' rel='stylesheet' href='/matchbox/themes/cool/style.css'>
    <link type='text/css' rel='stylesheet' href='/matchbox/themes/cool/timeTracker.css'>
    <link type='text/css' rel='stylesheet' href='foo/style.css'>
    <script type='text/javascript' src='foo/js/timeTracker.js'></script>
    <style>
        table {
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="windowBox2" style="text-align: center;">
        <?php

        // Header
        require_once 'foo/header.php';
        showHeader($hostname, 1, 1);

        // Custom styles
        // echo BD.'View/Template/styleMIS.php';
        // require_once BD.'View/Module/mis/Template/styleMIS.php';

        // Run View
        include 'view/' . $a . '.cgi';

        ?>
        <div class="footer"><?= $companyname ?></div>
    </div>
</body>