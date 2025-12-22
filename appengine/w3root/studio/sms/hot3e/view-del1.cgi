<?php
$tmid 		= $_GET['tmid'];
$transno 	= $_GET['transno'];
//echo "TmID: $tmid";
?>
<form action="project.cgi" method="GET">

	<input type="hidden" name="a" value="t3xview-del2">
	<input type="hidden" name="tmid" value="<?php echo $tmid; ?>">
	<input type="hidden" name="transno" value="<?php echo $transno; ?>">		
	Deleted Transmittal cannot be undeleted.<br> 
	Do you want to delete Transmittal No: <?php echo $transno; ?><br><br>
	<input type="submit" name="submit" value="Yes" style="width:100px;">
	<input type="submit" name="submit" value="No" style="width:100px;">

</form>