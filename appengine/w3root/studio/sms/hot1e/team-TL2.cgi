<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 											|
| Updated On: 29-May-09									|
+-------------------------------------------------------+
*/

// Get Variables
$teamleader 	= $_POST['teamleader'];
$loginname_tm	= $_POST["pm"];
$newteamleader 	= $_POST['newteamleader'];
$submit 		= $_POST['submit'];

//echo "Projectid: $projectid<br>Teamleader: $teamleader<br>New Teamleader: $newteamleader<br>Existing TL loginname: $loginname_tm<br>";

/* Cancel button */
if ($submit === "Cancel" || !$submit) {
	header("Location: rajarshi.cgi?a=t1xteam");
	die;
}

/*
+-------------------------------------------------------+
| Data Validation										|
+-------------------------------------------------------+
*/
/* Role validation */
if ($roleid > 10) {
	$mc = "$loginname, you do not have rights to change Teamleader for $projectname";
	header("Location: rajarshi.cgi?a=t1xteam-TL&teamleader=$teamleader&mc=$mc");
	die;
}

/* No change */
if ($teamleader === $newteamleader) {
	$mc = "Select New Name, specified new Teamleader's name was same as the existing Teamleader's name.";
	header("Location: rajarshi.cgi?a=t1xteam-TL&teamleader=$teamleader&mc=$mc");
	die;	
}

/* Invalid data */
if ($newteamleader === "-- Select --") {
	$mc = "Invalid data input. Retry";
	header("Location: rajarshi.cgi?a=t1xteam-TL&teamleader=$teamleader&mc=$mc");
	die;	
}

// Regex check
if (preg_match("/^[a-z0-9._-\s]+$/i", $newteamleader)){
	
	/* Input is valid */	
	$regex = 1;
	
} else {
	
	/* Input is invalid */
	header("Location:rajarshi.cgi?a=t1xteam-TL&teamleader=$newteamleader&mc=Invalid special character(s) detected. Rectify and try again...");
	die;
	
}
/*
+-------------------------------------------------------+
| Database												|
+-------------------------------------------------------+
*/
include "foo/arnav/dblogin.cgi";

/* Make the current Project Manager inactive */
$query = 	"update 
				roleinproject

			set
				roles_id = 99,
				active = 0
				
			where 
				project_id = $projectid and
				loginname ='$loginname_tm' and
				roles_id = 30";

echo "Q1: $query<br>";
if (!$mysqli->query($query)) {
	printf("<br>Error[Q1]: %s\n", $mysqli->error);
	die;
}


/* Check if the New PM already exists as a team member */
$user_exists_flag = 0; 
$query = "select active from roleinproject where project_id = $projectid and loginname = '$newteamleader'";

echo "Q2: $query<br>";

if ($result = $mysqli->query($query)) { 
	/*
    $row = $result->fetch_row();   
    $user_exists_flag = $row[0];
    */
	$user_exists_flag = $result->num_rows;
   
    $result->close();
    
} else {	
	printf("<br>Error[Q1]: %s\n", $mysqli->error);
	die;
}

/* Register the new PM */
if ($user_exists_flag > 0) {
	// Upgrade the user to PM
	$query = "update roleinproject set roles_id = 30 , active = 1 where project_id = $projectid and loginname = '$newteamleader'";
} else {
	// Register new user
	$query = "insert into roleinproject (project_id,loginname,roles_id,active) values ($projectid,'$newteamleader',30,1)";
}
echo "Q3: $query<br>";
if (!$mysqli->query($query)) {
	printf("<br>Error[Q1]: %s\n", $mysqli->error);
	die;
}

$mysqli->close();
	
/*
+-------------------------------------------------------+
| Redirection											|
+-------------------------------------------------------+
*/
header("Location: rajarshi.cgi?a=t1xteam");

?>