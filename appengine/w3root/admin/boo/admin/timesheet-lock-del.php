<?php

$userid = $_GET["tmId"];
$mysqliSuperUser = cn2();

include 'foo/timesheets/timesheetLockDt.php';
$tx = new timesheetLockDt($userid, $holidayListFile, $mysqliSuperUser);
if ($tx->removeOverride2LockDt()){
    echo 'T';
} else {
    echo 'F';
}

?>