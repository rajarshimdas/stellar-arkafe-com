<?php
$thisUId = $_POST['thisUId'];
$thisDt = $_POST['thisDt'];
/*
+-------------------------------------------------------+
| Update Database        				                |
+-------------------------------------------------------+
*/
$mysqli = cn2();
$query = "update 
            timesheet 
        set 
            approved = 1, 
            quality = 0,
            pm_review_flag = 1
        where 
            user_id = $thisUId and
            dt = '$thisDt' and
            active > 0 and
            quality < 1";

$rx = $mysqli->query($query) ? "T" : "F";

$mysqli->close();
/*
+-------------------------------------------------------+
| Send JSON Response                                    |
+-------------------------------------------------------+
*/
rdReturnJsonHttpResponse('200', [$rx]);
