<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 10-Dec-2007								|
| Updated On: 29-Jan-2008								|
+-------------------------------------------------------+
| Drawing List Editing	- iframe 						|
+-------------------------------------------------------+
*/
$now = strtotime("now"); // Get the current UNIX Timstamp

if ($blockno === "- Block/All -"){
	$blocknoX = "";
}else {
	$blocknoX = " and dwgidentity = '$blockno' ";
}

if ($dc === "- Discipline/All -"){
	$disciplinecodeX = "";
}else {
	$disciplinecodeX = "and disciplinecode = '$dc' ";
}
?>


<table
	border="1" cellspacing="0" width="100%"
	style="font: normal 13px  sans-serif, Verdana, Arial, Helvetica, Frutiger, Futura, Trebuchet MS;"
>

<?php

$sql = "select 
			
			sheetno,
			title,
			id,
			DATE_FORMAT(commitdt,'%d-%b-%y') as commitdt,
			DATE_FORMAT(newr0targetdt,'%d-%b-%y') as targetdt,
			revno,
			DATE_FORMAT(anewtargetdt,'%d-%b-%y') as schemedt,
			r0issuedflag
			
		from view_drawing_list 
		where 
			
			project_id = ".$a["projectid"]." 
			$blocknoX
			$disciplinecodeX
		
		order by sheetno";	

					
if ($result = $mysqli->query($sql)) {
	
	// Drawing list Serial Number
	$no	= 1;
    
	/* fetch object array */
   	while ($row = $result->fetch_row()) {
   		   		
   		$sheetno = $row[0];
   		if (!$row[6]) $schemedt = '&nbsp;'; else $schemedt = $row[6];
   		
   		// Format targetdate
   		if (!$tdt = $row[4]){
   			
   			// Target date not available
   			$targetdt = '&nbsp;';
   			
   		} else {   			
   						
   			if ($row[7] > 0) {
   				
   				// Drawing is already issued as GFC
   				$targetdt = '<span style="color:grey;">'.$tdt.'</span>';
   				
   			} else {
   				
   				// Drawing is not issued as GFC
	   			$d = strtotime($tdt);
				$x = $now - $d;
				
				if ($x > 0){
					
					// Passed date
					$targetdt = $tdt.'<span style="font-weight:bold;color:red;">?</span>';
					
				} else {
					
					// Furture date
					$targetdt = $tdt;
				
				}
				
   			}
   		}
   		
   		
   		if ($roleid < 45){
   			
   			// TLs and DMs
   			echo 	'<tr align="center">
   						<td width="5%">'.$no.'</td>
   						<td width="13%"><a href="t2DWGid-edit.php?sx='.$sx.'&id='.$row[2].'" target="EditPanel" style="text-decoration:none;">'.$sheetno.'</a></td>
   						<td width="38%" align="left">&nbsp;'.$row[1].'</td> 
   						<td width="8%">'.$row[5].'</td>
   						<td width="12%">'.$schemedt.'</td>
   						<td width="12%">&nbsp;'.$row[3].'&nbsp;</td>
   						<td width="12%">'.$targetdt.'</td>   						  						
   					</tr>';
   			
   		}else{
   			
   			// All other regular users
   			echo 	"<tr align='center'>
   						<td width='5%'>$no</td>
   						<td width='13%'><a href='t2DWGid-edit.php?sx=$sx&id=$row[2]' target='EditPanel' style='text-decoration:none;'>$sheetno</a></td>
   						<td width='50%' align='left'>$row[1]</td>
   						<td width='8%'>$row[5]</td>
   						<td width='12%'>$schemedt</td>
   						<td width='12%'>$targetdt</td>   						
   					</tr>";
   		
   		}
   		
   		$no++;
    }

    	/* free result set */
    	$result->close();
	} else {
		echo "Error: $mysqli->error";
	}
					
	?>

</table>

<?php $mysqli->close(); ?>