<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 						|
| Updated On: 29-May-09					|
+-------------------------------------------------------+
*/
$teamleader 	= $_GET['teamleader'];
$loginname_tm 	= $_GET["pm"];

/* User rights validation */
if ($roleid > 10) {
	$mc = "$loginname, you do not have Team editing rights for $projectname.";
	header("Location: rajarshi.cgi?a=t1xteam&mc=$mc");
	die;
}

?>
<script type="text/javascript">
window.onload = function () {
document.getElementById('newteamleader').select();
};
</script>

<form action="execute.cgi" method="POST">
<input type="hidden" name="a" value="t1xteam-TL2">
<input type="hidden" name="sx" <?php echo 'value="'.$sessionid.'"'; ?>>
<input type="hidden" name="teamleader" value="<?php echo $teamleader; ?>">
<input type="hidden" name="pm" value="<?php echo $loginname_tm; ?>">
<table style="text-align: left; width: 100%;background:#E8E9FF;" border="0"  cellpadding="2" cellspacing="0">
    <tbody>
      <tr>
      
        <td align="center" valign="undefined" width="30%">
          <?php echo $teamleader; ?> 
        </td>
        
        <td align="center" valign="undefined" width="70%">  
                
          Change Team Leader: 	  
		  <select name="newteamleader" style="width:200px;">
          <option value="-- Select --">-- Select --
          <?php
  			$query = "select id,fullname,loginname from users where domain_id = $domainid";
  			include('foo/arnav/angels.cgi');
  			
  			if ($result = $mysqli->query($query)) {
			    
			    while ($row = $result->fetch_row()) {
			        
			    	if ($row[1] !== $teamleader) echo "<option value='$row[2]'>$row[1]";
			    	
    			}
    					    
		    	$result->close();
			}
			
			$mysqli->close();
          ?>
          </select>		  
		           
          <input name="submit" type="submit" value="Edit" style="width:60px;">
          <input name="submit" type="submit" value="Cancel" style="width:60px;">
		  </td>
      </tr>
    </tbody>
  </table>
  </form>
