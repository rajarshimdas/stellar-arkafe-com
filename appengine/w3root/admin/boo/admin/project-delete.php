<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 09-Jan-09									|
| Updated On: 											|
+-------------------------------------------------------+
*/ 

$project_id     = $_POST["pid"];
$mysqli         = cn2();

if ($project_id < 1) {
	
	$message = "Select a project and try again.";
	$flag = 2;

} else {	
			
	$query = "select projectname from projects where id = $project_id";
        
	if ($result = $mysqli->query($query)) {
	
	    $row = $result->fetch_row();
	    $projectname = $row[0];   
	
	    $result->close();
	    
	}	
	
	$query = "update projects set active = 0 where id = $project_id";		
	$mysqli->query($query);
	
	$message = "Project $projectname is  deleted.";
	$flag = 1;
	
}

//$mysqli->close();

/*
+-------------------------------------------------------+
| Notification											|
+-------------------------------------------------------+

$mail_to        = $superAdminEmail;
$mail_from      = $superAdminEmail;
$mail_subject   = 'Project Deleted: '.$projectname;
$mail_body      = $projectname.' is deleted.';

qmail($mail_to,$mail_cc,$mail_bcc,$mail_from,$mail_subject,$mail_body,$imageX);
*/
?>