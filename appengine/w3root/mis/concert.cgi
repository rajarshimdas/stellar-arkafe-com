<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 09-Aug-09				                    |
| Updated On: 17-Jan-12				                    |
| Updated On: 06-Dec-23 Chartjs                         |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
/*
+-----------------------------------------------------+
| Module Authentication                               |
+-----------------------------------------------------+
*/
$moduleId = 3;
if (userModuleAuth($moduleId, $user_id, $loginname, $sessionid, $mysqli) !== TRUE) {
    die('<div align="center" style="font-size:120%;color:Red;">Module Access Denied.</div>');
}

// Financial Year
$year = date("Y");
$today = date("Y-m-d");

if ($today < "$year-03-01"){
    $lastYear = $year - 1;
    $finStartDate = "$lastYear-04-01";
} else {
    $finStartDate = "$year-04-01";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>MIS Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">

    <link href='<?= BASE_URL ?>matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>
    <script type='text/javascript' src='<?= BASE_URL ?>matchbox/api/jquery.min.js'></script>

    <style>
        .tabulation {
            font-size: 1em;
        }

        .dataRowCell1 {
            border: 0px;
            text-align: center;
            width: 35px;
            height: 70px;
            font-weight: bold;
        }

        .dataRowCell2 {
            text-align: right;
            border: 0px;
        }

        tr td.dataRowCell2:nth-child(2) {
            background-color: var(--rd-nav-light);
            color: white;
            text-align: left;
            padding-left: 10px;
        }

        .tabulation tr.dataRow {
            border: 0px solid green;
            background-color: var(--rd-home-box1);
        }

        .tabulation tr td {
            border: 0px;
            border-bottom: 2px solid white;
        }

        .tabulation table tr td {
            border: 0px solid blue;
        }
    </style>
</head>

<body>
    <div class="windowBox2">
        <table class="tabulation" style="width:100%;" cellpadding="0" cellspacing="0">

            <!-- Welcome Line & Settings -->
            <thead class="stickyTop">
                <tr style="background-color: var(--rd-nav-dark);">
                    <td style="width:70px; text-align:center;">
                        <img src="/da/arkafe-a.png" alt="Concert" style="height: 35px;">
                    </td>
                    <td style="color:white;font-size:1em;padding: 0px 15px;">CONCERT | MIS</td>
                    <td class="homeCell1" style="color: white; font-size: 1.1em; font-weight: bold;">
                        <?= $fullname ?>
                    </td>
                    <td class="homeCell3" style="padding-right: 30px; text-align:right">
                        <a class="button" href="../studio/home.cgi">Home</a>&nbsp;<a class="button" href="logout.cgi">Logout</a>
                    </td>
                </tr>
            </thead>

            <!-- Tools -->
            <tr class="dataRow">
                <td class="dataRowCell1">1</td>
                <td class="dataRowCell2">
                    Firmwide Projects
                </td>
                <td class="dataRowCell2" colspan="2">
                    <?php include 'foo/formFirmwideProjects.php'; ?>
                </td>
            </tr>

            <!--
            <tr class="dataRow">
                <td class="dataRowCell1">2</td>
                <td class="dataRowCell2">
                    Firmwide Time Utilization<br>Graph and CSV Export
                </td>
                <td class="dataRowCell2" colspan="2">

                    <?php include 'foo/formTimeUtilization.php'; ?>
                </td>
            </tr>
            -->

            <!-- Project timesheet team member -->
            <tr class="dataRow">
                <td class="dataRowCell1">2</td>
                <td class="dataRowCell2">
                    Project Timesheet<br>By Teammate
                </td>
                <td class="dataRowCell2" colspan="2">
                    <?php include 'foo/formProjectTeammateTs.php'; ?>
                </td>
            </tr>

            <!-- Project timesheet by scope | milestone | team member -->
            <tr class="dataRow">
                <td class="dataRowCell1">3</td>
                <td class="dataRowCell2">
                    Project Timesheet<br>By Scope
                </td>
                <td class="dataRowCell2" colspan="2">
                    <?php include 'foo/formProjectScopeView.php'; ?>
                </td>
            </tr>

            <tr class="dataRow">
                <td class="dataRowCell1">4</td>
                <td class="dataRowCell2">
                    Teammate Timesheets<br>Tabulated by Projects
                </td>
                <td class="dataRowCell2" colspan="2">
                    <?php include 'foo/formTeammateView.php'; ?>
                </td>
            </tr>

            <!--
            <tr class="dataRow">
                <td class="dataRowCell1">5</td>
                <td class="dataRowCell2">
                    Teammate Timesheets<br>Projects Pie Chart
                </td>
                <td class="dataRowCell2" colspan="2">
                    <?php include 'foo/formTeammateViewPie.php'; ?>
                </td>
            </tr>
            -->

            <tr class="dataRow">
                <td class="dataRowCell1">5</td>
                <td class="dataRowCell2">
                    Teammate Timesheets<br>Worked in Milestone (Stagewise)
                </td>
                <td class="dataRowCell2" colspan="2">
                    <?php include 'foo/formTeammateMilestonePie.php'; ?>
                </td>
            </tr>

            <tr class="dataRow">
                <td class="dataRowCell1">6</td>
                <td class="dataRowCell2">
                    Teammate Timesheet<br>Tabulated by Date
                </td>
                <td class="dataRowCell2" colspan="2">
                    <?php include 'foo/formTeammateTSView.php'; ?>
                </td>
            </tr>

            <!--
            <tr class="dataRow">
                <td class="dataRowCell1">8</td>
                <td class="dataRowCell2">
                    Timesheets Filled Status
                </td>
                <td class="dataRowCell2" colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                        <tr>
                            <td width="525px" colspan="2" style="text-align: right;">
                                Timesheets Status
                            </td>
                            <td width="50px" style="text-align: left;">
                                <a class="button" href="<?= BASE_URL ?>reports/timesheet/getStatus">Get</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            -->

            <tr class="dataRow">
                <td class="dataRowCell1">7</td>
                <td class="dataRowCell2">
                    Timesheets Month Summary
                </td>
                <td class="dataRowCell2" colspan="2">
                    <form action="<?= $base_url ?>index.cgi" method="POST">
                        <input type="hidden" name="a" value="reports-timesheet-getStatusMonth">
                        <table width="100%" cellpadding="2" border="0">
                            <tr>
                                <td align="right" style="height:45px">Timesheet summary for month of:</td>
                                <td width="120px">
                                    <input type="month" name="mo" value="<?= date("Y-m") ?>" min="2024-02" max="<?= date("Y-m") ?>">
                                </td>
                                <td width="50px" align="center">
                                    <input type="submit" name="go" value="Get">
                                </td>

                                <td width="50px" align="center">
                                    <input type="submit" name="go" value="Export CSV">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>


            <tr class="dataRow">
                <td class="dataRowCell1">8</td>
                <td class="dataRowCell2">
                    Timesheet Status Widget
                </td>
                <td class="dataRowCell2" colspan="2">
                    <form action="<?= $base_url ?>index.cgi" method="POST">
                        <input type="hidden" name="a" value="reports-timesheet-timesheetWidget">
                        <table width="100%" cellpadding="2" border="0">
                            <tr>
                                <td align="right">
                                    Timesheet Widget for all Users
                                </td>
                                <td width="50px" align="center">
                                    <input type="submit" name="go" value="Get">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>

        </table>
        <div class="footer"><?= $companyname ?></div>
    </div>
</body>

</html>