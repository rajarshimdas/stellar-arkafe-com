<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 09-Jun-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
| Timesheet :: Sumary		                        |
+-------------------------------------------------------+
*/

// Don't display data if user is not a TL or DM
if ($roleid < 40) {
    include('foo/time_addition.php');
    include('foo/arnav/angels.cgi');
}
/*
+-------------------------------------------------------+
| Get Last Week Dates					|
+-------------------------------------------------------+
*/
$year 		= date("Y");
$day_no 	= date("N");

// Setup week for display
if ($_GET["WeekNo"] && $_GET["WeekNo"] < 54 && $_GET["WeekNo"] > 0) {
    // User specified week no
    $week_no = $_GET["WeekNo"];
    $table_header = "Week($week_no) Days";
} else {
    // Last week = Current Week no - 1
    $week_no = date("W") - 1;
    $table_header = "Last Week Days";
}

//echo "<br>Today:: Year: $year Weekno :$week_no DayNo: $day_no";

include('foo/getDaysOfWeek.cgi');
$x = getDaysOfWeek($year,$week_no);

/* Days of Last week 
echo "<h3>Days of Last Week</h3>";
echo "<br>Monday: ".$x['monday'];
echo "<br>Tuesday: ".$x['tuesday'];
echo "<br>Wednesday: ".$x['wednesday'];
echo "<br>Thursday: ".$x['thursday'];
echo "<br>Friday: ".$x['friday'];
echo "<br>Saturday: ".$x['saturday'];
echo "<br>Sunday: ".$x['sunday'];
*/

/*
+-------------------------------------------------------+
| Tabulate Function					|
+-------------------------------------------------------+
| Function: tabulateTimeSheet				|
| Parameters	WeekNo					|
|				ProjName		|
|				DaysOfWeek		|
|				mysqli			|
+-------------------------------------------------------+
*/
function tabulateTimeSheet ($ProjName, $ProjID, $WeekNo, $DaysOfWeek, $tblHeader, $mysqli) {

    /* Test variables
	echo "<br>PN: $ProjName WN: $WeekNo Monday: ".$DaysOfWeek['monday'];
    */
    $week_hr = 0;	// Total no of hours for this week
    $week_mn = 0;   // Total no of minutes for this week

    ?>

<table width="95%" border="0" cellspacing="0" cellpadding="2" style="font-size:80%">
        <?php
        /*------------------------------------------+
	|	Header Row                          |
	+------------------------------------------*/
        ?>
    <tr align="center" style="background: rgb(255, 246, 244);font-weight:bold;">
        <td rowspan="3" width="5%" style="border:1px solid grey;">No</td>
        <td rowspan="3" width="23%" style="border-right:1px solid grey;border-top:1px solid grey;border-bottom:1px solid grey">Teammate</td>
        <td colspan="7" width="63%" style="border-right:1px solid grey;border-top:1px solid grey;border-bottom:1px solid grey"><?php echo $tblHeader; ?></td>
        <td rowspan="3" width="9%" style="border-right:1px solid grey;border-top:1px solid grey;border-bottom:1px solid grey">Total</td>
    </tr>
    <tr align="center" style="background: rgb(255, 246, 244);font-weight:bold;">
        <td width="9%" style="border-right:1px solid grey;border-bottom:1px solid grey">Mo</td>
        <td width="9%" style="border-right:1px solid grey;border-bottom:1px solid grey">Tu</td>
        <td width="9%" style="border-right:1px solid grey;border-bottom:1px solid grey">We</td>
        <td width="9%" style="border-right:1px solid grey;border-bottom:1px solid grey">Th</td>
        <td width="9%" style="border-right:1px solid grey;border-bottom:1px solid grey">Fr</td>
        <td width="9%" style="border-right:1px solid grey;border-bottom:1px solid grey">Sa</td>
        <td width="9%" style="border-right:1px solid grey;border-bottom:1px solid grey">Su</td>
    </tr>
    <tr align="center" style="background: rgb(255, 246, 244);font-weight:bold;">
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php $dt = explode("-",$DaysOfWeek['monday']);
                echo $dt[0]; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php $dt = explode("-",$DaysOfWeek['tuesday']);
                echo $dt[0]; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php $dt = explode("-",$DaysOfWeek['wednesday']);
                echo $dt[0]; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php $dt = explode("-",$DaysOfWeek['thursday']);
                echo $dt[0]; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php $dt = explode("-",$DaysOfWeek['friday']);
                echo $dt[0]; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php $dt = explode("-",$DaysOfWeek['saturday']);
                echo $dt[0]; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php $dt = explode("-",$DaysOfWeek['sunday']);
                echo $dt[0]; ?></td>
    </tr>


    <!-- Data Rows -->
        <?php
        /*------------------------------------------+
	| Get list of teammates for this project    |
	+------------------------------------------*/
        $query = "select
                        t2.id as user_id,
                        t2.fullname,
                        t1.active

                    from
                        roleinproject as t1,
                        users as t2

                    where
                        t1.loginname = t2.loginname and
                        t1.project_id = $ProjID

                    order by
                        t1.roles_id";

        //echo "<br>Q1: $query";


        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                $tmX[] = array("user_id"=> $row[0],"fullname" => $row[1],"active" => $row[2]);

            }

            $result->close();
        }

        //echo "No of Teammates: ".count($tmX);


        /*------------------------------------------+
	| Get TimeSheet data for the teammates      |
	+------------------------------------------*/
        $monday 	= $DaysOfWeek['monday'];
        $tuesday 	= $DaysOfWeek['tuesday'];
        $wednesday 	= $DaysOfWeek['wednesday'];
        $thursday 	= $DaysOfWeek['thursday'];
        $friday 	= $DaysOfWeek['friday'];
        $saturday 	= $DaysOfWeek['saturday'];
        $sunday 	= $DaysOfWeek['sunday'];
        //$monday = "STR_TO_DATE('$monday','%d-%b-%y')";

        //echo "<br>Monday: $monday<br>Sunday: $sunday";

        $query = "select
                    t1.user_id,
                    t2.fullname,
                    DATE_FORMAT(t1.dt,'%d-%b-%Y') as date,
                    t1.no_of_hours,
                    t1.no_of_min

                from
                    timesheet as t1,
                    users as t2
                where
                    t1.project_id = $ProjID and
                    t1.user_id = t2.id and
                    t1.active = 1 and
                    t1.dt >= STR_TO_DATE('$monday','%d-%b-%Y') and
                    t1.dt <= STR_TO_DATE('$sunday','%d-%b-%Y')";


        //echo "<br>Q2: $query";

        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                $dX[] = array(
                        "user_id"		=> $row[0],
                        "fullname" 		=> $row[1],
                        "date" 			=> $row[2],
                        "no_of_hours" 	=> $row[3],
                        "no_of_min" 	=> $row[4]
                );

            }

            $result->close();
        }

        /* Test $dX array
		echo "No: ".count($dX);
		for ($i=0;$i<count($dX);$i++){
			echo "<br>".$dX[$i]["fullname"];
		}
        */

        /*------------------------------------------+
	| Tabulate the data rows                    |
	+------------------------------------------*/
        $no = 1;

        // Loop for each Teammate
        for ($i = 0; $i < count($tmX); $i++) {

            $tm_total_hr = 0;
            $tm_total_mn = 0;

            // Teammate's user_id
            $this_user_id = $tmX[$i]["user_id"];
            //echo "<br>this_user_id: $this_user_id";

            // Deleted user colour blue
            $colour = ";color:black;";
            if ($tmX[$i]["active"] < 1) $colour = "color:blue;";

            /*------------------------------------------+
            |	Monday                                  |
            +------------------------------------------*/
            $monday_hr 		= 0;
            $monday_min 	= 0;

            for ($co = 0; $co < count($dX); $co++) {

                if ($this_user_id === $dX[$co]["user_id"] && $monday == $dX[$co]["date"]) {

                    $monday_hr 	= $monday_hr 	+ $dX[$co]["no_of_hours"];
                    $monday_min = $monday_min 	+ $dX[$co]["no_of_min"];

                    $tm_total_hr = $tm_total_hr + $dX[$co]["no_of_hours"];
                    $tm_total_mn = $tm_total_mn + $dX[$co]["no_of_min"];

                }

            }

            $monday_time = timeadd ($monday_hr,$monday_min);


            /*------------------------------------------+
            |	Tuesday					|
            +------------------------------------------*/
            $tuesday_hr 	= 0;
            $tuesday_min 	= 0;

            for ($co = 0; $co < count($dX); $co++) {

                if ($this_user_id === $dX[$co]["user_id"] && $tuesday == $dX[$co]["date"]) {

                    $tuesday_hr  = $tuesday_hr 		+ $dX[$co]["no_of_hours"];
                    $tuesday_min = $tuesday_min 	+ $dX[$co]["no_of_min"];

                    $tm_total_hr = $tm_total_hr + $dX[$co]["no_of_hours"];
                    $tm_total_mn = $tm_total_mn + $dX[$co]["no_of_min"];

                }

            }

            $tuesday_time = timeadd ($tuesday_hr,$tuesday_min);

            /*------------------------------------------+
			|	Wednesday							 	|
			+------------------------------------------*/
            $wednesday_hr 	= 0;
            $wednesday_min 	= 0;

            for ($co = 0; $co < count($dX); $co++) {

                if ($this_user_id === $dX[$co]["user_id"] && $wednesday == $dX[$co]["date"]) {

                    $wednesday_hr 	= $wednesday_hr 	+ $dX[$co]["no_of_hours"];
                    $wednesday_min 	= $wednesday_min 	+ $dX[$co]["no_of_min"];

                    $tm_total_hr = $tm_total_hr + $dX[$co]["no_of_hours"];
                    $tm_total_mn = $tm_total_mn + $dX[$co]["no_of_min"];

                }

            }

            $wednesday_time = timeadd ($wednesday_hr,$wednesday_min);

            /*------------------------------------------+
            |	Thursday			 	|
            +------------------------------------------*/
            $thursday_hr 	= 0;
            $thursday_min 	= 0;

            for ($co = 0; $co < count($dX); $co++) {

                if ($this_user_id === $dX[$co]["user_id"] && $thursday == $dX[$co]["date"]) {

                    $thursday_hr 	= $thursday_hr 	+ $dX[$co]["no_of_hours"];
                    $thursday_min 	= $thursday_min + $dX[$co]["no_of_min"];

                    $tm_total_hr = $tm_total_hr + $dX[$co]["no_of_hours"];
                    $tm_total_mn = $tm_total_mn + $dX[$co]["no_of_min"];

                }

            }

            $thursday_time = timeadd ($thursday_hr,$thursday_min);

            /*------------------------------------------+
            |	Friday					|
            +------------------------------------------*/
            $friday_hr 		= 0;
            $friday_min 	= 0;

            for ($co = 0; $co < count($dX); $co++) {

                if ($this_user_id === $dX[$co]["user_id"] && $friday == $dX[$co]["date"]) {

                    $friday_hr 	= $friday_hr 	+ $dX[$co]["no_of_hours"];
                    $friday_min = $friday_min 	+ $dX[$co]["no_of_min"];

                    $tm_total_hr = $tm_total_hr + $dX[$co]["no_of_hours"];
                    $tm_total_mn = $tm_total_mn + $dX[$co]["no_of_min"];

                }

            }

            $friday_time = timeadd ($friday_hr,$friday_min);

            /*------------------------------------------+
            |	Saturday				|
            +------------------------------------------*/
            $saturday_hr 	= 0;
            $saturday_min 	= 0;

            for ($co = 0; $co < count($dX); $co++) {

                if ($this_user_id === $dX[$co]["user_id"] && $saturday == $dX[$co]["date"]) {

                    $saturday_hr 	= $saturday_hr 		+ $dX[$co]["no_of_hours"];
                    $saturday_min 	= $saturday_min 	+ $dX[$co]["no_of_min"];

                    $tm_total_hr = $tm_total_hr + $dX[$co]["no_of_hours"];
                    $tm_total_mn = $tm_total_mn + $dX[$co]["no_of_min"];

                }

            }

            $saturday_time = timeadd ($saturday_hr, $saturday_min);

            /*------------------------------------------+
            |	Sunday				 	|
            +------------------------------------------*/
            $sunday_hr 	= 0;
            $sunday_min = 0;

            for ($co = 0; $co < count($dX); $co++) {

                if ($this_user_id === $dX[$co]["user_id"] && $sunday == $dX[$co]["date"]) {

                    $sunday_hr 	= $sunday_hr 	+ $dX[$co]["no_of_hours"];
                    $sunday_min = $sunday_min 	+ $dX[$co]["no_of_min"];

                    $tm_total_hr = $tm_total_hr + $dX[$co]["no_of_hours"];
                    $tm_total_mn = $tm_total_mn + $dX[$co]["no_of_min"];

                }

            }

            $sunday_time = timeadd ($sunday_hr, $sunday_min);

            $tm_total = timeadd($tm_total_hr,$tm_total_mn);

            // Add to the week summary
            $week_hr = $week_hr + $tm_total_hr;
            $week_mn = $week_mn + $tm_total_mn;

            /*------------------------------------------+
            |	Display Rows				|
            +------------------------------------------*/
            ?>
    <tr align="center">
        <td style="border-left:1px solid grey;border-right:1px solid grey;border-bottom:1px solid grey" "height="35px;"><?php echo $no;
                    $no++; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey;<?php echo $colour; ?>" align="left">&nbsp;<?php echo $tmX[$i]['fullname']; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php echo $monday_time; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php echo $tuesday_time; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php echo $wednesday_time; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php echo $thursday_time; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php echo $friday_time; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php echo $saturday_time; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php echo $sunday_time; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey"><?php echo $tm_total; ?></td>
    </tr>
            <?php
        }

        $week_total = timeadd($week_hr,$week_mn);
        ?>
    <tr align="center">
        <td colspan="9">&nbsp;</td>
        <td style="font-weight:bold;"><?php echo $week_total; ?></td>
    </tr>

</table>
    <?php

}

/*
+-------------------------------------------------------+
| Run Tabulate						|
+-------------------------------------------------------+
*/
//echo "&nbsp;<br>Project Summary for $projectname [ WeekNo: $week_no ]";
echo "&nbsp;<br>Project Summary [ WeekNo: $week_no ]";
tabulateTimeSheet($projectname,$projectid,$week_no,$x,$table_header,$mysqli);

?>