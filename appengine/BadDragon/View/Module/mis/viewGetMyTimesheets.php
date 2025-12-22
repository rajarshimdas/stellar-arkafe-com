<style>
    .tsDayRow {
        text-align: center;
    }

    .tmButton {
        height: 22px;
    }
</style>
<?php
/* 
+-----------------------------------------------------+
| Rajarshi Das                                        |
+-----------------------------------------------------+
| Tabulate the Timesheet                              |
+-----------------------------------------------------+
| Date: 19-Jan-24 Abhikalpan format                   |
+-----------------------------------------------------+
*/
$srno = 1;
$showAddEditDeleteButtons = 1;
$noOfDaysFromDate = 10;

$grand_total_h = 0;
$grand_total_m = 0;


// Hack for fetching teammate timesheet | 15-Feb-2024
if (isset($_POST['dxUid'])) {
    $this_userid = $_POST['dxUid'];
    require $w3etc . '/foo/uid2displayname.php';
    echo '<div style="text-align:center;line-height:50px;color:white;background-color:cadetblue;">' . uid2displayname($this_userid, $mysqli) . '</div>';
}

/*
+-----------------------------------------------------+
| Read daysOff.txt                             	    |
+-----------------------------------------------------+
*/
include_once 'tms/hot7e/getHolidayList.php';
include_once 'tms/hot7e/getHolidayBackground.php';
include_once 'foo/timeAdd.php';
include_once 'foo/dateMysql2UnixTS.php';

//echo '<br>Branch Name: '.$branch_name.' Path: '.$pathInclude.'<br>';

$daysOffX = getHolidayList(0, $mysqli);
//$daysOffX_count = count($daysOffX);

/*
+---------------------------------------------------+
| Read comboWorkedFrom.txt                          |
+---------------------------------------------------+
*/
$wfX = [
    ["id" => 10, "worked_from" => "Office"],
    ["id" => 20, "worked_from" => "Remote Location"],
];
$wfX_count = count($wfX);

?>
<style>
    .tsTable tr td {
        text-align: center;
    }
</style>
<table class="tsTable" width="100%" style="font-size:80%;" cellpadding="2" cellspacing="0">
    <tr style="background:#FFF6F4;font-weight:bold;">

        <td class="cellHeaderLeft" width="8%" rowspan="<?php echo $rowspan; ?>">Date</td>
        <td class="cellHeader" width="5%">Day</td>
        <td class="cellHeader" width="20%" style="text-align:left;">Project</td>
        <td class="cellHeader" width="5%">Scope</td>
        <td class="cellHeader" width="7%">Milestone</td>
        <td class="cellHeader" style="text-align:left;">Work Description</td>
        <td class="cellHeader" width="7%">Percent<br>Completed</td>
        <td class="cellHeader" width="7%">Hours<br>Worked</td>
        <!-- <td class="cellHeader" width="7%">Approval<br>Status</td> -->
        <td class="cellHeader" width="7%">Total<br>Hours</td>

    </tr>

    <?php
    /*
    +-------------------------------------------------------+
    | Data rows                                             |
    +-------------------------------------------------------+
    */
    include_once 'tms/hot7e/getTimesheetDataForThisTeammate.cgi';

    // Loop backwards one day at time starting from today
    /*
    $today = date("Y-m-d");

    $todayDay = date("d");
    $todayMonth = date("m");
    $todayYear = date("Y");
    */

    $today = $to_date;

    $todayDay = date("d", $to_date_ts);
    $todayMonth = date("m", $to_date_ts);
    $todayYear = date("Y", $to_date_ts);

    for ($co = 0; $co <= $no_of_days; $co++) {
        // This date        
        $this_date = date("Y-m-d", mktime(0, 0, 0, $todayMonth, $todayDay - $co, $todayYear));

        // Date Formats
        $thisDateTS = dateMysql2UnixTS($this_date);
        $this_dayname = date("D", $thisDateTS);
        $this_date_human = date("d-M-y", $thisDateTS);

        // Holiday Formatting
        $formatX = getHoldidayBackground($this_dayname, $this_date, $daysOffX, /* $satmode */ 'NA');

        // Holiday
        if ($formatX["isHoliday"] > 0) {
            $background = "RGB(200,200,200)";
        } else {
            $background = "white";
        }
        if ($formatX["holiday"] !== "X") {
            $this_date_human = $this_date_human . "<br>" . $formatX["holiday"];
        }

        // Show or Hide the Add, Edit, Update buttons
        if ($showAddEditDeleteButtons > 0) {
            $x = $thisDateTS - $lockdtTS;
            if ($x < 0) {
                $showAddEditDeleteButtons = 0;
            }
        }
    ?>

        <!-- Work Day -->
        <?php

        /* Get Today's entries */
        $dateX = getTimesheetDataForThisTeammate($this_userid, $this_date, "singleDay", $mysqli);
        $count = 0;
        if (isset($dateX)) {
            $count = count($dateX);
            $rowspan = $count;
        } else {
            $rowspan = 1;
        }
        ?>
        <!-- Day Row -->
        <tr valign="top" style="background:<?php echo $background; ?>">
            <td class="cellRowLeft" rowspan="<?= $rowspan ?>"><?php echo $this_date_human; ?></td>
            <td class="cellRow" rowspan="<?= $rowspan ?>"><?php echo $this_dayname; ?></td>

            <?php
            $i = 0;
            do {

                if ($i > 0) echo "<tr style='background:$background'>";

                // Formatting
                $this_project   = "<!-- None -->";
                $this_mh        = "<!-- None -->"; // Manhours HH:SS
                $this_work      = "<!-- None -->";
                $this_approval  = "<!-- None -->";
                $tx             = ["<!-- None -->", 0, 0]; // Day Total Hours
                $this_milestone = "<!-- None -->";
                $this_work_from = "<!-- None -->";
                $this_percent   = "<!-- None -->";
                $this_scope     = "<!-- None -->";

                // If there are data rows
                if ($count > 0) {

                    // Green color coding for the approved entries 
                    $x = "black";
                    if ($dateX[$i]["approved"] > 0 && $dateX[$i]["quality"] < 1) $x = 'green';
                    if ($dateX[$i]["approved"] < 1 && $dateX[$i]["quality"] > 0) $x = 'blue';
                    if ($dateX[$i]["approved"] > 0 && $dateX[$i]["quality"] > 0) $x = 'red';

                    if ($dateX[$i]["project_id"] > 100) {
                        $this_project = "<div style='color:$x;'>" . $dateX[$i]["jobcode"] . ' - ' . $dateX[$i]["projectname"] . "</div>";
                    } else {
                        $this_project = "<div style='color:$x;'>" . $dateX[$i]["projectname"] . "</div>";
                    }

                    $this_mh =  $dateX[$i]["no_of_hours"] . ":" . $dateX[$i]["no_of_min"];
                    if ($dateX[$i]["project_id"] == 2 || $dateX[$i]["project_id"] == 3) $this_mh = '<!-- Leave -->';

                    //$this_work = "<textarea style='background:$background;height:60px;width:98%;border:0px;' readonly>" . $dateX[$i]["work"] . "</textarea>";
                    $this_work = "<div style='min-height: 50px;'>" . $dateX[$i]["work"] . "</div>";
                    $this_milestone = $dateX[$i]['sname'];
                    if ($dateX[$i]["worked_from"] == '20') $this_work_from = 'Remote Location';

                    if ($dateX[$i]["percent"] > 0) $this_percent = $dateX[$i]["percent"] . '%';

                    $this_scope = ($dateX[$i]["scope_id"] > 1) ? $dateX[$i]['scope'] : "<!-- NA -->";
                }
            ?>

                <td class="cellRow" valign="top" style="text-align:left;">
                    <?= $this_project ?>
                    <div style="font-size: 0.9em;">
                        <div><?= $this_work_from ?></div>
                    </div>
                </td>
                <td class="cellRow" valign="top"><?= $this_scope ?></td>
                <td class="cellRow" valign="top"><?= $this_milestone ?></td>
                <td class="cellRow" valign="top" style="text-align:left;"><?= $this_work ?></td>
                <td class="cellRow" valign="top"><?= $this_percent ?></td>
                <td class="cellRow" valign="top"><?= $this_mh ?></td>
                <!--
                <td class="cellRow" valign="top">
                    <?php
                    /*
                    if ($count > 0) {
                        showEditButtons(
                            $dateX,
                            $i,
                            $this_userid,
                            $userid,
                            $showAddEditDeleteButtons,
                            $display_no_of_days,
                            $no_of_days_flag
                        );
                    }
                    */
                    ?>
                </td>
                -->

            <?php
                if ($i < 1) {

                    if ($count > 0) {
                        $tx = dayTotalHours($dateX, $count);
                        $grand_total_h = $grand_total_h + $tx[1];
                        $grand_total_m = $grand_total_m + $tx[2];
                    }

                    echo '<td class="cellRow" rowspan="' . $rowspan . '" valign="top">' . $tx[0] . '</td></tr>';
                }

                //echo '</tr>';
                $i++;
            } while ($i < $count);
            ?>

        <?php
    }
        ?>
        <tr style="line-height: 30px;">
            <td colspan="8" style="text-align: right;">Total</td>
            <td><?= timeadd($grand_total_h, $grand_total_m) ?></td>
        </tr>
</table>

<?php
/* */
function showEditButtons(
    $dateX,
    $i,
    $this_userid,
    $userid,
    $showAddEditDeleteButtons,
    $display_no_of_days,
    $no_of_days_flag
) {

    if ($this_userid == $userid && $dateX[$i]["approved"] < 1 && $showAddEditDeleteButtons > 0 && $dateX[$i]["project_id"] > 10) {
?>
        <a href="rajarshi.cgi?a=timesheet-edit&tsid=<?php
                                                    echo $dateX[$i]["timesheet_id"];
                                                    if ($display_no_of_days && $no_of_days_flag > 0)
                                                        echo "&no=$display_no_of_days";
                                                    ?>"><img class="tmButton" src='moo/images/edit.png' title="Edit Timesheet"></a>
        <a href="rajarshi.cgi?a=timesheet-del&tsid=<?php
                                                    echo $dateX[$i]["timesheet_id"];
                                                    if ($display_no_of_days && $no_of_days_flag > 0)
                                                        echo "&no=$display_no_of_days";
                                                    ?>"><img class="tmButton" src='moo/images/delete.png' title="Delete Timesheet"></a>
<?php
    }
}


function dayTotalHours(
    $dateX,
    $count
) {

    // Reset Variables
    $total_hours = 0;
    $total_min = 0;

    // Calculate Total Manhours for the day
    for ($i = 0; $i < $count; $i++) {

        // leave
        if ($dateX[$i]["project_id"] < 10) {
            // Nothing to do
        } else {
            // Remove the entries that are rejected by PM
            if ($dateX[$i]["quality"] < 1) {
                $total_hours = $total_hours + $dateX[$i]["no_of_hours"];
                $total_min = $total_min + $dateX[$i]["no_of_min"];
            }
        }
    }

    // Display Manhours for the day
    return [
        timeadd($total_hours, $total_min),
        $total_hours,
        $total_min,
    ];
}
