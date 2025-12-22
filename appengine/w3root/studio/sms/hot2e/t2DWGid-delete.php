<?php
# Rajarshi Das
# Created On: 10-Dec-2007
#
$sx 		= $_GET["sx"];
$sheetno 	= $_GET["sheetno"];
$dwglist_id = $_GET["id"];

/* Cancel Button */
if ($_GET["go"] === "No") {	
	header("Location: t2DWGid-edit.php?sx=$sx&id=$dwglist_id");
	die;	
}

include('../foo/arnav/config.php');
include('../foo/arnav/angels.cgi');

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
$mysqli->close();

/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");

/* Role checking */
if (!$roleid || $roleid > 99) die("<h3>You do not have editing rights for this project...</h3>");


/* Delete the drawing */
if ($_GET["go"] === "Yes"){
	
	/*echo "Delete drawing $sheetno id: $dwglist_id"; */
	
	include('../foo/arnav/dblogin.cgi');
	DeleteSheetNo($dwglist_id,$loginname,$mysqli);
	$mysqli->close();
	header("Location: t2DWGid-edit.php?sx=$sx");
	die;
		
}

/* Function */
function DeleteSheetNo ($dwglist_id,$loginname,$mysqli) {
	
	/* errorflag */
	$ex = 0;	
	
	$mysqli->autocommit(FALSE);
	
	/* Update the dwglist table */
	$sql = "update dwglist set active = 0 where id = $dwglist_id and r0issuedflag = 0";	
	
	if (!$mysqli->query($sql)) {		
		printf("Error: %s\n", $mysqli->error);
		$mysqli->rollback();
		$ex = 1;
	}
	
	/* Record transaction in dwghistory table */
	$sql = "insert into dwghistory
				(dwglist_id, scaleina1, loginname, dtime) values
				($dwglist_id, 'Drawing Deleted', '$loginname', CURRENT_TIMESTAMP())";
	
	if (!$mysqli->query($sql)) {		
		printf("Error: %s\n", $mysqli->error);
		$mysqli->rollback();
		$ex = 1;
	}
	
	$mysqli->commit();	
	
	/* Return sucess/failure status */
	if ($ex < 1) return true; else return false;
	
}

?>