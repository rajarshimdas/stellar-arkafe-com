
<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 25-Jun-10					|
| Updated On:                                           |
+-------------------------------------------------------+
*/

require_once 'foo/timesheets/feeEstimate.php';
require_once 'foo/timeAdd.php';
//require_once 'bootstrap.php';

$pid = $projectid;
$ver = $_GET["ver"];
$mysqli = cn1();

if ($ver === "X"){
    die('No data available...');
}

// Get the Stage Array
$stageX = getStageArray($mysqli);

// Get HR Group Array
$hrgroupX = getHRgroupArray($mysqli);

?>
<link type='text/css' rel='stylesheet' href="/matchbox/themes/cool/timeTracker.css">
<table width="1000px" cellspacing="0">
    <?php
    // Generate Header Rows
    generateHeaderRow($pid, $mysqli);

    // Data Rows - Loop for all stages
    for ($i = 0;$i < count($stageX); $i++) {

        $stage_id = $stageX[$i]["id"];
        $stage_no = $stageX[$i]["stageno"];
        $stage_nm = $stageX[$i]["stage"];

        // echo "<br>".$stageX[$i]["id"].". ".$stageX[$i]["stage"];
        stageTabulate($pid,$stage_id,$stage_no,$stage_nm,$hrgroupX,$ver,$mysqli);

    }
    ?>
</table>

