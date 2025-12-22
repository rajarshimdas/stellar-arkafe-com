<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 03-Jun-2009       						|
| Updated On: 											|
+-------------------------------------------------------+
| Project Allocation Plan	                        	|
+-------------------------------------------------------+
*/ 

// Only Project Manager and above can see this.
if ($roleid < 40) {
	include('foo/arnav/angels.cgi');	
} else {
	die('You are not authorized to view this page...');
}

?>


<table style="text-align: left; width: 100%;background:#E8E9FF;" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td align="center">Under active development.</td>
	</tr>
</table>


<?php /*
+-------------------------------------------------------+
| Get List of project(s) for this Project Manager      	|
+-------------------------------------------------------+
*/
$query = "select
			t1.id, 
			t1.projectname
		from 
			projects as t1,
			roleinproject as t2
		where
			t1.id = t2.project_id and
			t2.loginname = '$loginname' and
			t2.roles_id = 30 and
			t2.active = 1 and
			t1.active = 1
			";

//echo "Q1: $query<br>";

if ($result = $mysqli->query($query)) {
    
    while ($row = $result->fetch_row()) {
    	
        $ProjectX[] = array ("this_project_id" => $row[0],"this_project_name" => $row[1]);
    }
    
    $result->close();
}


/*
+-------------------------------------------------------+
| Get List of teammate(s) for these Project(s)     		|
+-------------------------------------------------------+
*/
for ($i = 0; $i < count($ProjectX); $i++){
	
	$this_project_id 	= $ProjectX[$i]["this_project_id"];
	$this_project_name 	= $ProjectX[$i]["this_project_name"];

	$query = "select 
				t3.fullname,
				t2.loginname,
				t2.roles_id,
				t2.active
			from 
				roleinproject as t2,
				users as t3
			where
				t2.loginname = t3.loginname and
				t2.project_id = $this_project_id";
	
	//echo "Q2: $query<br>";
	
	if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_row()) {
    	
        $teammateX[] = array (
        				"this_project_id" 		=> $this_project_id,
        				"this_project_name" 	=> $this_project_name,
        				"this_fullname" 		=> $row[0],
        				"this_loginname" 		=> $row[1],
        				"this_roles_id" 		=> $row[2],
        				"this_active" 			=> $row[3]
        				);
        				
    }

    /* free result set */
    $result->close();
	}	

}

/*
+-------------------------------------------------------+
| Week Dates											|
+-------------------------------------------------------+

$year 		= date("Y");
$week_no 	= date("W");
$day_no 	= date("N");
echo "<br>Today:: Year: $year Weekno :$week_no DayNo: $day_no";

// Display for next week
$week_no = $week_no + 1;

// Days remaining days of week + 1
$day_remaining_till_monday = (7 - $day_no) + 1;

// Monday
$query = "select 
			DATE_ADD(CURRENT_DATE,INTERVAL $day_remaining_till_monday DAY) as monday,
			DATE_ADD(CURRENT_DATE,INTERVAL $day_remaining_till_monday+1 DAY) as tuesday,
			DATE_ADD(CURRENT_DATE,INTERVAL $day_remaining_till_monday+2 DAY) as wednesday,
			DATE_ADD(CURRENT_DATE,INTERVAL $day_remaining_till_monday+3 DAY) as thursday,
			DATE_ADD(CURRENT_DATE,INTERVAL $day_remaining_till_monday+4 DAY) as friday,
			DATE_ADD(CURRENT_DATE,INTERVAL $day_remaining_till_monday+5 DAY) as saturday,
			DATE_ADD(CURRENT_DATE,INTERVAL $day_remaining_till_monday+6 DAY) as sunday
			";
//echo "<br>Q3: $query<br>";

if ($result = $mysqli->query($query)) {	
	
	while ($row = $result->fetch_row()) {
		
	    $monday 	= $row[0];
	    $tuesday 	= $row[1];
	    $wednesday 	= $row[2];
	    $thursday 	= $row[3];
	    $friday 	= $row[4];
	    $saturday 	= $row[5];
	    $sunday 	= $row[6];
	    				
	}	
	
	$result->close();
}	
*/

echo "<h3>Next Week:</h3>
		Monday: $monday
		<br>Tuesday: ".$tuesday.
		"<br>Wednesday: ".$wednesday.
		"<br>Thursday: ".$thursday.
		"<br>Friday: ".$friday.
		"<br>Saturday: ".$saturday.
		"<br>Sunday: ".$sunday
		;



/*
+-------------------------------------------------------+
| Get time information for each of teammateX array		|
+-------------------------------------------------------+
for ($i = 0; $i < count($teammateX); $i++){
	
		

}
*/
$mysqli->close();

?>