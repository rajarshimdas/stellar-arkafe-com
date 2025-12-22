<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 17-Aug-2009				|
| Updated On: 16-Aug-2101				|
+-------------------------------------------------------+
| Timesheet :: Get timesheet data from timesheet_id    	|
+-------------------------------------------------------+
*/

$query = "select
                t2.projectname,
                DATE_FORMAT(t1.dt,'%d-%b-%y'),
                t1.task_id,
                t1.no_of_hours,
                t1.no_of_min,
                t1.work,
                t1.approved,
                t3.`name` as task,
                t1.project_id,
                t1.worked_from,
                t1.subtask,
                t1.projectstage_id,
                t4.`name` as stage,
                t5.`name`,
                t1.`percent`,
                t6.`scope`,
                t6.`description`,
                t6.`id` as scope_id

        from
                timesheet as t1,
                projects as t2,
                timesheettasks as t3,
                projectstage as t4,
                department as t5,
                projectscope as t6

        where
                t1.id = $tsid and
                t1.user_id = $user_id and
                t1.project_id = t2.id and
                t1.task_id = t3.id and
                t1.projectstage_id = t4.id and
                t1.department_id = t5.id and
                t1.projectscope_id = t6.id";

//echo "Q1: $query<br>";



if ($result = $mysqli->query($query)) {

    $row = $result->fetch_row();

    $timesheetX["projectname"]          = $row[0];
    $timesheetX["date"]                 = $row[1];
    $timesheetX["task_id"]              = $row[2];
    $timesheetX["no_of_hours"]          = $row[3];
    $timesheetX["no_of_min"]            = $row[4];
    $timesheetX["work"]                 = $row[5];
    $timesheetX["approved"]             = $row[6];
    $timesheetX["task"]                 = $row[7];
    $timesheetX["projectid"]            = $row[8];
    $timesheetX["worked_from"]          = $row[9];
    $timesheetX["subtask"]              = $row[10];     // Subtask added on 18-Jun-2010
    $timesheetX["projectstage_id"]      = $row[11];     // 23-Jun-2010
    $timesheetX["projectstage_name"]    = $row[12];
    $timesheetX["dept_name"]            = $row[13];
    $timesheetX["percent"]              = $row[14];
    $timesheetX["scope"]                = $row[15];     // Project scope added on 08-Feb-24
    $timesheetX["scope_name"]           = $row[16];
    $timesheetX["scope_id"]             = $row[17];

    $result->close();

} else {

    echo "Q1a: error<br>";
    printf("Error: %s\n", $mysqli->error);

}
