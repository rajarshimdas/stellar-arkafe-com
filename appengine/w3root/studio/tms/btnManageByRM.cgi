<?php /* Reprting Manager
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 02-Feb-12					                |
| Updated On: 23-Jan-24                                 |
+-------------------------------------------------------+
*/

$row_cnt = 0;

$query = 'select 
            1 
        from 
            users_a
        where 
            reports_to_user_id = '.$userid;

// echo $query;

if ($result = $mysqli->query($query)) {
    $row_cnt = $result->num_rows;
    $result->close();
}

// Display the Manage Button if the member is a reporting manager of any employee
if ($row_cnt > 0) {
    echo '<a class="button" href="tms/timesheets.cgi?a=team">Manage</a>';
}
