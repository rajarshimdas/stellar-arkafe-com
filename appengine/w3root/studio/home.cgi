<?php
/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 09-Aug-09			                	    |
| Updated On: 17-Jan-12			                	    |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
// echo $avatar;
require_once BD . 'Controller/Common.php';
require_once BD . 'Controller/Cost/Cost.php';

?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= $base_url ?>matchbox/favicon.png">
    <!-- JQuery 3.6.1 -->
    <script src="<?= $base_url ?>matchbox/api/jquery.min.js"></script>
    <!-- Pure CSS | Yahoo -->
    <link href='<?= $base_url ?>matchbox/api/pure-css/pure-nr-min.css' rel='stylesheet' type='text/css'>
    <!-- Concert CSS -->
    <link href='<?= $base_url ?>matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>

    <script src="<?= $base_url ?>studio/home.js"></script>
</head>

<body style="background-color: var(--rd-login-background);">
    <div class="windowBox">
        <style>
            table.tableBox {
                border-collapse: collapse;
            }

            table.tableBox tr td {
                border: 0px solid red;
                background-color: white;
            }

            table.tableBox tr.whiteBorder {
                height: 110px;
                border-bottom: 3px solid var(--rd-login-background);
            }

            img#avatar {
                border-radius: 50%;
            }

            img#avatar:hover {
                cursor: pointer;
            }
        </style>

        <table class="tableBox" style="width:1000px; margin-top: 50px;">

            <!-- Banner -->
            <tr style="height: 65px;">
                <td style="width: 220px;">
                    <img src="<?= $base_url ?>da/arkafe-a-dark.png" style="height:35px; padding-left:15px;">
                </td>
                <td style="width: 70px; text-align:center;">
                    <img id="avatar" src="<?= $avatar ?>" width="32px" height="32px" onclick="dxShow('dxProfile')" title="Click to change Avatar">
                </td>
                <td></td>
                <td style="width: 140px;"></td>
                <td style="width:100px;">
                    <img src="<?= $base_url ?>da/logo-homepage.jpg" style="height:45px;">
                </td>
            </tr>

            <!-- Welcome Line & Settings #8bcaf2 -->
            <tr class="topRow whiteBorder" style="height: 45px;">
                <td class="homeCell2 firstCell" style="font-size:1.25em;font-weight:bold;text-align:center;color:var(--rd-nav-dark);">
                    <?php echo $fullname; ?>
                </td>
                <td class="homeCell2 secondCell" colspan="3" style="text-align: right;">
                    <?php
                    // Show Buttons
                    bdShowCostButton($loginname, $bdModuleAccess);
                    showMISButton($userid, $loginname, $sessionid, $mysqli);
                    showSysAdminButton($userid, $loginname, $sessionid, $mysqli);


                    # Switch User | 01-May-2025
                    # LocalSettings
                    #
                    if (in_array(strtolower($loginname), $adminSwitchUser)):
                    ?>
                        <a class="button" href="<?= BASE_URL ?>studio/switch-user.cgi">Switch User</a>
                    <?php endif ?>

                </td>
                <td>
                    <style>
                        table#my-buttons {
                            padding: 0px;
                            margin: 0px;
                            width: 100%;
                            border: 0px;
                        }

                        table#my-buttons tr {
                            height: 30px;
                            padding: 0px;
                            margin: 0px;
                            text-align: center;
                        }

                        table#my-buttons tr td {
                            border: 0px;
                        }

                        table#my-buttons tr td img {
                            height: 30px;
                            opacity: 1;
                        }

                        table#my-buttons tr td img:hover {
                            opacity: 0.5;
                        }

                        td.homeCell1,
                        td.homeCell2 {
                            background-color: var(--rd-home-box1);
                            text-align: left;
                        }

                        td.homeCell3 {
                            background-color: var(--rd-home-box2);
                        }
                        .fa5button
                        {height: 30px;width: 30px;}
                    </style>
                    <table id='my-buttons'>
                        <tr>
                            <td style="padding: 0px;">
                                <!-- Other buttons -->
                            </td>
                            <td style="width: 35px; padding: 0px;">
                                <!-- Profile Settings 
                                <img class="fa5button" src="<?= $base_url ?>da/fa5/cog-w.png" onclick="dxShow('dxProfileSettings')" valign="middle">
                                -->
                            </td>
                            <td style="width:40px; padding: 0px;">
                                <a href="password.cgi" title="Change Password">
                                    <img class="fa5button" src="/da/icons/password-w.png" alt="Change Password" valign="middle">
                                </a>
                            </td>
                            <td style="width:40px;padding:0px;text-align:left;">
                                <a href="logout.cgi" title="Logout">
                                    <img class="fa5button" src="<?= $base_url ?>da/fa5/power-off.png" valign="middle">
                                </a>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>



            <!-- Project Binder -->
            <form action="go2/project.cgi" method="POST" accept-charset="UTF-8">

                <input type="hidden" name="sx" value="<?php echo $sessionid; ?>">
                <input type="hidden" name="uid" value="<?php echo $userid; ?>">
                <input type="hidden" name="ln" value="<?php echo $loginname; ?>">

                <tr class="whiteBorder">
                    <td class="homeCell1" style="text-align: center;">
                        <img src="sms/foo/images/project.png" height="60px" alt="Project" title="Project Binder :: Manage your project Information, Schedule, Drawing Lists, Contacts, Transmittals etc.">
                    </td>
                    <td class="homeCell2">
                        Projects
                    </td>
                    <td class="homeCell2">
                        <select name="pid" style="width:100%">
                            <option value="0">-- Select Project --</option>
                            <?php
                            include('sms/foo/ShowProjects.php');
                            $ProjX = ShowProjects($userid, $mysqli);
                            $totalprojects = isset($ProjX) ? sizeof($ProjX) : 0;

                            for ($i = 0; $i < $totalprojects; $i++) {
                                echo "<option value='" . $ProjX[$i]["id"] . "'>" . $ProjX[$i]["pn"] . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td class="homeCell2" style="text-align:left;">
                        <input class="button" type="submit" name="go" value="Go">
                    </td>
                    <td class="homeCell3">
                        <!-- <?= rdWorkInProgress() ?> -->
                    </td>
                </tr>
            </form>




            <!-- Task Management & Timesheets -->
            <tr class="whiteBorder">
                <td class="homeCell1" style="text-align: center;">
                    <img src="<?= $base_url ?>da/project.png" alt="Tasks" height="60px" title="Task Management & Timesheet">
                </td>

                <td class="homeCell2" colspan="2" style="text-align:right;">
                    <style>
                        table#tsButtons {
                            border-collapse: collapse;
                            width: 100%;
                            border: 0px;
                            padding: 0px;
                            margin: 0px;
                            background-color: var(--rd-home-box1);
                        }

                        table#tsButtons tr td {
                            border: 0px solid blue;
                            padding: 0px;
                            margin: 0px;
                            text-align: center;
                        }

                        button {
                            cursor: pointer;
                        }
                    </style>
                    <table id="tsButtons">
                        <tr style="height: 35px;">
                            <td style="text-align: left;">
                                <div style="padding: 5px 0px;">Task Management & Timesheet</div>
                                <div>
                                    <?php include __DIR__ . '/foo/statusbox.php'; ?>
                                </div>
                            </td>
                            <td style="width: 35px;">
                                <?php
                                require_once $w3etc . '/foo/getTeamList.php';
                                $team = rdGetMyTeamList($userid, $mysqli);
                                // var_dump($team);
                                if (count($team) > 1) {
                                    echo '<img class="fa5button" src="' . $base_url . 'da/fa5/user-clock.png" title="View Team Timesheets" onclick="dxUserTSopen()">';
                                }
                                ?>
                            </td>
                            <td style="width: 35px;">
                                <img class="fa5button" src="<?= $base_url ?>da/fa5/list.png" title="View My Timesheets" onclick="dxTimesheetList(<?= $userid ?>)">
                            </td>
                        </tr>
                    </table>

                </td>
                <td class="homeCell2" style="text-align:left;">
                    <a class="button" href="<?= BASE_URL ?>concert/portal/timesheet">Go</a>
                    <?php /* Manage button */ include $w3root . '/studio/tms/btnManageByRM.cgi';  ?>
                </td>
                <td class="homeCell3" style="text-align:center;">
                    <!-- <?= rdWorkInProgress() ?> -->
                </td>
            </tr>


            <!-- HRMS | Leave Management -->
            <tr class="whiteBorder">
                <td class="homeCell1" style="text-align: center;">
                    <img src="<?= $base_url ?>da/leave.png" alt="Leave" height="60px" title="HRMS | Leave Management">
                </td>
                <td class="homeCell2" colspan="2">
                    <div>HRMS | Leave Management</div>
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <?php

                                define('W3APP', $w3path . 'modules/hrms/App');
                                require_once $w3path . 'modules/hrms/App/Controller/Controller.php';
                                require_once $w3path . 'modules/hrms/App/Controller/Aec/Aec.php';

                                $subs = bdGetSubordinatesAndMe($uid, $mysqli);
                                // var_dump($subs);
                                $subs_co = empty($subs) ? 0 : count($subs);

                                if ($subs_co > 1):
                                    $usersById = bdGetUsersById($mysqli);
                                    // var_dump($usersById);
                                    $subs = bdGetSubordinates($uid, $mysqli);
                                    $leaveReq = bdGetLeaveRequestForUsers($subs, $mysqli);
                                    // var_dump($leaveReq);

                                    // Counts
                                    $co_log = empty($leaveReq) ? 0 : count($leaveReq);
                                    $co_app = 0; // Approval | status_id: 5
                                    $co_rev = 0; // Revoke | status_id: 30

                                    foreach ($leaveReq as $x) {
                                        if ($x['status_id'] == 5) {
                                            $co_app++;
                                        } elseif ($x['status_id'] == 30) {
                                            $co_rev++;
                                        }
                                    }

                                    // Get users leave status
                                    // $leaveStats = bdGetAllUserLeaveEntitelments($mysqli);
                                    // var_dump($leaveStats);
                                ?>

                                    <div id="navbox">
                                        <button onclick="go2('<?= BASE_URL ?>aec/hrms/ui/leaves/manage-app')">Approval | <?= $co_app ?></button>
                                        <button onclick="go2('<?= BASE_URL ?>aec/hrms/ui/leaves/manage-rev')">Revoke | <?= $co_rev ?></button>
                                        <button onclick="go2('<?= BASE_URL ?>aec/hrms/ui/leaves/manage-log')">Log | <?= $co_log ?></button>
                                    </div>
                                    <script>
                                        function go2(url) {
                                            window.location = url;
                                        }
                                    </script>
                                <?php endif; ?>
                            </td>
                            <td style="width: 60px;">
                                <?php
                                $emoji = 0;
                                $query = "SELECT * FROM rd_leave_records where user_id = '$uid' and emoji > 0";
                                $result = $mysqli->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    if ($row['emoji'] > $emoji) $emoji = $row['emoji'];
                                }
                                if ($emoji > 0):
                                ?>
                                    <a href="<?= BASE_URL ?>aec/hrms/ui/leaves/myleaves">
                                        <img src="<?= BASE_URL ?>da/icons/leave-emoji-<?= $emoji ?>.png" alt="Emoji" height="50px">
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                <td class="homeCell2">
                    <a class="button" href="<?= BASE_URL ?>aec/hrms/ui/leaves/myleaves">Go</a>
                </td>
                <td></td>
            </tr>

        </table>

        <div style="text-align: center; color: white;">
            <div style="padding: 10px;">
                <?php
                $file = W3PATH . "w3filedb/log/quote.log";

                $response = file_get_contents("https://zenquotes.io/api/random");
                $data = json_decode($response, true);

                if (empty($data)) {
                    $content = file_get_contents($file);
                    if ($content !== false) {
                        echo $content;
                    } else {
                        echo "The Worksmart Architecture Studio Organizer";
                    }
                } else {
                    $quote = $data[0]['q'] . " — " . $data[0]['a'];
                    file_put_contents($file, $quote);
                    echo $quote;
                }
                ?>
            </div>
            <a href="https://arkafe.com" target="_blank">
                <img src="<?= BASE_URL ?>da/arkafe5.png" alt="Arkafe | Digital Transformation for Architects" style="width:80px">
            </a>
        </div>

        <?php
        /*
        +-------------------------------------------------------+
        | Statusbar                                             |
        +-------------------------------------------------------+
        */
        // $DisplaySessionInfo = 1;

        if ($DisplaySessionInfo > 0) {
            // echo '<br>Session:';
            // var_dump($_SESSION);
            include('sms/foo/statusbar.cgi');
        }

        ?>

    </div>

    <style>
        .dxTable {
            border-spacing: 6px;
            border: 0px;
        }

        .dxTable tr td {
            border: 0px solid red;
        }

        .dxTable button {
            width: 100%;
            line-height: 22px;
        }

        table {
            border: 0px;
        }
    </style>

    <?php
    include 'foo/dialog/home-timesheet.cgi';
    include 'foo/dialog/home-avatar.cgi';
    include 'foo/dialog/home-profile.cgi';
    include 'foo/dialog/alert.cgi';
    ?>

    <script>
        <?php
        if (isset($_GET["e"])) {

            $m = [
                "file-format-invalid" => ["Filetype Error", "Please upload a jpg or png image"],
                "file-upload-error" => ["File Upload Error", "File could not be uploaded"],
            ];
            echo 'window.onload = (event) => {
                        showAlert("' . $m["file-format-invalid"][0] . '", "' . $m["file-format-invalid"][1] . '")
                    }';
        }
        ?>
    </script>
    <?php
    // phpinfo();
    // var_dump($_SESSION); 
    ?>
</body>

</html>