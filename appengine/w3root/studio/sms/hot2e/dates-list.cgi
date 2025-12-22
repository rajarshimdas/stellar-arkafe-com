<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 29-Jan-08									|
| Updated On: 03-Mar-08									|
+-------------------------------------------------------+
| Date - Tabulate in a list form					 	|
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| Function: Tabulate									|
+-------------------------------------------------------+
*/
function Tabulate ($projectid,$blockNo,$blockName,$disciplinecode,$mysqli){
	
	if ($disciplinecode !== 'all') $x = " and disciplinecode = '$disciplinecode'";
	
	$query = "select id 
	
			from dwglist where 
				project_id = $projectid and 
				dwgidentity = '$blockNo' and				 
				active = 1 $x";
	
	if ($result = $mysqli->query($query)){
		    
		$row_cnt = $result->num_rows;
		
		if ($row_cnt > 0){
			
			echo "<br><span style='font-weight:bold;font-size:90%;'>$blockNo - $blockName</span><br>";
			$dx = new DWGid($projectid,$roleid);
			$dx->DWGListTableHeader(1,1,1,1,1,1,1,0,0,0,850);
			$no = 1;
			
		    while ($row = $result->fetch_row()) {
		    	
		        $DWGid = $row[0];
		        $a = $dx->GetDWGDetails($DWGid,$mysqli);
		        $dx->DWGListTableRow($no,$a);
		        $no++;
		        
		    }		
		    
		    echo "</table><br>";
		    $result->close();
		}
		
	} else {
		
		echo "MySQL Error";
		
	}	
		
/*
+-------------------------------------------------------+
| close function */}/* Tabulate 						|
+-------------------------------------------------------+
*/



if ($dx === "All Disciplines") $dx = "all";


// Get list of blocks
$query = "select blockno, blockname from blocks where project_id = $projectid and active = 1 order by blockno";

if ($result = $mysqli->query($query)) {
    
    while ($row = $result->fetch_row()) {        
        
    	// Blocks array
        $blockX[] = array("blockno" => $row[0], "blockname" => $row[1]);        
        
    }
    
    $result->close();
}

$co_blocks = count($blockX);
//echo "<br>No of blocks: $co_blocks";

// No blocks defined yet
if ($co_blocks < 1){
	die("Empty drawing list...");	
}


/* Display Masterplan details first */
foreach ($blockX as $k => $v) {
	  
    
    // Check MP block if exists and tabulate it...
    if ($blockX[$k]["blockno"] === "MP") {  	
    	
    	Tabulate($projectid,"MP",$blockX[$k]["blockname"],$dx,$mysqli); 	
    	    	
    }
}


/* Display all other blocks */
foreach ($blockX as $k => $v) {
        
    // Tabulate other blocks...
    if ($blockX[$k]["blockno"] !== "MP") {
    	
    	Tabulate($projectid,$blockX[$k]["blockno"],$blockX[$k]["blockname"],$dx,$mysqli);
    	    	
    }
}

?>
<br>