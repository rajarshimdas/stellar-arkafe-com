<?php /* SearchTM */

function TabulateTM ($mysqli, $tmid, $co, $item = "not_specified") {		
	
	$sql = "select
			
			transno,
			contact,
			company,
			address,
			sentmode,
			purpose,
			dt,
			remark,
			loginname
			
			from transmittals 
			
			where id = $tmid";
	
	if ($result = $mysqli->query($sql)) {    						
    	
		$row = $result->fetch_row();

    	$transno 	= $row[0];
    	$sentto 	= $row[1];
    	$company 	= $row[2];
    	$address 	= $row[3];
    	$sentmode 	= $row[4];
    	$purpose 	= $row[5];
    	$date		= $row[6];
    	$remark 	= $row[7];
    	$sentby 	= $row[8];      
        
    	$result->close();
	} 	
	else {		
		echo "Error: $mysqli->error";
		die;
	}	
	
	$sql = "select
	
			srno,
			item,
			nos,
			description
			
			from translist
			
			where transmittal_id = $tmid";
	
	if ($result = $mysqli->query($sql)) {    						
    	
		while ($row = $result->fetch_row()) {			
			
			// Transmittal List array
			$tX[]=array("srno" => $row[0], "item" => $row[1], "nos" => $row[2], "desc" => $row[3]);
        
    	}    

    	$result->close();
	} 	
	else {		
		echo "Error: $mysqli->error";
		die;
	}
	
	echo "<br>TransID: $tmid";
	?>
		
	<table border="0" width="850px" style="border:solid cadetblue;border-width:1px;" cellspacing="0">
		<tr>
			<!-- Serial Number -->
			<td width="5%" valign="top" align="center" style="border-right:solid cadetblue;border-width:1px;">
				<?php /* Serial Number */ echo $co; ?>
			</td>
			<!-- Transmittal Header Info -->
			<td width="35%" valign="top" style="border-right:solid cadetblue;border-width:1px;" cellspacing="0">
				<table width="100%">
					<tr>
						<td align="right" width="25%">Trans No:</td>
						<td style="border-bottom:solid cadetblue;border-width:1px;" width="75%">&nbsp;<?php echo $transno; ?></td>
					</tr>
					<tr>
						<td align="right">To:</td>
						<td style="border-bottom:solid cadetblue;border-width:1px;">&nbsp;<?php echo $sentto; ?></td>
					</tr>
					<tr>
						<td align="right">From:</td>
						<td style="border-bottom:solid cadetblue;border-width:1px;">&nbsp;<?php echo $sentby; ?></td>
					</tr>
					<tr>
						<td align="right">Date:</td>
						<td style="border-bottom:solid cadetblue;border-width:1px;">&nbsp;<?php echo dateformat($date); ?></td>
					</tr>
				</table>				
			</td>
			<!-- Transmittal List -->	
			<td width="60%" valign="top">
				<table width="100%" border="1" cellpadding="0" cellspacing="0" style="font-size:85%;">
					<tr align="center">
						<td width="10%">No</td>
						<td width="35%">Item</td>
						<td width="10%">Nos</td>
						<td width="45%">Description</td>
					</tr>
					
					<?php							
						for ($i = 0; $i < 12; $i++) {								
							echo "<tr>
									<td align='center'>&nbsp;".$tX[$i]['srno']."&nbsp;</td>
									<td>&nbsp;".$tX[$i]['item']."</td>
									<td align='center'>&nbsp;".$tX[$i]['nos']."&nbsp;</td>
									<td>&nbsp;".$tX[$i]['desc']."</td>										
								</tr>";						
						}
					?>
														
				</table>
			</td>
		</tr>
	</table>	
	
	<?php	
}
?>