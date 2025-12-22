<?php /* t2DWGid-update-C.php

Rajarshi Das
Created On: 21-Dec-2007
Last Updated: - 

*/

$sx = $_GET["sx"];


// MySQL connection - select,update,insert privileges
include('../foo/arnav/config.php');
include('../foo/arnav/dblogin.cgi');
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
	$loginname	= $a["loginname"];
	
}

/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");

/* Role checking */
if (!$roleid || $roleid > 99) die("<h3>You do not have editing rights for this project...</h3>");


/* errorflag */
$ex  = 0;


/* Input Variables */
$dwglist_id 		= $_GET["id"];
$newtargetdt_gfc 	= $_GET["newtargetdt_gfc"];
$issuedflag_gfc		= $_GET["issuedflag_gfc"];
$reason_gfc			= $_GET["reason_gfc"];


/* Check if r0targetdt is empty (not yet set) */
$query = "select r0targetdt from dwglist where id = $dwglist_id and project_id = $project_id";

if ($result = $mysqli->query($query)) {    
	$row = $result->fetch_row();       
	$r0targetdt = $row[0];   
    $result->close();
}

if ($r0targetdt === '0000-00-00'){
	$r0targetdt_sql = ", r0targetdt = STR_TO_DATE('$newtargetdt_gfc', '%d-%b-%y')";
} else {
	$r0targetdt_sql = "";
}

/* Update dwglist table */
$sql = "update dwglist 

		set
			newr0targetdt 	= STR_TO_DATE('$newtargetdt_gfc', '%d-%b-%y')
			$r0targetdt_sql			 				
			
		where 
			id = $dwglist_id and 
			r0issuedflag = 0 and 
			project_id = $project_id";

/*echo "<br>SQL: $sql";*/ 
if (!$mysqli->query($sql)) {
	printf("Error: %s\n", $mysqli->error);
	$mysqli->rollback();
	$ex = 1;
}


/* Update dwghistory table */
$sql = "insert into dwghistory
		(dwglist_id, scaleina1, newr0dt, r0newdt, r0reason,loginname,dtime) values
		($dwglist_id, 'Drawing Edited - C', 1, STR_TO_DATE('$newtargetdt_gfc', '%d-%b-%y') , '$reason_gfc', '$loginname',CURRENT_TIMESTAMP())";

/*echo "<br>SQL: $sql"; */
if (!$mysqli->query($sql)) {
	printf("Error: %s\n", $mysqli->error);
	$mysqli->rollback();
	$ex = 1;
}


$mysqli->commit();
$mysqli->close();

/* Exit */	
if ($ex > 0) {
	echo "<br>Error: The request could not be executed. Pls retry";
} else {
	header("Location: t2DWGid-edit-x4.php?sx=$sx&id=$dwglist_id");
}


?>