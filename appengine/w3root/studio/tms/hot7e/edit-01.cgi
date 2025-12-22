<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 27-May-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
| Timesheet :: Edit	action - step 2                 |
+-------------------------------------------------------+
*/

// Variables
$date = trim($_GET["dt"]);
//echo "UID: $userid<br>Date: $date";

// Validate the date
if ($date === "-- Select Date --") {
    $mc = "Date was not selected. Please select date from the drop down calendar...";
    header("Location: rajarshi.cgi?a=t7xedit&mc=$mc");
    die;
}

// Collect timesheet data for this date
$query = "select
            t2.projectname,
            t1.task_id,
            t1.no_of_hours,
            t1.no_of_min,
            t1.work,
            t1.approved,
            t1.quality,
            t1.id
        from
            timesheet as t1,
            projects as t2
        where
            t1.user_id = $userid and
            t1.project_id = t2.id and
            t1.active = 1 and
            DATE_FORMAT(t1.dt,'%d-%b-%y') = '$date'";

//echo "Q1: $query";

/* Get the database connection */
include 'foo/arnav/dblogin.cgi';
if (!$mysqli) echo "DB: Connection error";


if ($result = mysqli_query($mysqli, $query)) {

    while ($row = mysqli_fetch_row($result)) {

        $timeX[] = array(
                "projectname" => $row[0],
                "task_id" => $row[1],
                "hours" => $row[2],
                "min" => $row[3],
                "work" => $row[4],
                "approved" => $row[5],
                "quality" => $row[6],
                "timesheet_id" => $row[7]
        );

    }

    $result->close();
}

$mysqli->close();

// Tabulate the timesheet data
$count = count($timeX);

/*
// Check if all the timesheet entry have already been approved
$timesheet_approved_flag = 1; 	// assume that they are approved
for ($i = 0;$i < $count;$i++){
	if ($timeX[$i]["approved"] == 0) $timesheet_approved_flag = 0;
}
*/
// Tabulate
if ($count > 0) {
    Tabulate($timeX,$date);
} else {
    echo "No Timesheet entry for this date ($date) were found...";
}
/*
+-------------------------------------------------------+
| Tabulation function				                   	|
+-------------------------------------------------------+
*/
function Tabulate($timeX,$date) {

    $srno = 1;

    // Header row
    echo "&nbsp<br>Timesheet Data for $date<br>";
    ?>
<table width="95%" border="0" style="font-size:80%;" cellpadding="2" cellspacing="0">
    <tr style="background:#FFF6F4;font-weight:bold;" align="center">
        <td width="5%" style="border:1px solid grey;">No.</td>
        <td width="25%" style="border-top:1px solid grey;border-bottom:1px solid grey;border-right:1px solid grey;" align="left">Project</td>
        <td width="10%" style="border-top:1px solid grey;border-bottom:1px solid grey;border-right:1px solid grey;">Time<br>(HH:MM)</td>
        <td width="50%" style="border-top:1px solid grey;border-bottom:1px solid grey;border-right:1px solid grey;" align="left">Task<br>Work</td>
        <td width="10%" style="border-top:1px solid grey;border-bottom:1px solid grey;border-right:1px solid grey;">&nbsp;</td>
    </tr>

        <?php
        // Display the data rows
        for ($i = 0;$i < count($timeX);$i++) {

            /*
		+-------------------------------------------------------+
		| TaskID -> Task					                   	|
		+-------------------------------------------------------+
		task_id to taskname
		<select name="tk" style="width:303px">
	        <option value="0">-- Select --
	        <option value="1">Schematic Design
	        <option value="2">Design Development
	        <option value="3">Construction Documentation
	        <option value="4">Site work
	        <option value="6">Rework - Billable
	        <option value="7">Rework - Non Billable
	        <option value="5">Overheads
	        <option value="8">Others
	    </select>
            */
            if ($timeX[$i]["task_id"] == 1) $task = "Schematic Design";
            if ($timeX[$i]["task_id"] == 2) $task = "Design Development";
            if ($timeX[$i]["task_id"] == 3) $task = "Construction Documentation";
            if ($timeX[$i]["task_id"] == 4) $task = "Site work";
            if ($timeX[$i]["task_id"] == 5) $task = "Rework - Billable";
            if ($timeX[$i]["task_id"] == 6) $task = "Rework - Non Billable";
            if ($timeX[$i]["task_id"] == 7) $task = "Overheads";
            if ($timeX[$i]["task_id"] == 8) $task = "Others";
            ?>
    <tr align="center" valign="top">
        <td style="border-bottom:1px solid grey;border-right:1px solid grey;border-left:1px solid grey"><?php echo $srno;
        $srno = $srno + 1; ?></td>
        <td style="border-bottom:1px solid grey;border-right:1px solid grey;" align="left"><?php echo $timeX[$i]["projectname"]; ?></td>
        <td style="border-bottom:1px solid grey;border-right:1px solid grey;"><?php echo $timeX[$i]["hours"].":".$timeX[$i]["min"]; ?></td>
        <td style="border-bottom:1px solid grey;border-right:1px solid grey;" align="left">
        <?php echo $task; ?>
            <textarea style="width:100%;border:0;text-align:left"><?php echo trim($timeX[$i]["work"]); ?></textarea>
        </td>
        <td style="border-bottom:1px solid grey;border-right:1px solid grey;">
            <form action="rajarshi.cgi" method="GET">
                <input type="hidden" name="a" value="t7xedit-02">
                <input type="hidden" name="dt" value="<?php echo $date; ?>">
                <input type="hidden" name="tsid" value="<?php echo $timeX[$i]["timesheet_id"]; ?>">
                        <?php
                        if ($timeX[$i]["approved"] < 1) {
                            // echo '<input type="submit" name="go" value="edit" style="width:60px">';
                            echo '<input type="submit" name="go" value="delete" style="width:60px">';
                        }
        ?>
            </form>
        </td>
    </tr>

            <?php
        }
    ?>

</table>
    <?php
}

// echo "Total Time (HH:MM):";

?>

