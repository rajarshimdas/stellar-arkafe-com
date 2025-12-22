<?php
$blockno 	= $_GET["bno"];
$blockname 	= $_GET["bn"];
?>
<form action="execute.cgi" method="POST">
	<input name="a" type="hidden" value="t2xblocks-deleteaction">
	<input type="hidden" name="sx" <?php echo 'value="'.$sessionid.'"'; ?>>
		
  	<br>Deleting the block will also delete the drawing list for this block.<br>
  			Do you want to proceed?<br><br>
  	
  	<input name="blockno" type="hidden" value="<?php echo $blockno; ?>">
  	<input name="blockname" type="hidden" value="<?php echo $blockname; ?>">
  	<input type="submit" name="submit" value="Delete" style="width:100px;">
  	<input type="submit" name="submit" value="Cancel" style="width:100px;">
</form>