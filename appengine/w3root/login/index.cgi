<?php /* Log-in Page | Nginx leaky bucket algorithm
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jan-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientPaths.cgi';
require_once $w3etc . '/LocalSettings.php';
?>
<!DOCTYPE html>

<head>
    <title><?= 'Sign in | ' . $companyname ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">
    <!-- JQuery 3.6.1 -->
    <script src="<?= $base_url ?>matchbox/api/jquery.min.js"></script>
    <!-- Concert CSS -->
    <link href="<?= $base_url ?>matchbox/themes/cool/style.css" rel="stylesheet" type="text/css">
    <link href='<?= $base_url ?>matchbox/themes/cool/fonts-and-ui.css' rel='stylesheet' type='text/css'>
    <link href='<?= $base_url ?>matchbox/portal.css' rel='stylesheet' type='text/css'>
</head>

<body class="loginPage">
    <div id="boxLoginForm">

        <img id="logo" src="<?= $base_url ?>da/stellar.jpeg" style="height:150px;" alt="logo">
        <form action="sign-in.cgi" method="post" accept-charset="utf-8">
            <table class="loginForm">
                <tr>
                    <td><input id="uname" name="uname" type="text" placeholder="Username" /></td>
                    <td>
                        <input name="passwd" type="password" placeholder="Password" />
                        <div style="display: none;">
                            <input name="captcha" type="text" placeholder="Captcha" />
                        </div>
                    </td>
                    <td><button type="submit" class="button">Sign in</button></td>
                </tr>
            </table>
        </form>

        <div id="login_message" style="font-weight: bold; color: red;">
            <?php
            if (isset($_GET["e"])) {
                echo "Please re-login...";
            }
            ?>
        </div>

    </div>
    <div style="width: 600px; margin:auto; text-align:center;color:white;">
        <div style="padding: 10px;">
            The Worksmart Architecture Studio Organizer
        </div>
        <a href="https://arkafe.com" target="_blank">
            <img src="<?= $base_url ?>da/arkafe5.png" alt="Arkafe | Digital Transformation for Architects" style="width:80px">
        </a>
    </div>
    <script>
        $(function() {
            $('#login_message').delay(5000).fadeOut('slow');
            $("#uname").focus();
        });
    </script>
</body>

</html>