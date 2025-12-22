<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 14-Jan-08									|
| Updated On:											|
+-------------------------------------------------------+
| Page for Client and PMC Registration and Display.		|
+-------------------------------------------------------+
| Requires view: view_users								|
+-------------------------------------------------------+
*/

?>
<table style="text-align: left; width: 100%;" border="0"  cellpadding="2" cellspacing="0">
    <tr>     
    	<form action="execute.cgi" method="GET">
				
			<input type="hidden" name="a" value="t1xclientpmc-01">		  	
			<input type="hidden" name="sx" value="<?php echo $sessionid; ?>">
			   	
		<td align="center" valign="undefined" width="30%" style="background:#E8E9FF;">
        	Client & PMC
		</td>	
				 	 
        <td align="right" valign="undefined" width="50%" style="background:#E8E9FF;">   	  	
		  	          	 
          	Contacts*:          	
          	<select name="cn" style="width:200px;">
          		<option>-- Select --  	
          	
          		<?php /* Display the contacts drop down combo box */
          			
          			include("foo/arnav/angels.cgi");
          			if (!$mysqli) {
          				echo "<option>dB connection error";
          				die;
          			}
          			
          			$query = "select contact from transname where project_id = $projectid and active = 1 and extranetlogin = 0 order by contact";
          			//echo "<br>sql: $query";
          			
          			if ($result = $mysqli->query($query)) {

    					/* fetch object array */
    					while ($row = $result->fetch_row()) {
        					//printf ("%s (%s)\n", $row[0], $row[1]);
        					echo "<option>$row[0]";
    					}

    					/* free result set */
    					$result->close();
					}

					/* close connection */
					$mysqli->close();          			        			
          		
          		?>      		
          		
          	</select>
          	
          	<input name="go" type="submit" value="Login Setup" style="width:100px;">
          	
		</td>
		
		<td align="left" valign="undefined" width="50%" style="background:#E8E9FF;">                    	 
          	<input name="go" type="submit" value="New Contact" style="width:100px;">		  
		</td>
		
		</form>		  
    </tr>
</table>

<?php /*
+-------------------------------------------------------+
| Tabulate the External Users							|
+-------------------------------------------------------+
*/ 
include("foo/arnav/angels.cgi");
  		

$query = "select loginname,fname,lname,role_id,ok,email,active,id,organization			
			from view_users 
		where project_id = $projectid
			order by fname";

if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_row()) {
    	
    	/* Create the data Array */        
        $dataX[] = array(
        				"loginname"		=> $row[0],
        				"fname" 		=> $row[1],
        				"lname"			=> $row[2],
        				"roleid"		=> $row[3],
        				"ok"			=> $row[4],
        				"email"			=> $row[5],
        				"active"		=> $row[6],
        				"id"			=> $row[7],
        				"organization"	=> $row[8]
        				);
        
    }

    /* free result set */
    $result->close();
}

/* close connection */
$mysqli->close();

?>
<span style="font-size:12;">Note: External Users access rights management is TL/DM`s responsibility</span>
 
<br><br>
<span style="font-size:16px;font-weight:bold">Registered Users:</span>
<table width="850px" style="text-align:left; font-size:90%;" border="1"  cellpadding="2" cellspacing="0">
	<tbody>
    	<tr align="center" style="background:#FFF6F4;font-weight:bold;">
      		<td width="5%">No</td>
      		<td width="20%">Name</td> 
      		<td width="20%">Company</td>       		      		
      		<td width="23%">email</td>     		
      		<td width="12%">Role</td>
      		<td width="10%">Active</td>
	  		<td width="10%">Profile</td>        
    	</tr>
<?php
	// Data array size
	$co = count($dataX); 
	
	// If the data array contains any data, continue else exit
	if ($co > 0){
		
		// Required for converting role_id to role
		include("foo/roleid2role.php");
		
		for ($i = 1; $i <= $co; $i++) {
			
			// array counter
			$a = $i - 1;
			
			// Formatting: Active			
			if ($dataX[$a]["active"] > 0){
				$accepted_terms_and_conditions = "Yes";
			} else {
				$accepted_terms_and_conditions = "No";
			}
			
			// Formatting: Roleid to Role
			$rx = roleid2role($dataX[$a]["roleid"],'foo/arnav/angels.cgi');
			
			// Generate Rows
			echo "<tr>";
			echo "<form action='rajarshi.cgi' method='GET'>";
			echo "<input type='hidden' name='a' value='t1xclientpmc-show'>";
			echo "<input type='hidden' name='id' value='".$dataX[$a]["id"]."'>";
    		echo "<td align='center'>$i</td>";
    		echo "<td align='left'>&nbsp;".$dataX[$a]["fname"]."</td>";
    		echo "<td align='left'>&nbsp;".$dataX[$a]["organization"]."</td>";    		    		
    		echo "<td align='left'>&nbsp;".$dataX[$a]["email"]."</td>";
    		echo "<td align='left'>&nbsp;".$rx."</td>";
    		echo "<td align='center'>&nbsp;".$accepted_terms_and_conditions."&nbsp;</td>";
    		echo "<td align='center'><input type='submit' name='go' value='Edit'></td>";   
    		echo "</form>";		
    		echo "</tr>";
    		
		}
		
	}
?>
   	    
    
	</tbody>
</table>