<?php /* t2DWGid-update-B.php

Rajarshi Das
Created On: 21-Dec-2007
Last Updated: - 

*/

$sx = $_GET["sx"];

include('../foo/arnav/config.php');
include('../foo/arnav/dblogin.cgi');

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
	$loginname	= $a["loginname"];
	
}

/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");

/* Role checking */
if (!$roleid || $roleid > 99) die("<h3>You do not have editing rights for this project...</h3>");


/* Input Variables */
$ex /* errorflag */ = 0;
$dwglist_id 	= $_GET["id"];
$newstage		= $_GET["newstage"];
$newtargetdt_a 	= $_GET["newtargetdt_a"];
$issuedflag_a	= $_GET["issuedflag_a"];
$reason_a		= $_GET["reason_a"];


/* Date Formatting */

$mysqli->autocommit(FALSE);

/* Update dwglist table */
$sql = "update dwglist set

			newstage 	= $newstage,
			r0issuedt 	= STR_TO_DATE('$newtargetdt_a', '%d-%b-%y'),
			stageclosed = $issuedflag_a
			
		where id = $dwglist_id and project_id = $project_id";

//echo "<br>SQL: $sql";
if (!$mysqli->query($sql)) {
	printf("Error: %s\n", $mysqli->error);
	$mysqli->rollback();
	$ex = 1;
}


/* Update dwghistory table */
$sql = "insert into dwghistory
		(dwglist_id, scaleina1, newstg, newstage, newstgreason,remark,loginname,dtime) values
		($dwglist_id, 'Drawing Edited - B', 1, $newstage, '$reason_a', 'schematic_issuedflag:$issuedflag_a+newtartedate:$newtargetdt_a','$loginname',CURRENT_TIMESTAMP())";

//echo "<br>SQL: $sql";
if (!$mysqli->query($sql)) {
	printf("Error: %s\n", $mysqli->error);
	$mysqli->rollback();
	$ex = 1;
}


$mysqli->commit();
$mysqli->close();

/* Exit */	
if ($ex > 0) {
	echo "Error: The request could not be executed. Pls retry";
} else {
	header("Location: t2DWGid-edit-x2.php?sx=$sx&id=$dwglist_id");
}


?>