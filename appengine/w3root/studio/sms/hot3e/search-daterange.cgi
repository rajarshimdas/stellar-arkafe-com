<?php /* Search DateRange */

$date1 = trim($_GET['date1']);
$date2 = trim($_GET['date2']);

if (!$date1 || !$date2){
	header("location:project.cgi?a=t3xsearch&selectoption=Date+Range");
	die;
}

include('hot3e/SearchTM.php');
include('foo/arnav/dblogin.cgi');

$sql = "select id from transmittals where
		project_id = $projectid and
		dt > '$date1' and
		dt < '$date2' 
		order by id desc";
//echo "sql: $sql;";

if ($result = $mysqli->query($sql)) {
	
	$co = 1;
    while ($row = $result->fetch_row()) {    	
    	$tmid = $row[0];
    	
    	/* Display Transmittal */
        TabulateTM($mysqli,$tmid,$co); $co++;
        
    }    
    $result->close();
} else echo "Error: $mysqli->error";

$mysqli->close();

?>
<br>