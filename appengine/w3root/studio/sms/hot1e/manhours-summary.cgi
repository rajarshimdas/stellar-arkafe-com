<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 25-Jun-10					|
| Updated On:                                           |
+-------------------------------------------------------+
*/

require_once 'bootstrap.php';
require_once 'foo/timesheets/projectView.php';
require_once 'foo/getStageArray.php';
require_once 'foo/getTeammateArray.php';
require_once 'foo/pid2pname.php';
require_once 'foo/dateCal2Mysql.php';


$mysqli = cn1();

// Variables
$pid = $_GET['pid'];
$fdt = $_GET['fdt1'];
$tdt = $_GET['tdt1'];

// Get Stage list
$stageX = getStageArray($mysqli);
$co_stageX = count($stageX);
// echo "<br>Count stageX: ".$co_stageX;

// Get Teammates list
$teamX = getTeammateArray($mysqli);
$co_teamX = count($teamX);
//echo '<br>teamX: '.$co_teamX;

?>
<link type='text/css' rel='stylesheet' href="/matchbox/themes/cool/timeTracker.css">

<div align="center" style="font-size:80%">
    <?php

    if (!$fdt || $fdt === '-- Project Starting --') {
        // echo "<br>&nbsp;<br>Error: From Date. Click on Home button and try again...";
        $fdt    = '23-Apr-04';
        $fdt2   = 'Project Starting';
    } else {
        $fdt2   = $fdt;
    }
    if (!$tdt) {
        echo "<br>&nbsp;<br>Error: To Date. Click on Home button and try again...";
    }
    // Project name and date range

    //echo '<br>&nbsp;<span style="font-size:125%;font-weight:bold;">'.pid2pname($pid,$mysqli).'</span>';
    echo '<br><span style="font-weight:bold">Date Range: '.$fdt2.' to: '.$tdt.'</span><br>&nbsp;';

    // Dates: Calander format to MySQL format
    $fdt = dateCal2Mysql($fdt);
    $tdt = dateCal2Mysql($tdt);
    // echo '<br>PID: '.$pid.' fdt: '.$fdt.' tdt: '.$tdt;

    // Table Header
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
   
</div>
