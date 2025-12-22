<?php

/*
+-------------------------------------------------------+
| getProjectTimesheets                                  |
+-------------------------------------------------------+
*/
function bdGetProjectTimesheets(
    int     $pid,           // Project Id
    string  $fdt,           // From date
    string  $tdt,           // To date
    object  $mysqli         // db connection
) {
    $query = "select 
                * 
            from 
                `view_timesheets` 
            where 
                `project_id` = '$pid' and 
                `quality` < 1 and
                `dtmysql` >= '$fdt' and
                `dtmysql` <= '$tdt'";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $rx[] = $row;
        };
        $result->close();
    }
    return $rx;
}
