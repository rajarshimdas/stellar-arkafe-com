<?php /* UpdateRoleinprojects4TLs.cgi */

/*  Rajarshi Das (1:59 PM 25-Jun-07
To Update/Register TLs & DMs in the roleinprojects table from projects table
 
Not required to run reqularly. This was needed to populate the
roleinprojects table for the internet login, where logged users
will see only those project for which they are registered in
the roleinproject table...
*/

// include 'dblogin.cgi';

// Get Team Leader and Design manager details 
$sql1 	= 	"select id, teamleader, designmanager from projects";

// Get the projects array
if ($result = $mysqli->query($sql1)) {

    /* fetch object array */
    while ($row = $result->fetch_row()) {
        
    	// Array of ProjID, TL, DM 
    	$ProjX[] = array("pid"=>$row[0],"tl"=>$row[1],"dm"=>$row[2]);
    	
    }

    /* free result set */
    $result->close();
}

// Count total number of projects
$totalprojects = sizeof($ProjX);

// Team Leaders and Design Managers Register/Update
for ($i = 0; $i < $totalprojects; $i++) {
    
	$projectid 		= $ProjX[$i]["pid"];
	$teamleader 	= $ProjX[$i]["tl"];
	$designmanager 	= $ProjX[$i]["dm"];
	
	// Team Leader -> Role_id = 30
	$sql2 	= 	"select loginname from roleinproject where 
				project_id = $projectid and roles_id = 30";
	// Team Leader info not found
	$sql3a 	= 	"insert into roleinproject value ($projectid,'$teamleader',30,1)";
	// Team Leader info to be updated
	$sql3b 	= 	"update roleinproject set loginname = '$teamleader'			
				where project_id = $projectid and roles_id = 30";

	//Design managers -> Role_id = 35
	$sql5 	= 	"select loginname from roleinproject where 
				project_id = $projectid and roles_id = 35";
	// Design Manager info not found
	$sql5a 	= 	"insert into roleinproject value ($projectid,'$designmanager',35,1)";
	// Design Manager info to be updated
	$sql5b 	= 	"update roleinproject set loginname = '$designmanager'			
				where project_id = $projectid and roles_id = 35";
	
	echo "$i ProjID: $projectid TL: $teamleader DM: $designmanager<br>";
	
	$foundTL = 0; $foundDM = 0;
	
	// Team Leader
	if ($result = $mysqli->query($sql2)) {    	
    	$foundTL = $result->num_rows;   	
    	$result->close();
	}
	
	if ($foundTL < 1) {
		/* Team Leader is not registered -> Insert TL details */
		if (!$mysqli->query($sql3a)) printf("Error[3a]: %s\n", $mysqli->error);
	} else {
		/* Team Leader is registered -> Update TL details */
		if (!$mysqli->query($sql3b)) printf("Error[3b]: %s\n", $mysqli->error);
	}
	
	// Design Manager
	if ($result = $mysqli->query($sql5)) {    	
    	$foundDM = $result->num_rows;   	
    	$result->close();
	}
	
	if ($foundDM < 1) {
		/* DM is not registered -> Insert DM details */
		if (!$mysqli->query($sql5a)) printf("Error[3a]: %s\n", $mysqli->error);
	} else {
		/* DM is registered -> Update DM details */
		if (!$mysqli->query($sql5b)) printf("Error[3b]: %s\n", $mysqli->error);
	}
	
}


$mysqli->close();

?>