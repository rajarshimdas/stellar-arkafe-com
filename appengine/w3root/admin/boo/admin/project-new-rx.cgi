<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 12-Aug-10 				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$projectid = isset($_GET["pid"]) ? $_GET["pid"] : 0;

if ($projectid > 0) {
    require BD . 'Controller/Projects/Projects.php';
    $scope = bdProjectScope($mysqli);

    $scopeIds = $scope[0]["id"];
    for ($i = 1; $i < count($scope); $i++) {
        $scopeIds = $scopeIds . ',' . $scope[$i]["id"];
    }
    echo '<input type="hidden" id="scopeIds" value="' . $scopeIds . '">';

    include 'boo/admin/project-functions.php';
    $px[] = getProjectData($projectid, $mysqli);
    // var_dump($px);

    // Message bar
    // messagebar($_GET["rx"], $_GET["flag"]);

    // Display edited project
    tabulateThisProject($px, 0, $mysqli, $scope);
} else {
    // messagebar($message, $flag);
}
