<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 03-Jul-2012       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/

require $w3etc . '/foo/dateMysql2Cal.php';

// $signOffDate = date("Y-m-d");
$signOffDate = $_GET["sdt"];

$query = "update projects set projectstatus_id = 2, signoffdt = '$signOffDate' where id = " . $project_id;

if (!$mysqli->query($query)) {
    echo 'Error[contractSignOff]: Could not save data....';
} else {
    //echo dateMysql2Cal($signOffDate);


    echo '<td id="signOffDate" width="55%">Date: '.dateMysql2Cal($signOffDate).'</td>
            <td id="signOffButton" style="text-align: right;">
            <span class="button" onclick="javascript:contractSignOffFlag()">Edit</span>
            &nbsp;
            </td>';
}
