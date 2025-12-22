<?php /*
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
            roleinproject as t1,
            projects as t2
        where 
            t1.user_id = '.$userid.' and 
            t1.roles_id < 14 and 
            t1.project_id = t2.id and
            t2.domain_id = 2';

if ($result = $mysqli->query($query)) {
    $row_cnt = $result->num_rows;
    $result->close();
}

// Display the Manage Button if the member is a project manager on any project
if ($row_cnt > 0) {
    echo '<a class="button" href="go2/tmmanage.cgi">Manage</a>';
}
