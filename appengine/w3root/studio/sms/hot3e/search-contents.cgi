<?php /* Search Contents */

$to 	= $_GET['to'];
$item 	= trim($_GET['item']);
$desc 	= trim($_GET['desc']);

if ($to === "-- Select --" && !$item && !$desc){
	header("location:project.cgi?a=t3xsearch&selectoption=Contents");
	die;
}

include('hot3e/SearchTM.php');
include('foo/arnav/dblogin.cgi');

if ($to !== "-- Select --") 
	$param = "project_id = $projectid and contact = '$to'"; 
	else 
	$param = "project_id = $projectid";


$sql = "select id, transno, contact, dt from transmittals where $param order by id desc";
//echo "sql: $sql;";

if ($result = $mysqli->query($sql)) {    						
    while ($row = $result->fetch_row()) {
    	/* subset from the transmittals matching "to" criteria */        
        $tmX[]=array("tmid" => $row[0], "tmno" => $row[1], "to" => $row[2], "date" => $row[3]);
    }    
    $result->close();
} else echo "Error: $mysqli->error";

$count = sizeof($tmX); $co = 1;
for ($i = 0; $i < $count; $i++) {
	 
	 	$tmid 	= $tmX[$i]["tmid"];
	 	$tmno	= $tmX[$i]["tmno"];
		$to 	= $tmX[$i]["to"];
		$date 	= $tmX[$i]["date"];
		
		//echo "<br>TmID: $tmid Transno: $tmno To: $to Date: $date";
		
		if ($item) 
			$param2 = "transmittal_id = $tmid and item like '%$item%'"; else 
			$param2 = "transmittal_id = $tmid";
			
		if ($desc) $param2 = "$param2 and description like '%$desc%'";
		
		/* If a matching row is found display transmittal */
		$sql = "select srno from translist where $param2";
		//echo "sql: $sql";
		
		if ($result = $mysqli->query($sql)) { 			   						
    		$row_cnt = $result->num_rows;    		
    		
    		/* Check if a matching row is found */    		
    		if ($row_cnt > 0)  {
    			
    			/* Display Transmittal */   			   			
    			if($item)TabulateTM($mysqli,$tmid,$co,$item);else TabulateTM($mysqli,$tmid,$co);$co++;
    			 
    		}
    		   
    		$result->close();
		} else echo "Error: $mysqli->error";	

}

$mysqli->close();

?>
<br>