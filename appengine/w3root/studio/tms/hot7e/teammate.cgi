<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 15-Jun-2009       			|
| Updated On: 13-Apr-2011				|
+-------------------------------------------------------+
| Timesheet :: Teammate summary                       	|
+-------------------------------------------------------+
*/

// Don't display data if user is not a TL or DM
if ($roleid < 40) {

    include('foo/time_addition.php');
    include('foo/arnav/angels.cgi');

}

/*
+-------------------------------------------------------+
| Get Week Dates					|
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

echo "&nbsp;<br>Teammate's timesheet summary [WeekNo: $week_no]<br>";


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
| Get List of all Projects				|
+-------------------------------------------------------+
*/
$query = "select
			id,
			projectname
			
		from 
			projects
		
		order by
			projectname";

// echo "<br>Q1: $query";
if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {

        $ProjX[] = array(

                "project_id" 	=> $row[0],
                "projectname" 	=> $row[1]

        );

    }

    $result->close();
}


/*
+-------------------------------------------------------+
| Get Teammates list for this Project			|
+-------------------------------------------------------+
*/
include('hot7e/getTeammateListForThisProject.cgi');
$teamX = getTeammateListForThisProject($projectid,$mysqli);

/* Test::List the teammates
for ($i=0;$i<count($teamX);$i++){
	echo "<br>Teammate: ".$teamX[$i]["fullname"];
}
*/


/*
+-------------------------------------------------------+
| Get Timesheet data (loop for each Teammate)		|
+-------------------------------------------------------+
| Display Table for each teammate			|
+-------------------------------------------------------+
*/
include('hot7e/getTimesheetDataForThisTeammate.cgi');

for ($i=0;$i<count($teamX);$i++) {

    echo "<span style='font-weight:bold'>".$teamX[$i]["fullname"]."</span><br>";

    $timeX = getTimesheetDataForThisTeammate($teamX[$i]["user_id"],$x['monday'],$x['sunday'],$mysqli);
    $co = 1;

    /*
    +-------------------------------------------------------+
    | Tabulate :: Header					|
    +-------------------------------------------------------+
    */ ?>
<table border="0" cellspacing="0" cellpadding="2px" width="95%" style="font-size:80%;">
    <tr align="center" style="background:#FFF6F4;font-weight:bold;">
        <td width="5%" style="border:1px solid grey;">No</td>
        <td width="20%" style="border-right:1px solid grey;border-top:1px solid grey;border-bottom:1px solid grey;">Project</td>
        <td width="65%" style="border-right:1px solid grey;border-top:1px solid grey;border-bottom:1px solid grey;">Work</td>
        <td width="10%" style="border-right:1px solid grey;border-top:1px solid grey;border-bottom:1px solid grey;">Total<br>[ HH:MM ]</td>
    </tr>


        <?php

        // Loop for each project
        for ($n=0;$n<count($ProjX);$n++) {

            //echo $ProjX[$n]["projectname"]."<br>";
            unset($content);
            $count = 0;
            $total_hr = 0;
            $total_mn = 0;

            // Loop through the time data to find rows for this project
            for ($e=0;$e<count($timeX);$e++) {



                if ($ProjX[$n]["project_id"] === $timeX[$e]["project_id"]) {

                    $count++;
                    $total_hr = $total_hr + $timeX[$e]["no_of_hours"];
                    $total_mn = $total_mn + $timeX[$e]["no_of_min"];

                    // echo $ProjX[$n]["projectname"]. "T: ".$timeX[$e]["no_of_hours"].":".$timeX[$e]["no_of_min"]."<br>";
                    $content = $content
                            ."<span style='font-weight:bold'>"
                            .$timeX[$e]["date"]
                            ."</span> "
                            .$timeX[$i]["task"]
                            ." [ "
                            .$timeX[$e]["no_of_hours"].":".$timeX[$e]["no_of_min"]
                            ." ]<br>"
                            .$timeX[$e]["work"]
                            ."<br>";
                }

            }

            if ($count > 0) {
                ?>
    <tr valign="top">
        <td style="border-left:1px solid grey;border-right:1px solid grey;border-bottom:1px solid grey;""><?php echo $co++; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey;"><?php echo $ProjX[$n]["projectname"]; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey;"><?php echo $content; ?></td>
        <td style="border-right:1px solid grey;border-bottom:1px solid grey;" align="center"><?php echo timeadd($total_hr,$total_mn); ?></td>
    </tr>
                <?php
            }


        }
        ?></table><br><?php

}

?>
