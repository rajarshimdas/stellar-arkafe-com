<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 12-Oct-2012				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/

// $projectstageno = $_GET["pid"];

/* Get stageid 
$query = 'select id from projectstage where stageno = '.$projectstageno.' and active = 1';

if ($result = $mysqli->query($query)) {

    $row        = $result->fetch_row();
    $stageid    = $row[0];
    $result->close();
}
*/
$stageid = $_GET["stageid"];

// Display Page Body
$dc = '- Select/All -';
include 'view-stagewise-02.cgi';

?>
