<?php /* Timesheets Module
+---------------------------------------------------+
| Rajarshi Das					                    |
+---------------------------------------------------+
| Created On: 12-Aug-09	(Dad's Birthday)	        |
| Updated On: 16-Sep-10				                |
+---------------------------------------------------+
*/

require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
$moduleName = "Timesheets";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timesheets</title>
    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">

    <script type='text/javascript' src='/matchbox/api/jquery.min.js'></script>
    <script type='text/javascript' src="/matchbox/plugins/phpFunctionsInJavascript.js"></script>
    <link rel="stylesheet" type="text/css" href="/matchbox/mbox/calendar/styles.css" />
    <script type="text/javascript" src="/matchbox/mbox/calendar/classes.js"></script>
    <link href='/matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="moo/timesheet.js"></script>
</head>

<body>
    <div class="windowBox2">
        <table id="table" style="width: 100%;" cellpadding="0" cellspacing="0">
            <tr>
                <td><?php require_once $w3root . '/studio/sms/foo/header.cgi'; ?></td>
            </tr>
            <tr>
                <td>
                    <?php
                    $a = (isset($_GET["a"])) ? $_GET["a"] : "timesheet";
                    require_once __DIR__ . '/hot7e/' . $a . '.cgi';
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="windowBox2 footer">
        <?= $companyname ?>
    </div>
    
</body>

</html>