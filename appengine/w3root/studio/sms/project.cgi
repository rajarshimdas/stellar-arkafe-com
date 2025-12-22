<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007				                |
| Updated On: 14-Feb-2011				                |
+-------------------------------------------------------+
| Studio Management System Module - Front Controller    |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
require_once 'foo/commonFunctions.cgi';
require_once BD . '/Controller/Common.php';

if (!$projectname) die("<div>Error:: No Projectname</div>");
$moduleName = "Project Tracker";

$pid = $project_id;
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $projectname ?></title>
    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">

    <script type='text/javascript' src='/matchbox/api/jquery.min.js'></script>

    <script type='text/javascript' src='/matchbox/api/jquery-ui/jquery-ui.min.js'></script>
    <link type='text/css' rel='stylesheet' href='/matchbox/api/jquery-ui/jquery-ui.min.css'>

    <link type='text/css' rel='stylesheet' href='/matchbox/themes/cool/style.css'>

</head>

<body>
    <div class="windowBox2">
        <table cellpadding="0" cellspacing="0" style="width: 100%; margin: auto;">
            <tr>
                <td id="header">
                    <?php include 'foo/header.cgi'; ?>
                </td>
            </tr>
        </table>
    </div>
    <div style="background-color: var(--rd-nav-light); text-align: center;">
        <div class="windowBox2">
            <table cellpadding="0" cellspacing="0" style="width: 100%; margin: auto;">
                <tr style="text-align: center;">
                    <td id="t2"><?php /* Tabs */ include 'foo/nav-tabs.cgi'; ?></td>
                </tr>
                <tr>
                    <td id="nav">
                        <?php /* Current Tab Specific menus */
                        include "$activetab/index.cgi";
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="windowBox2">
        <table cellpadding="0" cellspacing="0" style="width: 100%; margin: auto;">
            <tr>
                <td class="contents" align="center">
                    <?php /*Contents */ include "$activetab/$activemenucontent.cgi"; ?>
                </td>
            </tr>
            <tr>
                <td><?php /* Error Prompts */ include 'foo/eprompts.cgi'; ?></td>
            </tr>
            </tbody>
        </table>
        <?php
        if ($DisplaySessionInfo > 0) include('foo/statusbar.cgi');
        ?>
    </div>
    <div class="windowBox2 footer">
        <?= $companyname ?>
    </div>
    <script>
        function dxShow(dxId) {
            document.getElementById(dxId).showModal()
        }
        function dxClose(dxId) {
            document.getElementById(dxId).close()
        }
    </script>
    <script type='text/javascript' src='<?= BASE_URL ?>matchbox/bad-dragon.js'></script>

</html>