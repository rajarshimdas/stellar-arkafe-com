<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 01-Feb-2012				|
| Updated On:           				|
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';
require_once 'bootstrap.php';
require_once 'foo/pid2pname.php';
require_once 'foo/uid2displayname.php';
require_once 'foo/sid2stagename.php';
require_once '../studio/tms/hot7e/getHolidayList.php';
require_once '../studio/tms/hot7e/getHolidayBackground.php';
require_once '../studio/tms/hot7e/getTimesheetDataForThisTeammate.cgi';

echo "<link href='/matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>";


$thisUID    = $_GET["tid"];
$mCal       = $_GET["mCal"];


/* Variables
 * ----------------------
 * $display_no_of_days
 * $HideIOdata   
 * $this_userid
 * 
 * 
*/



$display_no_of_days     = 31;
$HideIOdata             = "No";
$this_userid            = $thisUID;


echo '<div id="body" style="font-size:100%">';
echo uid2displayname($thisUID,$mysqli);

$comboWorkedFrom = '../studio/tms/hot7e/comboWorkedFrom.txt';

include '../studio/tms/hot7e/tabulate.cgi';

echo '</div>';
?>
