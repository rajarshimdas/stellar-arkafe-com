<!DOCTYPE html>
<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 16-May-09	(Noida)				            |
| Updated On: 						                    |
+-------------------------------------------------------+
| SMS :: Change Password(Form)				            |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password</title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>matchbox/favicon.png">

    <link type='text/css' rel='stylesheet' href='<?= BASE_URL ?>matchbox/themes/cool/style.css'>
    <link href='<?= $base_url ?>matchbox/fonts-and-ui.css' rel='stylesheet' type='text/css'>
</head>

<body>
    <div class="windowBox">
        <?php
        $moduleName = "Password Change";
        include $w3root . '/studio/sms/foo/header.cgi';
        ?>

        <table border="0" bgcolor="#8bcaf2" style="text-align:left;width:1000px;color:RGB(60,60,60);" cellpadding="0" cellspacing="4">
            <tr>
                <td style="width: 500px; height: 50px; text-align: right">Password Changed.</td>
                <td><a class="button" href="home.cgi">Home</a></td>
            </tr>
        </table>

    </div>
</body>

</html>