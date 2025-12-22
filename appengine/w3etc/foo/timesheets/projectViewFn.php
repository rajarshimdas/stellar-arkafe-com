<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 28-Jun-2012                               |
| Updated On:                           		|
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| getProjectManager					|
+-------------------------------------------------------+
*/
require 'foo/getProjectManager.php';

/*
+-------------------------------------------------------+
| timeEstimateFlag					|
+-------------------------------------------------------+
*/
function timeEstimateFlag($pid, $mysqli)
{

    $foundFlag = 0;

    $query = 'select 1 from timeestimateversion where project_id = ' . $pid;

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            $foundFlag = $row[0];
        }
        $result->close();
    }

    return $foundFlag;
}


/*
+-------------------------------------------------------+
| countDelivaryList					|
+-------------------------------------------------------+
*/
function countItems($pid, $mysqli)
{

    $row_cnt = 0;

    $query = "select 1 from dwglist where project_id = $pid and active = 1";
    if ($result = $mysqli->query($query)) {
        $row_cnt = $result->num_rows;
        $result->close();
    }

    return $row_cnt;
}


/*
+-------------------------------------------------------+
| countGFCs                                             |
+-------------------------------------------------------+
*/
function countGFCs($pid, $mysqli)
{

    $row_cnt = 0;

    $query = "select 1 from dwglist where project_id = $pid and r0issuedflag = 1 and active = 1";
    if ($result = $mysqli->query($query)) {
        $row_cnt = $result->num_rows;
        $result->close();
    }

    return $row_cnt;
}

/*
+-------------------------------------------------------+
| accruedCost                                           |
+-------------------------------------------------------+
| Returns array                                         |
| rX    [0] Total Cost                                  |
|       [1] Array with accrued cost on each stage       |
+-------------------------------------------------------+
*/
require_once 'foo/getHRGroupArray.php';
require_once 'foo/getStageArray.php';
require_once 'foo/timesheets/getManhourRate.php';

function accruedCost($pid, $ver, $startDate, $endDate, $mysqli)
{

    // Note: For current Version $ver = 'current'

    // Initialize the Variables
    $totalAccruedCost = 0;

    // Get Stage array
    $stageX = getStageArray($mysqli);

    // Get HRGroup array
    $hrgroupX = getHRgroupArray($mysqli);

    // Get Manhour Rates
    $rateX = getManhourRate($hrgroupX, $pid, $ver, $mysqli);

    // Loop: Stage
    for ($i = 0; $i < count($stageX); $i++) {

        $stageId = ($stageX[$i]["id"] + 0);

        // Variable Initiation
        $stageAccruedCost           = 0;

        // Get the Timesheet array for the given date range
        $query = 'select
                    t1.no_of_hours,
                    t1.no_of_min,
                    t2.userhrgroup_id
                from
                    timesheet as t1,
                    users_a as t2
                where
                    project_id = ' . $pid . ' and
                    projectstage_id = ' . $stageId . ' and
                    quality < 1 and
                    active = 1 and
                    dt >= "' . $startDate . '" and
                    dt <= "' . $endDate . '" and
                    t1.user_id = t2.user_id';


        /* Testing
        echo '<br>Q: '.$query;
        if ($result = $mysqli->query($query)) {
            $row_cnt = $result->num_rows;
            printf("<br>Result set has %d rows.\n", $row_cnt);
            $result->close();
        }
        */

        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {
                $timeX[] = array("h" => $row[0], "m" => $row[1], "hrgroupId" => $row[2]);
            }

            $result->close();
        }

        // Loop timeX and multiply with manhour rates
        $totalH = 0;
        $totalM = 0;

        for ($e = 0; $e < count($timeX); $e++) {

            $this_hrgroupId     = $timeX[$e]["hrgroupId"];
            $rate               = $rateX[$this_hrgroupId];

            $stageAccruedCost = ($stageAccruedCost + (($timeX[$e]["h"] * $rate) + (($timeX[$e]["m"] / 60) * $rate)));
        }

        // Add to the cumulative total
        $totalAccruedCost = $totalAccruedCost + $stageAccruedCost;

        // Resultset
        $accruedExpenseX[$stageX[$i]["id"]] = array("stageExpn" => $stageAccruedCost, "cumulativeExp" => $totalAccruedCost);

        // Test: Stages
        // echo '<br>StageNo: '.$stageX[$i]["stageno"].' This Stage: '.$totalhrgroupAccruedCost.' Total Cost: '.$totalAccruedCost;
        //
        // Purge the time array for next stage loop
        unset($timeX);
    }



    $rX[0] = $totalAccruedCost; // Grand Total Accrued Cost till date.
    $rX[1] = $accruedExpenseX;  // Stagewise costs array, if required.

    return $rX;
}


/*
+-------------------------------------------------------+
| timeEstimateFlag					                    |
+-------------------------------------------------------+
*/
function activeProjectStage($pid, $mysqli)
{

    $query = "select
                t1.currentstage_id,
                t2.name,
                t2.stageno
            from
                projects as t1,
                projectstage as t2
            where
                t1.currentstage_id = t2.id and
                t1.id = " . $pid;

    // echo "Q: ".$query;

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $projectStageX = array("stageId" => $row[0], "stage" => $row[1], "stageno" => $row[2]);
        $result->close();
    }

    // echo $row[0].'. '.$row[1];

    return $projectStageX;
}
