<?php /* t2DWGid-update-A.php

Rajarshi Das
Created On: 17-Dec-2007
Last Modified: 21-Dec-2007

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
    $a 		= StartSession($sx,$mysqli);
    $ValidUser 	= $a["ValidUser"];
    $project_id = $a["projectid"];
    $roleid	= $a["roleid"];
    $loginname	= $a["loginname"];

}

/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");

/* Role checking */
if (!$roleid || $roleid > 99) die("<h3>You do not have editing rights for this project...</h3>");

// Input Variables
$dwglist_id	= $_GET["id"];
$title		= $_GET["title"];
$remark		= $_GET["remark"];
// $actionby	= trim($_GET["actionby"]);

/* Input Data Validation */
// Todo

/* Action by Formatting */
if ($actionby === "-- None/Select --" || !$actionby) $actionby = "-";

/* Update the Database: dwglist, dwghistory */
$mysqli->autocommit(FALSE); 
$ex /* errorflag */ = 0;

$sql = "update 
            dwglist
        set
            title = '$title',
            remark = '$remark',
            priority = '$actionby'
        where
            id = $dwglist_id and
            project_id = $project_id";
//echo "<br>SQL1: $sql";

if (!$mysqli->query($sql)) {
    printf("Error: %s\n", $mysqli->error);
    $mysqli->rollback();
    $ex = 1;
}


$sql = "insert into dwghistory
            (dwglist_id,title,remark,scaleina1,newstgreason,loginname,dtime)
        values
            ($dwglist_id,'$title','$remark','Drawing Edited - A','Action by:$actionby','$loginname',CURRENT_TIMESTAMP())";

//echo "<br>SQL2: $sql";

if (!$mysqli->query($sql)) {
    printf("Error: %s\n", $mysqli->error);
    $mysqli->rollback();
    $ex = 1;
}

$mysqli->commit();
$mysqli->close();

if ($ex > 0) {
    echo "Error: The request could not be executed. Pls retry";
} else {
    header("Location: t2DWGid-edit-x1.php?sx=$sx&id=$dwglist_id");
}

?>