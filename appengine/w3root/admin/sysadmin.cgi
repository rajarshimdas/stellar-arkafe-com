<?php
/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On:   07-Jan-09				                |
| Updated On:   25-May-2010 UI                          |
|               24-Oct-2023 Message - Success/Error     |
+-------------------------------------------------------+
| Generates the Admin Panel form 			            |
+-------------------------------------------------------+
| Update: 25-May-2010 				                    |
|	Total layout changed and added GUIs for		        |
|	user and project details editing		            |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
require_once $w3etc . '/env.php';
require_once 'boo/moduleConfig.php';

// Session Vars
require_once 'boo/sessionvars.php';


// Message Bar
$flag = 0; /* 0 is hidden, 1 is success and 2 is error */
$message = 'Message';

if (isset($_GET['flag'])){
    $flag = $_GET['flag'];
} elseif (isset($_POST['flag'])){
    $flag = $_POST['flag'];
}

if (isset($_GET['message'])){
    $message = $_GET['message'];
} elseif (isset($_POST['message'])){
    $message = $_POST['message'];
}


function messagebar($message, $flag)
{
    $flagX = ['messagebarHidden', 'messagebarSucccess', 'messagebarError'];
    if ($flag > 0) echo '<div class="' . $flagX[$flag] . '">' . $message . '</div>';
}

/*
+-------------------------------------------------------+
|// Run Form Action if any                              |
+-------------------------------------------------------+
*/
if (isset($_POST["x"])) {

    require_once $w3etc . '/foo/ci3_form_validation.php';

    // Action Script
    $x = $_POST["x"];
    require "boo/admin/$x.php";
}

// Parse URL and Menu functions
include 'boo/parse-url.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>System Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">

    <link type='text/css' href='<?= BASE_URL ?>matchbox/themes/cool/style.css' rel='stylesheet'>
    <script type='text/javascript' src='<?= BASE_URL ?>matchbox/api/jquery.min.js'></script>
    <script type='text/javascript' src='<?= BASE_URL ?>matchbox/bad-dragon.js'></script>
    <script type='text/javascript' src='<?= BASE_URL ?>admin/sysadmin.js'></script>

    <style>
        body {
            margin: 5px 0 30px 0;
        }

        .tabulation tr td {
            padding-left: 10px;
        }

        input,
        select {
            width: calc(100% - 10px);
        }

        .dataTbl tr {
            height: 20px;
        }
    </style>
    <!-- Calendar -->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>matchbox/mbox/calendar/styles.css" />
    <script type="text/javascript" src="<?= BASE_URL ?>matchbox/mbox/calendar/classes.js"></script>
</head>

<body>
    <div style="width: 800px; margin:auto;">

        <table style="width:800px" cellpadding="0" cellspacing="0">
            <tr>
                <td width="210px" align="center" style="vertical-align:top;text-align:left;">
                    <img alt="Logo" src="/da/arkafe-a-dark.png" style="height:35px;">
                </td>

                <td style="height: 35px; vertical-align: middle;">
                    <table style="width:100%;">
                        <tr>
                            <td style="text-align: center;">
                                <?= $fullname ?>
                            </td>
                            <td style="text-align: center; width: 150px; color: gray;">
                                <?= date("D, d M Y") ?>
                            </td>
                            <td style="width: 60px;">
                                <a href="<?= BASE_URL ?>studio/home.cgi" class="button">Home</a>
                            </td>
                            <td style="width: 60px;">
                                <a href="<?= BASE_URL ?>studio/logout.cgi" class="button">Logout</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td valign="top"><?php include('boo/menu.php'); ?></td>
                <td valign="top">
                    <?php
                    messagebar($message, $flag);

                    // $content comes from menubar function of parse-url.php
                    if (isset($content)) {
                        include("boo/admin/$content");
                    } else {
                        echo "Nothing here.";
                    }

                    ?>
                </td>
            </tr>

        </table>
    </div>
    <?php include $w3root . '/studio/foo/dialog/alert.cgi'; ?>

</body>