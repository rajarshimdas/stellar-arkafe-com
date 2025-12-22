<?php

/*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jan-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$dxProjId   = $_POST['dxProjId'];
$dxCal1     = $_POST['dxCal1'];
$dxCal2     = $_POST['dxCal2'];

// echo "<div>dxProjId: " . $dxProjId . " | dxCal1: " . $dxCal1 . " | dxCal2: " . $dxCal2 . "</div>";

// Unix timestamp
$ts1 = strtotime($dxCal1);
$ts2 = strtotime($dxCal2);

// To date
$to_date_ts = ($ts1 < $ts2) ? $ts2 : $ts1;
$to_date = date("Y-m-d", $to_date_ts);

$datediff = abs($ts1 - $ts2);
$no_of_days = round($datediff / (60 * 60 * 24));

// echo "To date timestamp: ".$to_date_ts." | To date: ".$to_date." | No of days: ".$no_of_days;

// Caveats 
$this_userid = $userid;
/*
+-------------------------------------------------------+
| Generate Page                                         |
+-------------------------------------------------------+
*/
$moduleName = "Timesheets";
$view = "Module/mis/viewGetMyTimesheets";

require_once BD . "View/Module/mis/Template/generatePage.php";
