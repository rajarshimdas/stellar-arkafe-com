<?php

/*
  +---------------------------------------------------------+
  | Rajarshi Das                                            |
  +---------------------------------------------------------+
  | Created On: 01-Feb-2012                                 |
  | Updated On:                                             |
  +---------------------------------------------------------+
 */

require_once 'foo/uid2displayname.php';
require_once $virtualFolderPath . '/w3etc/foo/timesheets/timesheetTabulate.php';

$uid = $_GET["tid"];
$fdt = dateCal2Mysql($_GET["fdt3"]);
$tdt = dateCal2Mysql($_GET["tdt3"]);

// UserId to Name
echo '<span style="font-size:125%">' . uid2displayname($uid, $mysqli) . '</span>';

// Date Range
echo '<br>From: ' . $_GET["fdt3"] . ' To: ' . $_GET['tdt3'];

// Tabulate
$daysOffX = getHolidayList($branch_name, $pathInclude);
$T = new timesheetTabulate($uid, '1000', 'N', 'N', $daysOffX, $virtualFolderPath, $mysqli);

$T->generateHeaderRow();

// Date Range

$fdtX = explode("-", $fdt);
$tdtX = explode("-", $tdt);

$fdtTS = mktime(0, 0, 0, $fdtX[1], $fdtX[2], $fdtX[0]);
$tdtTS = mktime(0, 0, 0, $tdtX[1], $tdtX[2], $tdtX[0]);
$datediff = $tdtTS - $fdtTS;
$noOfDays = floor($datediff / (60 * 60 * 24));
// echo '<br>noOfDays: '.$noOfDays;

for ($e = 0; $e <= $noOfDays; $e++){
    //echo '<br>'.$e.'. '.date("Y-m-d", strtotime('+'.$e.' day', $fdtTS)); 
    $date = date("Y-m-d", strtotime('+'.$e.' day', $fdtTS));
    $T->generateDataRow($date);            
}

?>
