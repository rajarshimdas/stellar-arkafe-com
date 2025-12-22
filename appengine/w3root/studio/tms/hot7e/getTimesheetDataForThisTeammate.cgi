<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:                                           |
| Updated On: 14-Apr-11					                |
+-------------------------------------------------------+
| Get Timesheet data of Teammate for a specified        |
| duration (From Date - To Date)                        |
+-------------------------------------------------------+
*/

function getTimesheetDataForThisTeammate(
    $uid,           // user id
    $startDt,       // Start Date
    $endDt,         // End Date ("singleDay" for single day)
    $mysqli         // Database connection
) {

    if ($endDt === "singleDay") {
        $daterange = "dtmysql = '$startDt'";
    } else {
        $daterange = "dtmysql >= STR_TO_DATE('$startDt','%d-%b-%Y') and dtmysql <= STR_TO_DATE('$endDt','%d-%b-%Y')";
    }

    $query = "SELECT 
                * 
            FROM 
                `view_timesheets`
            where
                $daterange and
                `user_id` = $uid";
    // echo "<br>Q1: $query";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $timeX[] = $row;
        }

        $result->close();
    }

    if (isset($timeX))
        return $timeX;
    else
        return NULL;
}
