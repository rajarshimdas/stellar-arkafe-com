<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <link href='<?= $base_url ?>matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>
    <link href="<?= $base_url ?>matchbox/favicon.png" rel="icon" type="image/x-icon">

    <link href='<?= $base_url ?>matchbox/bad-dragon.css' rel='stylesheet' type='text/css'>
    <script type='text/javascript' src='<?= $base_url ?>matchbox/bad-dragon.js'></script>
</head>

<body>
    <div id="page-lock-overlay"><!-- Loading... --></div>
    <div style="background-color: var(--rd-nav-light);width:100%;">
        <div class="windowBox2" style="padding: 0px;">

            <?php
            /* 
            +-----------------------------------------------+
            | Variables required and sample values          |
            +-----------------------------------------------+
            $title          = "MIS | Timesheet Status";
            $moduleName     = "MIS";
            */
            require_once BD . "View/Module/generateBanner.php";

            $rm = $route->method;
            ?>

            <table style="width: 100%; border: 0px; border-spacing: 0px; border-collapse: collapse;" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:350px;">
                        <?php require_once BD . 'View/Module/setActiveProject.php'; ?>
                    </td>
                    <td id="t2">

                        <div id="tabs">
                            <ul>
                                <?php
                                # $tsPortalTabs defined in LocalSettings.php | 15-Apr-25
                                // rx($tsPortalTabs);
                                foreach ($tsPortalTabs as $tab):
                                ?>
                                    <li>
                                        <a href="<?= BASE_URL . 'concert/portal/' . $tab[0] ?>" <?= bdActiveTab($tab[0], $rm) ?>>
                                            <span><?= $tab[1] ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td id="nav" colspan="2">

                        <?php
                        $tm = $tabMenu[$rm];
                        $role = bdUserRoleInProject($user_id, $pid, $mysqli);
                        $rid = isset($role) ? $role["role_id"] : 50;

                        for ($i = 0; $i < count($tm); $i++) {
                            // echo "<div>$rid | ".$tm[$i][2]."</div>";
                            if ($rid <= $tm[$i][2]) {
                        ?>
                                <a href="<?= BASE_URL ?>concert/portal/<?= $rm ?>/<?= $tm[$i][0] ?>" <?php bdActiveTab($page, $tm[$i][0]) ?>><?= $tm[$i][1] ?></a>
                        <?php
                            }
                        }

                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div>

        <?php
        #echo 'page: ' . $page . ' | method: ' . $rm;
        $view = BD . "View/Module/Concert/$rm/$page.php";

        if (file_exists($view)) {
            $displaySelectProject = 1;
            require_once $view;
        } else {
            echo "View [" . $view . "] does not exist.";
        }

        if ($pid < 100 && $displaySelectProject > 0) {
            echo "<div style='width:100%;padding:25px 0px;text-align:center;background-color:RGB(240,240,240);'>
                    <div style='opacity:0.35;'><img src='" . BASE_URL . "da/fa5/building.png' alt='Project' style='width:150px'></div>
                    Please select a project
                </div>";
        }
        ?>
    </div>

    <div class="windowBox2 footer">
        <?= $companyname . ' | ' . $cnfLocalSettings ?>
    </div>
    </dialog>
    <dialog id="dxAlertBox">
        <table class='dxTable' style="width:400px;">
            <tr>
                <td id="dxAlertBoxH" style="font-weight: bold; color: red;">
                    <!-- Alert Header goes here -->
                </td>
                <td style="width: 30px;">
                    <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" onclick="dxClose('dxAlertBox')">
                </td>
            </tr>
            <tr>
                <td id="dxAlertBoxM">
                    <!-- Alert Message goes here -->
                </td>
            </tr>
        </table>
    </dialog>
    <script>
        function dxShow(dxId, setFocusId) {
            // console.log(dxId + ' show')
            document.getElementById(dxId).showModal()
            if (setFocusId != 'NA') {
                document.getElementById(setFocusId).focus()
            }
        }

        function dxClose(dxId) {
            // console.log(dxId + ' close')
            document.getElementById(dxId).close()
        }

        function showAlert(header, message) {
            const dxAlertBox = document.getElementById("dxAlertBox")
            document.getElementById("dxAlertBoxH").innerHTML = header
            document.getElementById("dxAlertBoxM").innerHTML = message

            dxAlertBox.showModal()
        }

        function dxAlertBox(header, message) {

            const dxAlertBox = document.getElementById("dxAlertBox")

            document.getElementById("dxAlertBoxH").innerHTML = header
            document.getElementById("dxAlertBoxM").innerHTML = message

            dxAlertBox.showModal()
        }
    </script>

    <script src="<?= $base_url ?>matchbox/api/jquery.min.js"></script>
</body>

</html>