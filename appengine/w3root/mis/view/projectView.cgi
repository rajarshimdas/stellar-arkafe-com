<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 01-Feb-2012				|
| Updated On:           				|
+-------------------------------------------------------+
*/

require_once 'foo/timesheets/projectView.php';
require_once 'foo/getStageArray.php';
require_once 'foo/getTeammateArray.php';
require_once 'foo/pid2pname.php';
require_once 'foo/dateCal2Mysql.php';

// Variables
$pid = $_GET['pid'];
$fdt = $_GET['fdt1'];
$tdt = $_GET['tdt1'];
// echo 'PID: '.$pid.' fdt: '.$fdt.' tdt: '.$tdt;

// Get Stage list
$stageX = getStageArray($mysqli);
$co_stageX = count($stageX);
// echo "<br>Count stageX: ".$co_stageX;

// Get Teammates list
$teamX = getTeammateArray($mysqli);
$co_teamX = count($teamX);
//echo '<br>teamX: '.$co_teamX;



// Data Validation
if (!$pid) {
    die("<br>&nbsp;<br>Error: Project was not selected. Click on Home button and try again...");
}
if (!$fdt || $fdt === '-- Select --') {
    die("<br>&nbsp;<br>Error: From Date. Click on Home button and try again...");
}
if (!$tdt) {
    die("<br>&nbsp;<br>Error: To Date. Click on Home button and try again...");
}
// Project name and date range
require_once 'foo/pid2pname.php';
echo '<br>&nbsp;<span style="font-size:125%;font-weight:bold;">'.pid2pname($pid,$mysqli).'</span>';
echo '<br>From: '.$fdt.' To: '.$tdt.'<br>&nbsp;';

// Dates: Calander format to MySQL format
require_once 'foo/dateCal2Mysql.php';
$fdt = dateCal2Mysql($fdt);
$tdt = dateCal2Mysql($tdt);
// echo '<br>PID: '.$pid.' fdt: '.$fdt.' tdt: '.$tdt;

// Header
tabulateHeader();

// Initiate Variables
$GTotal_hr = 0;
$GTotal_mn = 0;

// Loop through stageX and generate Row HTML
for ($i = 0;$i < $co_stageX; $i++) {

    $serialno = $i + 1;
    $GTotalX = tabulateStage($pid, $stageX[$i]["id"], $stageX[$i]["stage"], $teamX, $fdt, $tdt, $serialno, $mysqli);

    $GTotal_hr = $GTotal_hr + $GTotalX["hr"];
    $GTotal_mn = $GTotal_mn + $GTotalX["mn"];

}

// Footer: Grand Total
tabulateFooter($GTotal_hr,$GTotal_mn);
?>


