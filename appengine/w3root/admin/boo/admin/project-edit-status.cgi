<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 12-Aug-10 				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$projectid = $_GET["pid"];

require BD.'Controller/Projects/Projects.php';
$scope = bdProjectScope($mysqli);

include 'boo/admin/project-functions.php';
$px[] = getProjectData($projectid, $mysqli);
// var_dump($px);

// Message bar
messagebar($_GET["rx"], $_GET["flag"]);

// Display edited project
tabulateThisProject($px, 0, $mysqli, $scope);

?>