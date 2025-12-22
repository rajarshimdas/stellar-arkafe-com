<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 10-Dec-2007				|
| Updated On: 31-Jan-2008 				|
+-------------------------------------------------------+
| Drawing List Editing	- iframe contents		|
+-------------------------------------------------------+
*/

// Get Session Variable
$sx = $_GET['sx'];

/* MySQL connection - select only */
include('../foo/arnav/config.php');
include('../foo/arnav/angels.cgi');
//echo "MySQL: $mysqli->host_info<br>";

/* Validate the session */
if (!$sx) {	
	
	die('<h3>Error: Session invalid...</h3>');
	
} else {

	include('../foo/StartSession.php');	
	$ValidUser 	= 0;
	
	/* Validate and return critical information about this login session */
	$a 		= StartSession($sx,$mysqli);
	$ValidUser 	= $a["ValidUser"];
	$project_id     = $a["projectid"];
	$roleid		= $a["roleid"];
	
}

/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");
/* +-------------------------------------------------------+ */

// Get dwglist.id of the selected drawing
$dwglist_id = $_GET['id'];

/* Load DWGid class */
include '../foo/t2DWGid.php';
$dx = new DWGid($project_id,$roleid);
$a = $dx->GetDWGDetails($dwglist_id,$mysqli);

?>
<link rel="stylesheet" type="text/css" href="../foo/calendar/styles.css" />	
<script type="text/javascript" src="../foo/calendar/classes2.js"></script>
<script type="text/javascript">
	
	window.onload = function calender(){		
		var dp_cal_01;
		dp_cal_01 = new Epoch('epoch_popup','popup',document.getElementById('anewtargetdt'));		
	}

</script>

<table width="100%" border="0">

<?php /*
+-------------------------------------------------------+
|	Schematic Stage	- Stage and Target Date Tracking	|
+-------------------------------------------------------+
*/
?>	
	
	<tr>
	<?php 
		if ($a["r0issuedflag"] > 0){
			
			// Schematic stage closed
			echo "<td>Schematic Stage closed...<br>&nbsp;<br></td>";
			
		} else {	
			
			// Drawing still in schematic stage
	?>	
		<form action="t2DWGid-edit-x2a.php" method="GET">
		<input name="id" type="hidden" value="<?php echo $dwglist_id; ?>">
		<input name="sx" type="hidden" value="<?php echo $sx; ?>">
	 	<td valign="top" align="left" width="100%">			
				<!-- <input style="width:75px;" type="text" value="<?php /* echo $a["stage"]; */ ?>" readonly> -->
				<select "name="newstage" style="width:65%;">
					<?php 
						$stg = $a["stage"];
						echo "<option>$stg";
						//if ($stg != 1) echo "<option>1";
						if ($stg != 2) echo "<option>2";
						if ($stg != 3) echo "<option>3";
						if ($stg != 4) echo "<option>4";						
					?>					
				</select> : New Stage
				<br><input name="a1" style="width:65%;" type="text" value="<?php echo $a["atargetdt"]; ?>" readonly> : Target Date
				<br><input id="anewtargetdt" name="newtargetdt_a" style="width:65%;" type="text" value="<?php echo $a["anewtargetdt"]; ?>"> : Revised Date
				
				<br><textarea name="reason_a" style="width:65%;height:80px;"></textarea> : Reason				
				<br><select name="issuedflag_a" style="width:65%;">				
					<?php
						echo "<option>".$a["stageclosed"];
						if ($a["stageclosed"] != 0) echo "<option>0";
						if ($a["stageclosed"] != 1) echo "<option>1";
					?>
				</select> : Issuedflag				
				<br><input type="submit" name="go" value="Update" onclick="SetDivPosition()">
				<br>&nbsp;			
		</td>
		</form>	
		<?php } ?>		
	</tr>	

</table>

<?php $mysqli->close(); ?>