<?php /* <include>/foo/sms/projectSchedule.php
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 03-Jul-2012				|
| Updated On:                                           |
+-------------------------------------------------------+
| */ require_once 'foo/dateCal2Mysql.php'; /*           |
| */
require_once 'foo/dateMysql2Cal.php'; /*           |
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| fn: setStageTargetDate        			|
+-------------------------------------------------------+
*/
function setStageTargetDate($pid, $stageId, $targetdt, $mysqli)
{


    $errorFlag  = 0;
    $tdt        = dateCal2Mysql($targetdt);

    $mysqli->autocommit(FALSE);

    $query = "update
                projectschedule 
            set
                active      = 0
            where
                project_id  = $pid and
                stage_id    = $stageId";
    // echo 'Q1: '.$query.'<br>';

    if (!$mysqli->query($query)) {
        $errorFlag++;
    }

    $dtime = date("Y-m-d H:i:s");

    $query = "insert into
                projectschedule (project_id, stage_id, targetdt, dtime) 
            values
                ($pid, $stageId, '$tdt', '$dtime')";
    // echo 'Q2: '.$query.'<br>';

    if (!$mysqli->query($query)) {
        $errorFlag++;
    }

    if ($errorFlag < 1) {

        $mysqli->commit();
        return true;
    } else {

        return false;
    }
}
/*
+-------------------------------------------------------+
| fn: setCurrentProjectStage        			|
+-------------------------------------------------------+
*/
function setCurrentProjectStage($pid, $stageId, $mysqli)
{

    $query = "update
                projects
            set
                currentstage_id = $stageId
            where
                id = $pid";
    // echo 'Q: '.$query;

    if (!$mysqli->query($query)) {
        return FALSE;
    }

    return TRUE;
}

/*
+-------------------------------------------------------+
| fn: getScheduleArray                                  |
+-------------------------------------------------------+
*/
function getScheduleArray($pid, $mysqli)
{

    $query = "select
                t1.stage_id,
                t2.stageno,
                t2.name as stage,
                t1.targetdt
            from
                projectschedule as t1,
                projectstage as t2
            where
                t1.project_id = $pid and
                t1.active = 1 and
                t1.stage_id = t2.id";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            $rX[] = array(
                "stageId"       => $row[0],
                "stageNo"       => $row[1],
                "name"          => $row[2],
                "targetDt"      => $row[3],
                "tDtCalander"   => dateMysql2Cal($row[3])
            );
        }

        $result->close();
    } else {
        return FALSE;
    }

    return $rX;
}





/*
+-------------------------------------------------------+
| fn: getScheduleArray                                  |
+-------------------------------------------------------+
*/
function getStageTargetDate($pid, $stageId, $mysqli)
{

    $query = "select
                targetdt
            from
                projectschedule
            where
                project_id = $pid and
                stage_id = $stageId and
                active = 1";

    // echo 'Q: '.$query;
    $targetDate = 'X';

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $targetDate = $row[0];
        }

        $result->close();
    } else {
        return FALSE;
    }

    if ($targetDate !== 'X') {
        $tdt = dateMysql2Cal($targetDate);
    } else {
        $tdt = '&nbsp;';
    }

    return $tdt;
}

/*
+-------------------------------------------------------+
| fn: getScheduleArray                                  |
+-------------------------------------------------------+
*/
function getCurrentProjectStage($pid, $mysqli)
{

    $query = "select
                t1.currentstage_id,
                t2.name,
                t2.stageno,
                t2.sname
            from
                projects as t1,
                projectstage as t2
            where
                t1.id = $pid and
                t1.currentstage_id = t2.id";
    // echo 'Q: '.$query;

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();

        $stageId    = $row[0];
        $stage      = $row[1];
        $stageNo    = $row[2];
        $sname      = $row[3];

        $result->close();
    } else {

        return FALSE;
    }

    // Get the Target date for the current stage
    $targetdt = getStageTargetDate($pid, $stageId, $mysqli);

    $rX = array(
        "stageId"   => $stageId,
        "stage"     => $stage,
        "stageNo"   => $stageNo,
        "targetdt"  => $targetdt,
        "sname"     => $sname,
    );

    return $rX;
}

/*
+-------------------------------------------------------+
| fn: deleteTargetDate                                  |
+-------------------------------------------------------+
*/
function deleteTargetDate($pid, $stageId, $mysqli)
{

    $query = "update projectschedule set active = 0 where project_id = $pid and stage_id = $stageId";

    if (!$mysqli->query($query)) {
        return FALSE;
    } else {
        return TRUE;
    }
}

/*
+-------------------------------------------------------+
| fn: getStageNoTdtArray                                |
+-------------------------------------------------------+
*/
function getStageNoTdtArray($pid, $mysqli)
{

    $query = "select
                t1.targetdt,
                t2.stageno
            from
                projectschedule as t1,
                projectstage as t2
            where
                t1.project_id = $pid and
                t1.active = 1 and
                t1.stage_id = t2.id";


    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            $rX[$row[1]] = $row[0];
        }

        $result->close();
        return $rX;
    } else {
        return FALSE;
    }
}
