<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 31-Jan-2008								|
| Updated On: 			 								|
+-------------------------------------------------------+
| Committed Dates - Update						 		|
+-------------------------------------------------------+
*/

// Session variable
$sx = $_GET['sx'];

/* MySQL connection - select only */
include('../foo/arnav/config.php');
include('../foo/arnav/dblogin.cgi');
echo "MySQL: $mysqli->host_info<br>";
$mysqli->autocommit(FALSE); 

/* Validate the session */
if (!$sx) {	
	
	die('<h3>Error: Session invalid...</h3>');
	
} else {

	include('../foo/StartSession.php');	
	$ValidUser 	= 0;
	
	/* Validate and return critical information about this login session */
	$a 			= StartSession($sx,$mysqli);
	$ValidUser 	= $a["ValidUser"];
	$project_id = $a["projectid"];
	$roleid		= $a["roleid"];	
	
}

/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");
/* +-------------------------------------------------------+ */

// Get the User Inputs
$dwglist_id = $_GET["id"];
$commitdt	= trim($_GET["commitdt"]);
$reason 	= $_GET["reason"];

echo "Drawing ID: $dwglist_id<br>Commited Date: $commitdt<br>reason: $reason";


// Update dwglist
$query = 	"update 
				dwglist 
				
			set 
				dtime = STR_TO_DATE('$commitdt', '%d-%b-%y')
			
			where 
				id = $dwglist_id and
				project_id = $project_id";
//echo "<BR>Query: $query;";

if (!$mysqli->query($query)) {
	printf("Error: %s\n", $mysqli->error);
	$mysqli->rollback();
	$ex = 1;
}


// Record Committed Date updation in dwghistory
$query = "insert into dwghistory
			(dwglist_id, scaleina1, r0newdt, loginname, dtime, active) values
			($dwglist_id,'commitdt',STR_TO_DATE('$commitdt', '%d-%b-%y'),'".$a["loginname"]."',CURRENT_TIMESTAMP(),1)";
//echo "<BR>Query: $query";

if (!$mysqli->query($query)) {
	printf("Error: %s\n", $mysqli->error);
	$mysqli->rollback();
	$ex = 1;
}

$mysqli->commit();
$mysqli->close();

/* Redirect if transaction suceeded */
if ($ex > 0) {
	echo "Error: The request could not be executed. Pls retry";
} else {
	header("Location: t2DWGid-edit-x4.php?sx=$sx&id=$dwglist_id");
}


?>