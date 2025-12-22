<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: Not Available (2007)						|
| Updated On: 22-Feb-08									|
+-------------------------------------------------------+
| Functions: Tabulates the Printable drawing lists		|
+-------------------------------------------------------+
| a.	TabulateHeader - common							|
| b.	TabulateBW - Block wise tabulation				|
| c.	TabulateDC - Disciplinecode wise tabulation		|
+-------------------------------------------------------+
*/

function TableHeader($schematic,$r0release,$r0target,$lastissued,$remark){ ?>

	<table width="<?php echo $width."px"; ?>" style="text-align: center; font-size:12px;" border="1" cellpadding="1" cellspacing="0">  			
		<tr height=30px; style="background:#FFF6F4;font-weight:bold;">
			<?php if ($ShowNo > 0) echo "<td style='width:30px;text-align:center;' rowspan='2'>No</td>"; ?>      
				<td style="width:70px;text-align:center;" rowspan="2">Drawing Number</td>
				<td style="width:50px;text-align:center;" rowspan="2">Current<br>RevNo</td>
				<td style="width:170px; text-align:left;" rowspan="2">&nbsp;Title</td>
				<?php if ($ShowSchematic > 0) echo "<td style='width:140px;text-align:center;' colspan='2'>Schematic</td>"; ?>
				<?php if ($ShowGFC > 0) echo "<td style='width:140px;text-align:center;' colspan='2'>GFC(R0)</td>"; ?> 
				<?php if ($ShowLastIssued > 0) echo "<td style='width:110px;text-align:center;' colspan='2'>Last Issued</td>";?>      
				<?php if ($ShowActionBy > 0) echo "<td style='width:80px;text-align:center;' rowspan='2'>Action By</td>"; ?>
				<td style="width:80px;text-align:left;" rowspan="2">&nbsp;Remark</td>   
				<?php if ($ShowEdit > 0) echo "<td style='width:60px;text-align:left;' rowspan='2'>&nbsp;</td>"; ?>      
		</tr> 
		<tr height=30px; style="background:#FFF6F4;font-weight:bold;">    			
			<?php if ($ShowSchematic > 0) echo "<td width='70px'>Target</td><td width='70px'><span style='color:RED;'>Revised</span>&nbsp;/<br><span style='color:GREEN;'>Completed</td>"; ?>
			<?php if ($ShowGFC > 0) echo "<td width='70px'>Target</td><td width='70px'><span style='color:RED;'>Revised</span>&nbsp;/<br><span style='color:GREEN;'>Completed</td>"; ?>
			<?php if ($ShowLastIssued > 0) echo "<td width='40px'>RevNo</td><td width='70px'>Date</td>"; ?>
		</tr>
<?php
}

function TabulateBW ($bk,$dc,$ProjID,$schematic,$r0release,$r0target,$lastissued,$remark,$datarange,$from,$to,$dcX,$mysqli){
		
	// Find discipline name for this discipline code
	$co = sizeof($dcX);
	for ($i = 0; $i < $co; $i++) {    	
		if ($dcX[$i]["dc"] === "$dc") $dcname = $dcX[$i]["name"];		
	}
	
	// Get the drawing list array
	$x = "project_id = $ProjID and dwgidentity = '$bk' and disciplinecode = '$dc' and active = 1";
	
	// Filter data to be displayed
	if ($datarange === "GFC releases only") $x = "r0issuedflag = 1 and $x";
	if ($datarange === "Pending Drawings") 	$x = "r0issuedflag = 0 and $x";
	
	// Date Range from Last Issued
	if ($from && $to) $x = "lastissueddate > '$from' and lastissueddate < '$to' and $x";	

	// Query
	$sql = "select id from dwglist where $x";
	//echo "sql: $sql;";	
    
	if ($result = $mysqli->query($sql)) {
		
		$count = $result->num_rows;
		
		if ($count > 0){
			
			// Display the discipline name
			echo "<br><span style='font-weight:bold;'>$dcname</span>";
			echo "<br>dc: $dc Schematic: $schematic r0release: $r0release r0targetdt: $r0target Lastissued: $lastissued Remark: $remark Datarange: $datarange From: $from To: $to";
			
			// Serial Number - Reset
			$no = 1;
			
			// New DWGid object instantiate
			$dx = new DWGid();
			
			// Generate the drawing list header row
			//$dx->DWGListTableHeader(1,$schematic,$r0release,$lastissued,0,0);
						
			
			// Generate the drawing list data rows
			while ($row = $result->fetch_row()){
				
	    		// Get details of this drawing
		    	$a = $dx->GetDWGDetails($row[0]);
		    	
		    	// Display the data rows in the table
		    	//$dx->DWGListTableRow($no,$schematic,$r0release,$lastissued,0,0,$a,"-");
		    	
		    	$no++;
		    	
	    	} 
	    	   	        
	    	$result->close();
	    	
		}     	   
	} else {		
		echo "Error: $mysqli->error";		
	}
	
	echo '</table>';
}


?>