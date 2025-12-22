<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 28-May-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
| Timesheet :: Sumary		                        |
+-------------------------------------------------------+
*/
echo "&nbsp;<br>Timedata for $projectname";

// Don't display data if user is not a TL or DM
if ($roleid < 40) {

    include('foo/time_addition.php');
    include('foo/arnav/angels.cgi');

}

/*
+-------------------------------------------------------+
| Get Teammates list for this Project			|
+-------------------------------------------------------+
*/
include('hot7e/getTeammateListForThisProject.cgi');
$teamX = getTeammateListForThisProject($projectid,$mysqli);

/*
timesheet as t1
+-------------+-----------------------+------+-----+-------------------+----------------+
| Field       | Type                  | Null | Key | Default           | Extra          |
+-------------+-----------------------+------+-----+-------------------+----------------+
| id          | bigint(20) unsigned   | NO   | PRI | NULL              | auto_increment |
| dt          | date                  | NO   | MUL | NULL              |                |
| user_id     | int(10) unsigned      | NO   |     | NULL              |                |
| project_id  | mediumint(8) unsigned | NO   |     | NULL              |                |
| task_id     | tinyint(3) unsigned   | NO   |     | NULL              |                |
| no_of_hours | tinyint(4)            | NO   |     | NULL              |                |
| no_of_min   | tinyint(4)            | NO   |     | NULL              |                |
| work        | text                  | NO   |     | NULL              |                |
| approved    | tinyint(1)            | YES  |     | 1                 |                |
| quality     | tinyint(4)            | NO   |     | NULL              |                |
| tmstamp     | timestamp             | NO   |     | CURRENT_TIMESTAMP |                |
| active      | tinyint(1)            | NO   |     | 1                 |                |
+-------------+-----------------------+------+-----+-------------------+----------------+

users as t2
+--------------+----------------------+------+-----+---------+----------------+
| Field        | Type                 | Null | Key | Default | Extra          |
+--------------+----------------------+------+-----+---------+----------------+
| id           | int(10) unsigned     | NO   | PRI | NULL    | auto_increment |
| domain_id    | smallint(5) unsigned | NO   | MUL | NULL    |                |
| loginname    | varchar(50)          | NO   |     | NULL    |                |
| passwd       | varchar(50)          | NO   |     |         |                |
| fullname     | varchar(50)          | NO   |     | NULL    |                |
| emailid      | varchar(150)         | NO   |     | NULL    |                |
| internaluser | tinyint(1)           | YES  |     | NULL    |                |
| remark       | varchar(250)         | NO   |     | NULL    |                |
| active       | tinyint(1)           | YES  |     | NULL    |                |
+--------------+----------------------+------+-----+---------+----------------+
*/

$query = "select
			t2.fullname,
			t2.loginname,
			t1.no_of_hours,
			t1.no_of_min,
			t1.approved,
			t1.user_id

		from
			timesheet as t1,
			users as t2

		where
			t1.user_id = t2.id and
			t1.active = 1 and
			t1.project_id = $projectid";

// echo "Q2: $query<br>";

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {

        $timesheetX[] = array(

                "fullname" 	=> $row[0],
                "loginname" => $row[1],
                "hours" 	=> $row[2],
                "min" 		=> $row[3],
                "approved" 	=> $row[4],
                "user_id" 	=> $row[5]

        );

    }

    $result->close();
}

$mysqli->close();
/*
+-------------------------------------------------------+
| Tabulate - Header			                |
+-------------------------------------------------------+
*/
?>
<table width="95%" border="0" style="font-size:80%;" cellpadding="2" cellspacing="0">
    <tr style="background:#FFF6F4;font-weight:bold;" align="center">
        <td width="5%" style="border:1px solid grey;">No.</td>
        <td width="35%" style="border-top:1px solid grey;border-bottom:1px solid grey;border-right:1px solid grey;" align="left">Teammate</td>
        <td width="15%" style="border-top:1px solid grey;border-bottom:1px solid grey;border-right:1px solid grey;">Approved<br>(HH:MM)</td>
        <td width="15%" style="border-top:1px solid grey;border-bottom:1px solid grey;border-right:1px solid grey;">Current<br>(HH:MM)</td>
        <td width="20%" style="border-top:1px solid grey;border-bottom:1px solid grey;border-right:1px solid grey;">&nbsp;</td>

    </tr>

    <?php
    /*
+-------------------------------------------------------+
| Tabulate - Rows			                |
+-------------------------------------------------------+
    */
    $srno 						= 1;
    $grand_total_hours 			= 0;
    $grand_total_min 			= 0;
    $grand_total_current_hours 	= 0;
    $grand_total_current_min 	= 0;

// Teammate wise data rows
    for ($i = 0;$i < count($teamX);$i++) {

        $this_loginname = $teamX[$i]["loginname"];
        $this_user_id	= $teamX[$i]["user_id"];
        //echo '<br>'.$teamX[$i]["loginname"];

        // Reset variables
        $total_hours 			= 0;
        $total_min 				= 0;
        $total_hours_current 	= 0;
        $total_min_current 		= 0;

        if ($teamX[$i]["user_active"] < 1) $bgcolor = "style='color:blue'"; else $bgcolor = "style='color:black'";

        echo '<tr align="center" '.$bgcolor.'>
        <td style="border-bottom:1px solid grey;border-right:1px solid grey;border-left:1px solid grey">'.$srno++.'</td>
        <td style="border-bottom:1px solid grey;border-right:1px solid grey;" align="left">&nbsp;'.$teamX[$i]["fullname"].'</td>';

        // Dig out the information for this teammate
        for ($co = 0; $co < count($timesheetX); $co++) {

            //echo '<br>'.$this_loginname.':'.$timesheetX[$co]["loginname"];
            if ($this_user_id === $timesheetX[$co]["user_id"]) {

                if ($timesheetX[$co]["approved"] > 0) {

                    $total_hours 			= $total_hours 	+ $timesheetX[$co]["hours"];
                    $total_min 				= $total_min 	+ $timesheetX[$co]["min"];

                } else {

                    $total_hours_current 	= $total_hours_current 	+ $timesheetX[$co]["hours"];
                    $total_min_current 		= $total_min_current 	+ $timesheetX[$co]["min"];

                }

            }
        }

        echo 	'<td style="border-bottom:1px solid grey;border-right:1px solid grey;">'.timeadd($total_hours,$total_min).'</td>
        	<td style="border-bottom:1px solid grey;border-right:1px solid grey;">'.timeadd($total_hours_current,$total_min_current).'</td>
        	<form action="rajarshi.cgi" method="GET">
        	<td  style="border-bottom:1px solid grey;border-right:1px solid grey;">
				<input type="hidden" name="a" value="t7xsummary-01">
				<input type="hidden" name="uid" value="'.$teamX[$i]["user_id"].'">
				<input type="hidden" name="fn" value="'.$teamX[$i]["fullname"].'">
				<input type="submit" name="go" value="View" style="width:80px">
				<input type="submit" name="go" value="Approve" style="width:80px">
	        </td>
	        </form>
    	</tr>';

        // Update grand total approved
        $grand_total_hours = $grand_total_hours + $total_hours;
        $grand_total_min = $grand_total_min + $total_min;

        // Update grand total current
        $grand_total_current_hours = $grand_total_current_hours + $total_hours_current;
        $grand_total_current_min = $grand_total_current_min + $total_min_current;

    }
    /*
+-------------------------------------------------------+
| Tabulate - Bottom Row		                        |
+-------------------------------------------------------+
    */
    echo '<tr align="center" style="font-weight:bold">
        <td colspan="2">Total</td>
        <td>'.timeadd($grand_total_hours,$grand_total_min).'</td>
        <td>'.timeadd($grand_total_current_hours,$grand_total_current_min).'</td>
        <td>&nbsp;</td>
    </tr>';

    ?>

</table>
