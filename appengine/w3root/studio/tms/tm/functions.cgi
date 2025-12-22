<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 06-Feb-12					                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
function id2dataX($tsid, $mysqli)
{
    return ['fullname' => 'bad dragon'];
}

function id2data($tsid, $mysqli)
{

    $query = 'select
                DATE_FORMAT(t1.dt,"%d-%b-%Y") as dt,
                t1.no_of_hours,
                t1.no_of_min,
                t1.work,
                t2.fullname,
                t3.projectname,
                t4.name as stage,
                t5.name as task,
                t3.id as pid,
                t4.id as stageid,
                t5.id as taskid,
                t1.department_id as workgroupid,
                t6.name as workgroup,
                t1.percent,
                t1.worked_from,
                t3.jobcode,
                t1.user_id,
                t1.dt as dtmysql
            from
                timesheet as t1,
                users as t2,
                projects as t3,
                projectstage as t4,
                timesheettasks as t5,
                department as t6
            where
                t1.id = ' . $tsid . ' and
                t1.user_id = t2.id and
                t1.project_id = t3.id and
                t1.projectstage_id = t4.id and
                t1.task_id = t5.id and
                t1.department_id = t6.id';

    // echo "<br>Q: ".$query;
    // return ['fullname' => $query ];


    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $a = array(
            "date"          => $row[0],
            "hour"          => $row[1],
            "min"           => $row[2],
            "work"          => $row[3],
            "fullname"      => $row[4],
            "projectname"   => $row[5],
            "stage"         => $row[6],
            "task"          => $row[7],
            "projectid"     => $row[8],
            "stageid"       => $row[9],
            "taskid"        => $row[10],
            "workgroupid"   => $row[11],
            "workgroup"     => $row[12],
            "percent"       => $row[13],
            "jobcode"       => $row[15],
            "user_id"       => $row[16],
            "dt"            => $row[17],
        );

        $wf = [
            '10' => "Office",
            '20' => "Remote Location",
            '30' => "Leave"
        ];

        $a['worked_from'] = $wf[$row[14]];

        $result->close();
    }

    return $a;
}

function checkRoleInProject($pid, $pm_roles_id, $user_id, $mysqli)
{
    $flag   = 0;
    $query  = "select
                1
            from 
                roleinproject
            where
                project_id = $pid and
                roles_id < $pm_roles_id and
                user_id = $user_id";

    if ($result = $mysqli->query($query)) {
        $row = $result->fetch_row();
        $flag = $row[0];
        $result->close();
    }

    if ($flag > 0) {
        return true;
    } else {
        return false;
    }
}
