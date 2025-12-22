<?php
// Requires view_drawing_list
$sheetno 	= trim($_GET["sheetno"]);
$project_id = $_GET["ProjID"];
$sx 		= $_GET["sx"];

$sql = "select id from view_drawing_list where sheetno = '$sheetno' and project_id = $project_id";
//echo "sql: $sql";

// MySQL connect with select privilege
include('../foo/arnav/config.php');
include('../foo/arnav/angels.cgi');

if ($result = $mysqli->query($sql)) {

    /* fetch object array */
    while ($row = $result->fetch_row()) {
        $id = $row[0];
    }

    /* free result set */
    $result->close();
} else {
	echo "MySQL: $mysqli->error";
}

/* close connection */
$mysqli->close();

// Redirection
header("Location: t2DWGid-edit.php?id=$id&sx=$sx");

?>