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
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password</title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>matchbox/favicon.png">

    <link type='text/css' rel='stylesheet' href='<?= BASE_URL ?>matchbox/themes/cool/style.css'>
    <link href='<?= $base_url ?>matchbox/fonts-and-ui.css' rel='stylesheet' type='text/css'>
</head>

<body style="background-color: var(--rd-login-background);">
    <div class="windowBox" style="padding-top: 50px;">

        <?php
        $moduleName = "Password Change";
        include $w3root . '/studio/sms/foo/header.cgi';
        ?>
        <style>
            table#tblForm {
                text-align: left;
                width: 1000px;
                color: RGB(60, 60, 60);
                padding: 8px 0px;
                background-color: white;
            }
        </style>
        <table id="tblForm" cellpadding="0" cellspacing="4">
            <form action="passwordx.cgi" method="POST">
 
                <tr style="line-height:25px;">
                    <td align="right" valign="bottom" style="width:350px;">
                        Username:&nbsp;
                    </td>
                    <td align="left" valign="bottom" style="width:400px;">
                        <?php echo $loginname; ?>
                    </td>
                    <td style="width:20%;">&nbsp;</td>
                </tr>

                <tr>
                    <td align="right" valign="baseline">
                        Current Password:&nbsp;
                    </td>
                    <td align="left" valign="baseline">
                        <input id="pw" type="password" name="pw" style="width:100%;font-size:105%;">
                    </td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td align="right" valign="baseline">New Password:&nbsp;</td>
                    <td align="left" valign="baseline">
                        <input type="password" id="p1" name="p1" style="width:100%;font-size:105%;">
                    </td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td align="right" valign="baseline">Confirm New Password:&nbsp;</td>
                    <td align="left" valign="baseline">
                        <input type="password" name="p2" style="width:100%;font-size:105%;">
                    </td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td align="right" valign="top" style="height:25px">&nbsp;</td>
                    <td align="center" valign="top">
                        <input class="button" type="submit" name="go" style="width:150px" value="Change Password">
                        <input class="button" type="submit" name="go" style="width:150px" value="Cancel">
                    </td>
                    <td>&nbsp;</td>
                </tr>

            </form>
        </table>
        
        <?php
        // Display the Messages
        if ($message = $_GET['e']) {
            echo "<div class='windowbox' style='text-align: center;background-color: white; padding: 5px;'><span style='font-weight:normal;color:red;'>$message</span></div>";
        }
        ?>
        <div style="text-align: center; color: white;">
            <div style="padding: 10px;">
                The Worksmart Architecture Studio Organizer
            </div>
            <a href="https://arkafe.com" target="_blank">
                <img src="<?= BASE_URL ?>da/arkafe5.png" alt="Arkafe | Digital Transformation for Architects" style="width:80px">
            </a>
        </div>

    </div>
</body>
<script>
    window.onload = (event) => {
        document.getElementById("pw").focus()
    };
</script>

</html>