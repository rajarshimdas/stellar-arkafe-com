<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 02-Feb-12					                |
| Updated On: 07-Mar-25                                 |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
require_once BD . '/Controller/Common.php';

$this_uid = $_GET["uid"];

require $w3etc . '/foo/uid2displayname.php';
$dname = uid2displayname($this_uid, $mysqli);

$moduleName = "Timesheet Approvals";
$returnURL = ["Manage", $base_url . 'studio/tms/timesheets.cgi?a=team'];
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SMS :: Timesheets manage</title>

    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">
    <link type='text/css' rel='stylesheet' href='/matchbox/themes/cool/style.css'>

    <script type='text/javascript' src='<?= BASE_URL ?>matchbox/bad-dragon.js'></script>
    <style>
        .tabulation {
            border-collapse: collapse;
            width: 100%;
        }

        .tabulation tr td {
            border: 1px solid gray;
            text-align: center;
            padding: 4px;
        }

        .tabulation thead {
            font-size: 1.25em;
            text-align: center;
            background-color: #447ca9;
            color: white;
            border: 1px solid #447ca9;
        }

        table.tbl4day {
            border-collapse: collapse;
            width: 100%;
            border: 0px;
            font-size: 0.95em;
        }

        table.tbl4day tr:hover {
            background-color: rgb(203, 228, 248);
        }

        table.tbl4day tr td {
            border-top: 0px;
            padding: 0px 4px;
        }

        img.fa5button:hover {
            opacity: 1;
        }
    </style>

</head>

<body>
    <div class="windowBox2">

        <div>
            <?php include $w3root . '/studio/sms/foo/header.cgi'; ?>
        </div>

        <table class="tabulation" cellpadding="0" cellspacing="0">
            <thead class="headerRow">
                <tr>
                    <td colspan="10" style="height: 35px; color:white;border-right: 1px none gray;">
                        <?= $dname ?>
                    </td>
                    <td style="border-left: 1px none gray;">
                        <a href="<?= BASE_URL ?>studio/tms/timesheets.cgi?a=team" class="button" style="font-family:'Segoe UI';font-weight:normal;">Back</a>
                    </td>
                </tr>
            </thead>
            <tr style="font-weight: bold;">
                <td width="80px">Date</td>
                <td width="50px">Day</td>
                <td width="240px">Project</td>
                <td width="70px">Scope</td>
                <td width="70px">Milestone</td>
                <td>Work Description</td>
                <td width="80px">% Complete</td>
                <td width="70px" style="border-right:0px;">Hours<br>Worked</td>
                <td width="40px" style="text-align:center;border-left:0px;">&nbsp;</td>
                <td width="70px">Manhours</td>
                <td width="90px">Approve</td>
            </tr>
            <tbody>
                <?php

                $query = "select
                            dt,
                            sum(no_of_hours) as h,
                            sum(no_of_min) as m
                        from
                            timesheet
                        where
                            user_id = '$this_uid' and
                            active > 0 and
                            quality < 1
                        group by
                            dt";

                if ($result = $mysqli->query($query)) {

                    while ($row = $result->fetch_assoc()) {
                        $tsDay[$row['dt']] = $row;
                    }
                    $result->close();
                }

                $tsTotalCount = 0;

                $query = "SELECT 
                            * 
                        FROM 
                            `view_timesheets`
                        where
                            `user_id` = '$this_uid' and
                            `approved` < '1' and
                            `pm_review_flag` < '1' and 
                            `project_id` > '15'";
                // die($query);

                if ($result = $mysqli->query($query)) {

                    $tsTotalCount = $result->num_rows;

                    while ($row = $result->fetch_assoc()) {
                        $tsByDate[$row['dtmysql']][] = $row;
                    }
                    $result->close();
                }

                $dayCo = 0;
                $today = mktime(10, 10, 0, date("m"), date("d"), date("Y"));

                # Get Date 
                #
                function getDateAgo($daysAgo, $today)
                {
                    $ts = $today - (86400 * $daysAgo);

                    return [
                        'dateDay' => date("D", $ts),
                        'dateCal' => date("d-M-y", $ts),
                        'dateISO' => date("Y-m-d", $ts),
                    ];
                }

                # Tabulate the day
                #
                function tblDayTS($thisDayISO, $tx)
                {
                    echo '<table class="tbl4day">';
                    foreach ($tx as $t):
                ?>
                        <tr id="tm-row-<?= $t["timesheet_id"] ?>">
                            <td style="width:240px;text-align:left;">
                                <?= ($t['project_id'] < 500) ? $t['projectname'] : $t['jobcode'] . ' - ' . $t['projectname']; ?>
                            </td>
                            <td style="width:70px;"><?= $t['scope'] ?></td>
                            <td style="width:70px;"><?= $t['sname'] ?></td>
                            <td style="text-align:left;"><?= $t['work'] ?></td>
                            <td style="width:80px;"><?= $t['percent'] . '%' ?></td>
                            <td style="width:70px;border-right:0px;">
                                <?= bdAddHourMin($t['no_of_hours'], $t['no_of_min']) ?>
                            </td>
                            <td style="width:40px;border-right:0px;border-left:0px;text-align:right;">
                                <img class="fa5button" src="/da/icons/no.32px.png" title="Reject Timesheet" onclick="javascript:btnReject('<?= $t["timesheet_id"] ?>', '<?= $thisDayISO ?>');">
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    echo '</table>';
                }
                ## Loop
                #
                while ($tsTotalCount > 0):

                    $thisDay        = getDateAgo($dayCo, $today);
                    $thisDayCal     = $thisDay['dateCal'];
                    $thisDayNm      = $thisDay['dateDay'];
                    $thisDayISO     = $thisDay['dateISO'];
                    $dayCo++;

                    if (empty($tsByDate[$thisDayISO])) {
                        continue;
                    } else {
                        $tx = $tsByDate[$thisDayISO];
                        $txCount = count($tx);
                        $tsTotalCount = $tsTotalCount - $txCount;
                    }
                    ?>

                    <tr id="tr-day-<?= $thisDayISO ?>">
                        <td style="vertical-align:top;"><?= $thisDayCal ?></td>
                        <td style="border-right:0px;vertical-align:top;"><?= $thisDayNm ?></td>
                        <td colspan="7" style="padding:0px;border:0px;"><?php tblDayTS($thisDayISO, $tx); ?></td>
                        <td id="day-mh-<?= $thisDayISO ?>" style="vertical-align:top;">
                            <?= bdAddHourMin($tsDay[$thisDayISO]['h'], $tsDay[$thisDayISO]['m']) ?>
                        </td>
                        <td style="vertical-align:top;" onmouseenter="moEnter('<?= $thisDayISO ?>')" onmouseleave="moLeave('<?= $thisDayISO ?>')">
                            <button class="button" onclick="javascript:btnApprove('<?= $thisDayISO ?>')">Approve</button>
                        </td>
                    </tr>
                <?php
                endwhile;
                ?>

            </tbody>
        </table>

    </div>

    <div class="windowBox2 footer">
        <?= $companyname ?>
    </div>
    <?php require_once __DIR__ . '/manage-rm-js.cgi'; ?>
</body>

</html>