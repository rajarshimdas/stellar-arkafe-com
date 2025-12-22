<?php
/*
  +-----------------------------------------------------+
  | Rajarshi Das                                        |
  +-----------------------------------------------------+
  | Tabulate the Timesheet                              |
  +-----------------------------------------------------+
  | Date: 10-Feb-12                                     |
  | a.    DA's modifications                            |
  | b.    Timesheet Approval Requirements               |
  +-----------------------------------------------------+
  | Date: 26-Apr-11                                     |
  | a.    Modifications to use same code for teammate   |
  |       as well as Tabulation when called from the    |
  |       Manager's query tool.                         |
  | b.    Use cronjob's holiday list extraction         |
  |       function                                      |
  | c.    Use cronjob's holiday background change       |
  |       function.                                     |
  | Folder paths - required				                |
  |	$hot7e 						                        |
  |	$cronpath 					                        |
  |	$path 						                        |
  +-----------------------------------------------------+
  | Date: 09-May-2013                                   |
  | Added 3 working day add, edit, delete locks         |
  +-----------------------------------------------------+
  | Date: 13-Apr-11                                     |
  | Overhaul Non-Working days handling                  |
  | Sunday    -   Grey background                       |
  | Saturday  -   Grey background depending on user's   |
  |               branch_id and satmode for that branch |
  +-----------------------------------------------------+
  | Date: 2-Mar-10					|
  | Read days-off 2nd|4th|5th Saturdays and Holidays    |
  | from daysOff.txt					|
  +-----------------------------------------------------+
  | Date: 8-Mar-10					|
  | Display IN OUT data from access card		|
  +-----------------------------------------------------+
  | Date: 14-Apr-10					|
  | Hide IN/OUT data from access card in user's timesheet|
  | The administrator can still see the IN/OUT time	|
  +-----------------------------------------------------+
  | Date: 18-Jun-2010                                   |
  | Sub Task added to the form. Display the subtask in  |
  | the tabulation also.                                |
  +-----------------------------------------------------+
 */
$srno = 1;
$showAddEditDeleteButtons = 1;

/*
  +-----------------------------------------------------+
  | Hide IN/OUT access card data for users		|
  +-----------------------------------------------------+
 */
if ($HideIOdata === "Y") {
    $rowspan = 1;
} else {
    $rowspan = 2;
}

/*
  +-----------------------------------------------------+
  | Read daysOff.txt                             	|
  +-----------------------------------------------------+
 */
include_once 'hot7e/getHolidayList.php';
include_once 'hot7e/getHolidayBackground.php';
include_once 'foo/timeAdd.php';
include_once 'foo/dateMysql2UnixTS.php';

//echo '<br>Branch Name: '.$branch_name.' Path: '.$pathInclude.'<br>';

$daysOffX = getHolidayList($branch_name, $pathInclude);
$daysOffX_count = count($daysOffX);

/*
  +---------------------------------------------------+
  | Read comboWorkedFrom.txt                          |
  +---------------------------------------------------+
 */
// Format worked_from

if (!$comboWorkedFrom)
    $comboWorkedFrom = 'hot7e/comboWorkedFrom.txt';
// echo "comboWorkedFrom: ".$comboWorkedFrom;

$file = fopen($comboWorkedFrom, "r");
if (!$file)
    die("Fatal :: Could not read Holiday List file.");

$maxlines = 0; /* maxlines to avoid infinite loop */

while (!feof($file) && $maxlines < 10) {

    $maxlines++;
    $buffer = fgets($file, 4096);
    //echo $maxlines.". ".$buffer."<br>";
    $tempX = explode(":", $buffer);
    $wfX[] = array("id" => $tempX[0], "worked_from" => $tempX[1]);
}

fclose($file);
$wfX_count = count($wfX);
/*
  +-----------------------------------------------------+
  | Header				                |
  +-----------------------------------------------------+
  | Has been moved to the calling function              |
  +-----------------------------------------------------+
  echo "&nbsp;<br>Timesheet (all projects) for the last $display_no_of_days days";
 */
?>

<table width="100%" border="0" style="font-size:80%;" cellpadding="2" cellspacing="0">
    <tr style="background:#FFF6F4;font-weight:bold;" align="center">

        <td class="cellHeaderLeft" width="8%" rowspan="<?php echo $rowspan; ?>">Date</td>
        <td class="cellHeader" width="8%" rowspan="<?php echo $rowspan; ?>">Day</td>
        <td class="cellHeader" width="48%" rowspan="<?php echo $rowspan; ?>" align="left">Work</td>
        <td class="cellHeader" width="8%" rowspan="<?php echo $rowspan; ?>">Total<br>(HH:MM)</td>
        <?php
        if ($HideIOdata !== "Y") {
            echo '<td class="cellHeader" colspan="3" style="border-bottom:0px;">Time Register</td>';
        }
        ?>
    </tr>

    <?php
    if ($HideIOdata !== "Y") {
        echo '<tr align="center" style="background:#FFF6F4;font-weight:bold">
        <td class="cellHeader" width="8%">In</td>
        <td class="cellHeader" width="8%">Out</td>
        <td class="cellHeader" width="8%">Hours</td>
    </tr>';
    }
    /*
      +-------------------------------------------------------+
      | Data rows                                             |
      +-------------------------------------------------------+
     */
    include_once 'hot7e/getTimesheetDataForThisTeammate.cgi';

    // Loop backwards one day at time starting from today
    $today = date("Y-m-d");

    $todayDay = date("d");
    $todayMonth = date("m");
    $todayYear = date("Y");

    for ($co = $noOfDaysToDate; $co < $noOfDaysFromDate; $co++) {

        // This date        
        $this_date = date("Y-m-d", mktime(0, 0, 0, $todayMonth, $todayDay - $co, $todayYear));

        // Date Formats
        $thisDateTS = dateMysql2UnixTS($this_date);
        $this_dayname = date("D", $thisDateTS);
        $this_date_human = date("d-M-y", $thisDateTS);

        // Holiday Formatting
        $formatX = getHoldidayBackground($this_dayname, $this_date, $daysOffX, $satmode);

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
        <!-- Day Row -->
        <tr align="center" valign="top" style="background:<?php echo $background; ?>" bgcolor="<?php echo $background; ?>">
            <td class="cellRowLeft"><?php echo $this_date_human; ?></td>
            <td class="cellRow"><?php echo $this_dayname; ?></td>
            <td class="cellRow" align="left" valign="top">
                <!-- Work Table -->
                <?php
                /*
                  +-----------------------------------------------------+
                  | Work Table    		                      	        |
                  +-----------------------------------------------------+
                 */
                /* Get Today's entries */
                $dateX = getTimesheetDataForThisTeammate($this_userid, $this_date, "singleDay", $mysqli);
                $count = count($dateX);

                if ($count < 1) {
                    echo '&nbsp;';
                } else {

                    for ($i = 0; $i < $count; $i++) {

                        // Green color coding for the approved entries 
                        $x = "black";
                        if ($dateX[$i]["approved"] > 0 && $dateX[$i]["quality"] < 1) $x = 'green';
                        if ($dateX[$i]["approved"] < 1 && $dateX[$i]["quality"] > 0) $x = 'blue';
                        if ($dateX[$i]["approved"] > 0 && $dateX[$i]["quality"] > 0) $x = 'red';

                        /* Format worked from */
                        for ($n = 0; $n < $wfX_count; $n++) {
                            if ($dateX[$i]["worked_from"] == $wfX[$n]["id"])
                                $worked_from = $wfX[$n]["worked_from"];
                        }
                ?>

                        <table height="96px" width="100%" cellpadding="0" cellspacing="0" style="font-size:100%;border:0;">
                            <tr valign="top">
                                <td height="22px"><?php echo "<span style='color:$x;font-weight:bold;'>" . $dateX[$i]["jobcode"].' - '.$dateX[$i]["projectname"] . "&nbsp;</span><span style='font-weight:normal'>$worked_from</span><br>"; ?> </td>
                                <td width="35px" align="right">
                                    <?php echo $dateX[$i]["no_of_hours"] . ":" . $dateX[$i]["no_of_min"] . "<br>"; ?>
                                </td>
                                <td width="55px" align="right">
                                    <?php
                                    /*
                                 * This ensures that other users (PM) have read access only to the users timesheet data
                                 * The second check dissables editing for approved timesheets
                                 */
                                    if ($this_userid == $userid && $dateX[$i]["approved"] < 1 && $showAddEditDeleteButtons > 0) {
                                    ?>
                                        <a href="rajarshi.cgi?a=timesheet-edit&tsid=<?php
                                                                                    echo $dateX[$i]["timesheet_id"];
                                                                                    if ($display_no_of_days && $no_of_days_flag > 0)
                                                                                        echo "&no=$display_no_of_days";
                                                                                    ?>"><img src='moo/images/edit.png' border="0" title="Edit Timesheet"></a>
                                        <a href="rajarshi.cgi?a=timesheet-del&tsid=<?php
                                                                                    echo $dateX[$i]["timesheet_id"];
                                                                                    if ($display_no_of_days && $no_of_days_flag > 0)
                                                                                        echo "&no=$display_no_of_days";
                                                                                    ?>"><img src='moo/images/delete.png' border="0" title="Delete Timesheet"></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>

                            <tr valign="center">
                                <td height="18px" colspan='3'>
                                    <!-- (approved: <?=$dateX[$i]["approved"] ?> & quality:<?= $dateX[$i]["quality"] ?>)<br> -->
                                    <?php
                                    // echo $dateX[$i]["stage"] . ' | ' . $dateX[$i]["task"];
                                    echo $dateX[$i]["stage"]; // Abhikalpan
                                    /* echo "<textarea  style='height:100%;width:100%;border:0px;$background;font-weight:bold' readonly>".$dateX[$i]["subtask"]."</textarea>"; */
                                    ?>
                                </td>
                            </tr>

                            <tr valign="top">
                                <td height="60px" colspan='3'>
                                    <?php echo "<textarea  style='height:100%;width:100%;border:0px;background:$background' readonly>" . $dateX[$i]["work"] . "</textarea>"; ?>
                                </td>
                            </tr>
                        </table>
                <?php
                    }
                }
                ?>
            </td>

            <td class="cellRow">&nbsp;
                <?php
                // Reset Variables
                $total_hours = 0;
                $total_min = 0;

                // Calculate Total Manhours for the day
                for ($i = 0; $i < $count; $i++) {

                    // Remove the entries that are rejected by PM
                    if ($dateX[$i]["quality"] < 1) {
                        $total_hours = $total_hours + $dateX[$i]["no_of_hours"];
                        $total_min = $total_min + $dateX[$i]["no_of_min"];
                    }
                }

                // Display Manhours for the day
                echo timeadd($total_hours, $total_min);
                ?>&nbsp;
            </td>
        <?php
        // Hide IO data
        if ($HideIOdata !== "Y") {

            $timeIn = '&nbsp;';
            $timeOut = '&nbsp;';
            $manhour = '&nbsp;';

            // Get IO data
            $ioX = getIOdata($user_id, $this_date, $mysqli);

            if ($ioX["ioFlag"] > 0) {
                if ($ioX["intimepunch"] > 0) {
                    $timeIn = $ioX["intime"];
                }
                if ($ioX["outtimepunch"] > 0) {
                    $timeOut = $ioX["outtime"];
                }
                $manhour = $ioX["manhours"];
            }

            echo '<td class="cellRow">' . $timeIn . '</td>
                <td class="cellRow">' . $timeOut . '</td>
                <td class="cellRow">' . $manhour . '</td>';
        }

        echo '<tr>';
    }
        ?>

</table>
<br>

<?php
/*
  +--------------+------------------+------+-----+---------+-------+
  | Field        | Type             | Null | Key | Default | Extra |
  +--------------+------------------+------+-----+---------+-------+
  | dt           | date             | NO   |     | NULL    |       |
  | user_id      | int(10) unsigned | NO   |     | NULL    |       | <<< CONCERT's uid
  | intimepunch  | tinyint(1)       | NO   |     | NULL    |       |
  | intime       | time             | NO   |     | NULL    |       |
  | outtimepunch | tinyint(1)       | NO   |     | NULL    |       |
  | outtime      | time             | NO   |     | NULL    |       |
  | manhours     | varchar(10)      | NO   |     | NULL    |       |
  +--------------+------------------+------+-----+---------+-------+
 */

function getIOdata($uid, $date, $mysqli)
{

    // Variable Initialization
    $ioFlag = 0;
    $intimepunch = 0;
    $outtimepunch = 0;
    $intime = '&nbsp;';
    $outtime = '&nbsp;';
    $manhours = '&nbsp;';

    $query = "select
                intimepunch, 
                DATE_FORMAT(intime, '%H:%i'),
                outtimepunch, 
                DATE_FORMAT(outtime, '%H:%i'),
                manhours
            from
                iostat
            where
                user_id = $uid and
                dt = '$date'";

    // echo "Q: ".$query;

    if ($result = $mysqli->query($query)) {

        if ($row = $result->fetch_row()) {

            $ioFlag = 1;
            $intimepunch = $row[0];
            $intime = $row[1];
            $outtimepunch = $row[2];
            $outtime = $row[3];
            $manhours = $row[4];
        }

        $result->close();
    }

    $rX = array(
        "ioFlag" => $ioFlag,
        "dt" => $date,
        "intimepunch" => $intimepunch,
        "intime" => $intime,
        "outtimepunch" => $outtimepunch,
        "outtime" => $outtime,
        "manhours" => $manhours,
        "q" => $query
    );

    return $rX;
}
?>