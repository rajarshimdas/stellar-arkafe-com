<?php

require 'foo/timesheets/timesheetLockDt.php';
require 'foo/dateCal2Mysql.php';
require 'foo/uid2displayname.php';

$admin_uid = $userid;
$userid = $_GET["userId"];
$templockdt = dateCal2Mysql($_GET["lockdtCal"]);
$mysqliSuperUser = cn2();
$reason = addslashes($_GET["reason"]);
$fullname = uid2displayname($userid, $mysqli);


if ($userid > 0) {

    $tslock = new timesheetLockDt($userid, $holidayListFile, $mysqliSuperUser);

    if ($tslock->setOverride2LockDt($templockdt, $admin_uid, $reason) == TRUE) {
        
        // Add row to the table
        echo '<tr class="dataRowNew" id="tmId' . $userid . '" style="background-color:white;">
                <td class="dataRowCell1">
                    &nbsp;' . $fullname . '
                </td>
                <td class="dataRowCell2" style="border-right: 0px; text-align: center;">
                    ' . $_GET["lockdtCal"] . '
                </td>
                <td class="dataRowCell2" style="text-align: right">
                    <img 
                        src="/da/icons/delete.png" 
                        alt="Delete" 
                        onClick="javascript:deleteRecord(' . $userid . ');" 
                        style="cursor:pointer"
                        title="Delete ' . $fullname . '\'s Entry."
                    >
                </td>
            </tr>';
        
    } else {
        echo 'F1';
    }
} else {

    // User was not selected
    echo 'F2';
}
?>
