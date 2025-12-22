<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <link href='<?= $base_url ?>matchbox/themes/cool/style-v01.css' rel='stylesheet' type='text/css'>
    <link href="<?= $base_url ?>matchbox/favicon.png" rel="icon" type="image/x-icon">

    <link href='<?= $base_url ?>matchbox/bad-dragon.css' rel='stylesheet' type='text/css'>
    <script type='text/javascript' src='<?= $base_url ?>matchbox/bad-dragon.js'></script>

</head>

<body>

    <div style="background-color: var(--rd-nav-light);width:100%;">
        <div class="windowBox2" style="padding:0px;/*border-bottom:8px solid var(--rd-nav-dark);*/">

            <?php
            /* 
            +-----------------------------------------------+
            | Variables required and sample values          |
            +-----------------------------------------------+
            $title          = "MIS | Timesheet Status";
            $moduleName     = "MIS";
            */
            require_once BD . "View/Module/generateBanner.php";

            $activeTab = $route->method;
            ?>

            <table style="width: 100%; border: 0px; border-spacing: 0px; border-collapse: collapse;" cellspacing="0" cellpadding="0">
                <tr>
                    <td id="t2">
                        <div id="tabs">
                            <ul>
                                <li>
                                    <a href="<?= BASE_URL ?>cost/ui/snapshot/firmwide" <?= bdActiveTab('snapshot', $activeTab) ?> style="width:250px;">
                                        <span>Cost MIS Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= BASE_URL ?>cost/ui/settings" <?= bdActiveTab('settings', $activeTab) ?>>
                                        <span>Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php
                /* 
                +-----------------------------------------------+
                | Nav Links                                     |
                +-----------------------------------------------+
                */
                $navLinks = [
                    "snapshot" => [
                        ["Firmwide", 'firmwide'],
                        ["Project", 'project'],
                    ],
                    "settings" => [],
                ];

                $activeLink = (empty($route->parts[3])) ? "X" : $route->parts[3];
                // echo $activeLink;

                if (!empty($navLinks[$activeTab])):
                    $links = $navLinks[$activeTab];
                ?>
                    <tr>
                        <td id="nav" colspan="2">
                            <?php
                            foreach ($links as $ln):
                                $linkName = $ln[0];
                                $linkPath = $ln[1];
                            ?>
                                <a <?= bdActiveTab($linkPath, $activeLink) ?> href="<?= BASE_URL ?>cost/ui/<?= $activeTab . '/' . $linkPath ?>">
                                    <?= $linkName ?>
                                </a>
                            <?php
                            endforeach;
                            ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="windowBox2">

        <?php
        $page = isset($route->parts[3]) ? $route->parts[3] : 'index';

        #echo 'page: ' . $page . ' | method: ' . $activeTab;
        $view = BD . "View/Module/Cost/$activeTab/$page.php";

        if (file_exists($view)) {
            require_once $view;
        } else {
            echo "View [" . $view . "] does not exist.";
        }

        if ($pid < 100 && $displaySelectProject > 0) {
            echo "<div style='width:100%;text-align:center;'>
                    <div style='opacity:0.35;'><img src='" . BASE_URL . "da/fa5/building.png' alt='Project' style='width:150px'></div>
                    Please select a project
                </div>";
        }
        ?>
    </div>

    <div class="windowBox2 footer">
        <?= $companyname ?>
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

    <script type='text/javascript'>
        const apiUrl = "<?= $base_url ?>index.cgi"
        const activeFinancialYear = '<?= $activeFinancialYear ?>'

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