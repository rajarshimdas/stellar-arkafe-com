<?php /* Time Tracker Tabulation
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 27-Jan-2012				|
| Updated On:           				|
+-------------------------------------------------------+
*/

require_once 'foo/timesheets/feeEstimate.php';
require_once 'foo/timeAdd.php';

// Variables
$pid = $_GET['pid'];
$ver = $_GET['ver'];

// Get the projectname
$query = 'select projectname from projects where id = '.$pid;

if ($result = $mysqli->query($query)) {
    $row = $result->fetch_row();
    $projectname = $row[0];
    $result->close();
}

// Get the Stage Array
$stageX = getStageArray($mysqli);

// Get HR Group Array
$hrgroupX = getHRgroupArray($mysqli);





// No Project Selected
if (!$pid) {
    die('<br>&nbsp;<br>Error: Project Not Selected. Click Home and Select a Project....');
}

if ($ver === '' || !$ver) {
    // Get the current version
    include 'foo/bar/getVersion.php';
    $ver = getVersion($pid,$mysqli);
}

echo '<div style="background:#e4bc97;width:1000px;border: 1px solid black;">&nbsp;<br><span style="font-size: 125%; font-weight: bold">'.$projectname.'</span><br>';

if (!$ver) {
    echo 'Fee Calculator Estimate not uploaded for this project.';
} else {
    echo 'Displaying Version: <span style="font-weight:bold">'.$ver.'</span>&nbsp;or&nbsp;';
    include 'foo/bar/showVersions.php';
}
echo '<br>&nbsp;</div><br>';
?>
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

