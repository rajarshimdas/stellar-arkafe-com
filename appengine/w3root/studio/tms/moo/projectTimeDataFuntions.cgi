<?php /* Timesheets Module
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 17-Feb-2011               		        |
| Updated On:                                           |
+-------------------------------------------------------+
| Available Functions                                   |
| 1. projectTimeData    Gets project time data for a    |
|                       specified time duration         |
| 2. tabulateHeader     Header row                      |
| 3. tabulateRow4Day    Data row for a particular day   |
+-------------------------------------------------------+
*/

function projectTimeData($thisProjID, $from, $to, $mysqli)
{

    // Get timesheet entries for last week
    $query = "select
                t1.id as timesheet_id,
                t1.dt as date,
                t2.projectname,
                t2.jobcode,
                t1.user_id,
                t3.loginname,
                t3.fullname,
                t1.task_id,
                t4.name as task,
                t1.no_of_hours,
                t1.no_of_min,
                t1.work,
                t1.approved,
                t1.quality,
                t1.worked_from,
                t5.name as projectstage

            from
                timesheet as t1,
                projects as t2,
                users as t3,
                timesheettasks as t4,
                projectstage as t5

            where
                t1.project_id = $thisProjID and
                t1.project_id = t2.id and
                t1.user_id = t3.id and
                t1.task_id = t4.id and
                t1.dt >= STR_TO_DATE('$from','%d-%b-%Y') and
                t1.dt <= STR_TO_DATE('$to','%d-%b-%Y') and
                t1.projectstage_id = t5.id and
                t1.active = 1";

    //echo "Q2: $query<br>";


    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            $tmX[] = array(
                "id"            => $row[0],
                "date"          => $row[1],
                "projectname"   => $row[2],
                "jobcode"       => $row[3],
                "user_id"       => $row[4],
                "loginname"     => $row[5],
                "fullname"      => $row[6],
                "task_id"       => $row[7],
                "task"          => $row[8],
                "no_of_hours"   => $row[9],
                "no_of_min"     => $row[10],
                "work"          => $row[11],
                "approved"      => $row[12],
                "quality"       => $row[13],
                "worked_from"   => $row[14],
                "projectstage"  => $row[15]
            );
        }

        $result->close();
    }

    return $tmX;
}
