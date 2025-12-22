<style>
    .tsDayRow {
        text-align: center;
    }

    .tmButton {
        height: 22px;
        opacity: 0.7;
    }

    .tmButton:hover {
        opacity: 0.4;
    }
</style>
<?php
/* original code is tabulate.cgi. This is custom for Abhikalpan
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


/*
+-----------------------------------------------------+
| Read daysOff.txt                             	    |
+-----------------------------------------------------+
*/
require_once $w3root . '/studio/tms/hot7e/getHolidayList.php';
require_once $w3root . '/studio/tms/hot7e/getHolidayBackground.php';
require_once $w3etc . '/foo/timeAdd.php';
require_once $w3etc . '/foo/dateMysql2UnixTS.php';

//echo '<br>Branch Name: '.$branch_name.' Path: '.$pathInclude.'<br>';

$daysOffX = getHolidayList(/* $branch_id */0, $mysqli);
//$daysOffX_count = count($daysOffX);

if (!isset($lockdtTS)) $lockdtTS = dateMysql2UnixTS($lockdt);

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
<table class="tsTable" style="font-size:80%;width:100%;max-width:var(--rd-max-width);margin:auto;" cellpadding="2" cellspacing="0">
    <thead style="top: 0; position: sticky;z-index:1000;">
        <tr style="background:#FFF6F4;font-weight:bold;">

            <td class="cellHeaderLeft" width="8%">Date</td>
            <td class="cellHeader" width="5%">Day</td>
            <td class="cellHeader" width="18%" style="text-align:left;">Project</td>
            <td class="cellHeader" width="5%">Scope</td>
            <td class="cellHeader" width="7%">Milestone</td>
            <td class="cellHeader" style="text-align:left;">Work Description</td>
            <td class="cellHeader" width="7%">Percent<br>Completed</td>
            <td class="cellHeader" width="9%">Hours<br>Worked</td>
            <td class="cellHeader" width="7%">Approval<br>Status</td>
            <td class="cellHeader" width="7%">Total<br>Hours</td>

        </tr>
    </thead>

    <?php
    /*
    +-------------------------------------------------------+
    | Data rows                                             |
    +-------------------------------------------------------+
    */
    require_once $w3root . '/studio/tms/hot7e/getTimesheetDataForThisTeammate.cgi';

    // Loop backwards one day at time starting from today
    $today = date("Y-m-d");

    $todayDay = date("d");
    $todayMonth = date("m");
    $todayYear = date("Y");

    //for ($co = $noOfDaysToDate; $co < $noOfDaysFromDate; $co++) {
    for ($co = 0; $co < $display_no_of_days; $co++) {
        // This date 
        $thisDateTS = mktime(1, 0, 0, $todayMonth, $todayDay - $co, $todayYear); 

        // Date Formats
        $this_date = date("Y-m-d", $thisDateTS);
        $this_dayname = date("D", $thisDateTS);
        $this_date_human = date("d-M-y", $thisDateTS);

        // Holiday Formatting
        $formatX = getHoldidayBackground($this_dayname, $this_date, $daysOffX, /* $satmode */ 'satmode not used');

        // Holiday
        if ($formatX["isHoliday"] > 0) {
            $background = "RGB(200,200,200)";
        } else {
            $background = "white";
        }
        if ($formatX["holiday"] != "X") {
            $this_date_human = $this_date_human . "<br>" . $formatX["holiday"];
        }

        // Show or Hide the Add, Edit, Update buttons
        if ($showAddEditDeleteButtons > 0) {
            $x = $thisDateTS - $lockdtTS;
            if ($x < 0) {
                $showAddEditDeleteButtons = 0;
            }
        }
    
    
        /* Get Today's entries */
        $dateX = getTimesheetDataForThisTeammate($this_userid, $this_date, "singleDay", $mysqli);
        $count = 0;
        if (isset($dateX)) {
            $count = count($dateX);
            $rowspan = $count;
        } else {
            $rowspan = 1;
        }

        /* Day Row */
        ?>
        <tr valign="top" style="height:45px;background:<?php echo $background; ?>">
            <td class="cellRowLeft" rowspan="<?= $rowspan ?>"><?php echo $this_date_human; ?></td>
            <td class="cellRow" rowspan="<?= $rowspan ?>"><?php echo $this_dayname; ?></td>

            <?php
            $i = 0;
            do {

                if ($i > 0) echo '<tr style="height:45px;background:' . $background . '">';

                // Reset
                $this_project   = "<!-- None -->";
                $this_mh        = "<!-- None -->"; // Manhours HH:SS
                $this_work      = "<!-- None -->";
                $this_approval  = "<!-- None -->";
                $tx             = "<!-- None -->"; // Day Total Hours
                $this_milestone = "<!-- None -->";
                $this_work_from = "<!-- None -->";
                $this_percent   = "<!-- None -->";
                $this_scope     = "<!-- None -->";

                // If there are data rows
                if ($count > 0) {

                    /* Colorcode mindmap
                    +-----------+-----------+-------------------+-------------------+-------+
                    | approved  | quality   | pm_review_flag    | Type              | Color |
                    +-----------+-----------+-------------------+-------------------+-------+
                    | 1         | 0         | X (1)             | Approved          | Green |
                    +-----------+-----------+-------------------+-------------------+-------+
                    | 0         | 1         | 1                 | Remarked by PM    | Red   |
                    +-----------+-----------+-------------------+-------------------+-------+
                    | 1         | 1         | X (1)             | Rejected by PM    | Red   |
                    +-----------+-----------+-------------------+-------------------+-------+
                    | 0         | 1         | 0                 | Re-submitted      | Blue  |
                    +-----------+-----------+-------------------+-------------------+-------+
                    */
                    $x = "black";                                                               // Provisional
                    if ($dateX[$i]["approved"] > 0 && $dateX[$i]["quality"] < 1) $x = 'green';  // Approved
                    if ($dateX[$i]["approved"] > 0 && $dateX[$i]["quality"] > 0) $x = 'red';    // Rejected
                    if ($dateX[$i]["approved"] < 1 && $dateX[$i]["quality"] > 0 && $dateX[$i]["pm_review_flag"] < 1) $x = 'blue'; // Re-submitted for Approval
                    if ($dateX[$i]["approved"] < 1 && $dateX[$i]["quality"] > 0 && $dateX[$i]["pm_review_flag"] > 0) $x = 'red';    // Remarked



                    if ($dateX[$i]["project_id"] > 500) {
                        $this_project = "<div style='color:$x;font-weight:bold;'>" . $dateX[$i]["jobcode"] . ' - ' . $dateX[$i]["projectname"] . "</div>";
                    } else {
                        $this_project = "<div style='color:$x;font-weight:bold;'>" . $dateX[$i]["projectname"] . "</div>";
                    }

                    $this_mh =  $dateX[$i]["no_of_hours"] . ":" . $dateX[$i]["no_of_min"];
                    if ($dateX[$i]["project_id"] > 1 && $dateX[$i]["project_id"] < 10) $this_mh = '<!-- Leave -->';
               
                    //$this_work = "<textarea style='background-color:$background;height:60px;width:98%;border:0px;' readonly>" . $dateX[$i]["work"] . "</textarea>";
                    $this_work = $dateX[$i]["work"];

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
                <td class="cellRow" valign="top" id="<?= "tp_" . $dateX[$i]["timesheet_id"] ?>"><?= $this_percent ?></td>
                <td class="cellRow" valign="top" id="<?= "th_" . $dateX[$i]["timesheet_id"] ?>"><?= $this_mh ?></td>
                <td class="cellRow" valign="top" id="<?= "tc_" . $dateX[$i]["timesheet_id"] ?>">
                    <?php
                    if ($count > 0) {
                        showEditButtons(
                            $dateX,
                            $i,
                            $this_userid,
                            $userid,
                            $showAddEditDeleteButtons,
                            $display_no_of_days,
                            $no_of_days_flag,
                            $base_url
                        );
                    }
                    ?>
                </td>

            <?php
                if ($i < 1) {

                    if ($count > 0) {
                        $tx = tsDayTotalHours($dateX, $count);
                    }

                    echo '<td class="cellRow" rowspan="' . $rowspan . '" valign="top">' . $tx . '</td></tr>';
                }

                //echo '</tr>';
                $i++;
            } while ($i < $count);
            ?>

        <?php
    }
        ?>

</table>

<?php

function tsDayTotalHours(
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
    return timeadd($total_hours, $total_min);
}
