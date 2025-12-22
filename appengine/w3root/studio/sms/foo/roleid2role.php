<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 18-Jan-07									|
| Updated On:											|
+-------------------------------------------------------+
| Roleid to Role mapping								|
| Not very efficient way to do this. But this function	|
| is not frequently used so this overhead is ok...		|
+-------------------------------------------------------+
*/

function roleid2role($id,$connection_path) {
    
	// MySQL connect
	include $connection_path;
		
	$query = "select roles from roles where id = $id";
	
	if ($result = $mysqli->query($query)) {
		
    	$row = $result->fetch_row();
        $x = $row[0];    	
    	$result->close();
    	
	}
	
	$mysqli->close();
	
	// Return the role
    return $x;
    
}

?>