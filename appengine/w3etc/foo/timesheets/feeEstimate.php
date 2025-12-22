<?php /* <include>/foo/timesheets/feeEstimate.php
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 24-Jan-2012				|
| Updated On: 03-Jan-2012 (used fgetcsv)                |
+-------------------------------------------------------+
| CSS: timeTracker.css                                  |
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| Function getStageArray                           	|
+-------------------------------------------------------+
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
require_once 'foo/getStageArray.php';

/*
+-------------------------------------------------------+
| Function getHRgroupArray                           	|
+-------------------------------------------------------+
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
require_once 'foo/getHRGroupArray.php';
/*
+-------------------------------------------------------+
| Function getManhourRate                       	|
+-------------------------------------------------------+
| $hrgroupX HR Groups                                   |
| $pid      Project ID                                  |
| $ver      Version                                     |
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
require_once 'foo/timesheets/getManhourRate.php';

/*
+-------------------------------------------------------+
| Function generateHeaderRow                           	|
+-------------------------------------------------------+
| $pid      projectid                                   |
| $mysqli   database connection                         |
+-------------------------------------------------------+
*/
function generateHeaderRow($pid,$mysqli) {

    // Get the roles
    $hrgroupX = getHRgroupArray($mysqli);
    $co = count($hrgroupX);

    // Tabulate Header
    echo '<tr class="headerRow">
            <td class="headerRowCell1" rowspan="2" width="40px">Row<br>No</td>
            <td class="headerRowCell2" rowspan="2">Tasks</td>
            <td class="headerRowCell2" colspan="'.$co.'" align="center">Manhours</td>
            <td class="headerRowCell2" rowspan="2" width="60px">Total</td>
        </tr>
        <tr class="headerRow">';
    // HR Group columns
    for ($i = 0; $i < count($hrgroupX); $i++) {
        echo '<td class="headerRowCell3" width="60px">'.$hrgroupX[$i]["hrgroup"].'</td>';
    }
    echo '</tr>';

    return true;
}

/*
+-------------------------------------------------------+
| Function saveDataRow                           	|
+-------------------------------------------------------+
| $pid      projectid                                   |
| $fx       filename is used as a version identifier    |
| $line     csv file line containing the data           |
| $dtime    timestamp                                   |
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
function saveDataRow($pid, $fx, $line, $dtime, $hrgroupX, $mysqli) {

    // Template Version 1.1
    //

    $dataflag   = 0;
    $cell       = $line; // Line contains array of cells - See fgetcsv

    $stage_id   = $cell[2];
    $task_id    = $cell[3];

    $query = 'insert into
                timeestimate (project_id, stage_id, task_id, hrgroup_id, manhours, version, dtime)
            values ';

    // Counter
    $co = 0;

    // Template file column 7 to 18 contain the time estimates
    for ($i = 7; $i < 18; $i++) {

        $cellValue  = trim($cell[$i]);
        $hrgroup_id = $hrgroupX[$co]["id"];

        $co++;

        if ($cellValue != '' && $cellValue > 0) {

            if ($dataflag < 1) {
                $query = $query."($pid,$stage_id,$task_id,$hrgroup_id,$cellValue,'$fx','$dtime')";
                $dataflag++;
            } else {
                $query = $query.", ($pid,$stage_id,$task_id,$hrgroup_id,$cellValue,'$fx','$dtime')";
                $dataflag++;
            }

        }

    }

    if ($dataflag > 0) {
        // echo '<br>'.$query;
        if (!$mysqli->query($query)) die ('Error: 2');
    }

    return true;

}

/*
+-------------------------------------------------------+
| Function savemanhourRate                           	|
+-------------------------------------------------------+
| $pid      projectid                                   |
| $fx       filename is used as a version identifier    |
| $line     csv file line containing the data           |
| $dtime    timestamp                                   |
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
function saveManhourRate($pid, $fx, $line, $dtime, $hrgroupX, $mysqli) {

    /* echo "fn: saveManhourRate: ".$pid." | ".$fx." | ".$f1line." | ".$dtime; */
    //$cell = explode(',', $line);
    $query = "insert into
                timeestimaterate (project_id, hrgroup_id, hrgroup_name, rateperhour, version, dtime)
            values ";

    $co = 0;

    // See the Template for understanding on the $e value.
    for ($e = 7; $e < 18; $e++) {

        $rateperhour    = $line[$e];
        $hrgroup_id     = $hrgroupX[$co]["id"];
        $hrgroup_name   = $hrgroupX[$co]["nm"];

        if ($e < 8) {
            // First
            $query = $query."($pid, $hrgroup_id, '$hrgroup_name', $rateperhour, '$fx', '$dtime')";
        } else {
            // Subsequent
            $query = $query.",($pid, $hrgroup_id, '$hrgroup_name', $rateperhour, '$fx', '$dtime')";
        }

        $co++;
    }

    //die('<br>Q: '.$query.'<br>') ;
    
    if (!$mysqli->query($query)) {
        echo 'Error[5]: saveManhourRate.';
        return FALSE;
    }

    return TRUE;

}

/*
+-------------------------------------------------------+
| Function stageTabulate                           	|
+-------------------------------------------------------+
*/
function stageTabulate($pid,$stage_id,$stage_no,$stage_nm,$hrgroupX,$ver,$mysqli) {

    // Stage Header
    echo '<tr>
        <!-- Stage_id:'.$stage_id.'. '.$stage_nm.' -->
        <td class="stageRow" colspan="14">&nbsp;'.$stage_no.'. '.$stage_nm.'&nbsp;</td></tr>';

    // Get the tasks array (DepartmentId 1 is NA and has a 1to1 stage tasks map.)
    $query = 'select
                t1.timesheettask_id as task_id,
                t2.name as task
            from
                projectstagetasks as t1,
                timesheettasks as t2
            where
                t1.timesheettask_id = t2.id and
                t1.projectstage_id = '.$stage_id.' and
                t1.department_id = 1
            order by
                displayorder';

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $taskX[] = array("id" => $row[0], "task" => $row[1]);
        }

        $result->close();
    }

    /*
    +-------------------------------------------------------+
    | Loop :: Tasks                                         |
    +-------------------------------------------------------+
    */
    // Column Totals for each Role. Variable initiation (11 required as of now)
    $esX    = array (0,0,0,0,0,0,0,0,0,0,0);  // Estimated Manhours
    $tmXH   = array (0,0,0,0,0,0,0,0,0,0,0);  // Timesheet Hours
    $tmXM   = array (0,0,0,0,0,0,0,0,0,0,0);  // Timesheet Minutes

    $rowno = 1;
    $co = count($taskX);

    // Loop one-by-one for all tasks in this stage
    for ($e = 0; $e < $co; $e++) {

        $task_id = $taskX[$e]["id"];

        echo '
            <!-- ['.$stage_id.'] '.$task_id.'. '.$taskX[$e]["task"].' -->
            <tr><td class="dataRowCell1">'.$rowno++.'</td><td class="dataRowCell2" style="text-align:left;">'.$taskX[$e]["task"].'</td>';

        $X = taskTimeTabulate($pid, $stage_id, $task_id, $hrgroupX, $ver, $mysqli);

        // Update the column total - Estimated Manhours
        $this_role_est_total    = 0;
        $this_role_tmx_total    = 0;
        $this_esX               = $X["esX"];
        $this_tmXH              = $X["tmXH"];
        $this_tmXM              = $X["tmXM"];

        for ($x = 0; $x < 11; $x++) {
            $esX[$x]    = $esX[$x] + $this_esX[$x];
            $tmXH[$x]   = $tmXH[$x] + $this_tmXH[$x];
            $tmXM[$x]   = $tmXM[$x] + $this_tmXM[$x];
        }
    }

    /*
    +-------------------------------------------------------+
    | Totals Row                                            |
    +-------------------------------------------------------+
    */
    $RT_esX     = 0;    // Row Total Estimated Manhours
    $RT_tmXH    = 0;    // Row Total Timesheet Hours
    $RT_tmXM    = 0;    // Row Total Timesheet Minutes

    // Get the Manhour Rates for each Role
    $rateX = getManhourRate ($hrgroupX, $pid, $ver, $mysqli);
    $cost_esX   = 0;
    $cost_tmX   = 0;

    // Sr No and Tasks Cells
    echo '<tr style="background: RGB(220,220,220)"><td class="dataRowCell1">&nbsp;</td><td class="dataRowCell2" style="text-align:left;font-weight:bold;">Total</td>';

    // Get HRGroup array
    $hrgroupX = getHRGroupArray($mysqli);

    // Loop for all hrgroups
    for ($i = 0; $i < count($hrgroupX); $i++) {

        $hrgroup_id = $hrgroupX[$i]["id"];

        // Color Formatting
        $cx = 'green';
        $estimateH      = ($esX[$i] + 0);   // if string, convert to numerical data
        $timesheetH     = ($tmXH[$i] + 0);  // string to numerical

        if ($timesheetH > $estimateH) {
            $cx = 'red';
        }

        // Display Row
        echo '<td class="dataRowCell2"><span style="color:blue;font-weight:bold">'.$esX[$i].'</span>
            <br><span style="color:'.$cx.';font-weight:bold">'.timeAdd($tmXH[$i], $tmXM[$i]).'</span></td>';

        // Store in Totals
        $RT_esX     = $RT_esX + $esX[$i];
        $RT_tmXH    = $RT_tmXH + $tmXH[$i];
        $RT_tmXM    = $RT_tmXM + $tmXM[$i];

        // Store in cost Totals
        // Estimated Manhours
        $this_cost_esX      = $rateX[$hrgroup_id] * $esX[$i];
        $cost_esX           = $cost_esX + $this_cost_esX;

        // Timesheets
        $this_cost_tmXH     = $rateX[$hrgroup_id] * $tmXH[$i];
        $this_cost_tmXM     = $rateX[$hrgroup_id] * ($tmXM[$i] / 60);
        $cost_tmX           = $cost_tmX + ($this_cost_tmXH + $this_cost_tmXM);


    }
    // Grand totals
    echo '<td class="dataRowCell2" style="font-weight:bold">'.$RT_esX.'<br>'.timeAdd($RT_tmXH, $RT_tmXM).'</td></tr>';

    /*
    +-------------------------------------------------------+
    | Totals Costs Row                                      |
    +-------------------------------------------------------+
    */

    echo '<tr style="background:RGB(180,180,180)"><td class="dataRowCell1">&nbsp;</td><td class="dataRowCell2" style="text-align:left;font-weight:bold;" colspan="12">Cost</td>';
    // Total Cost
    $cx = 'green';
    if ($cost_esX < $cost_tmX) {
        $cx = 'red';
    }
    echo '<td class="dataRowCell2"><span style="color:blue;font-weight:bold">'.$cost_esX.'</span><br><span style="color:'.$cx.';font-weight:bold">'.$cost_tmX.'</td>';

}

/*
+-------------------------------------------------------+
| Sub-Function taskTimeTabulate                       	|
+-------------------------------------------------------+
| $pid      project id                                  |
| $stage_id Stage Id
| $task_id  Task Id for which row is to be generated    |
| $hrgroupX HR Groups                                   |
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
function taskTimeTabulate($pid,$stage_id,$task_id,$hrgroupX,$ver,$mysqli) {

    // Variable Initialization
    $total_budgeted_hr      = 0;
    $total_timesheet_hr     = 0;
    $total_timesheet_mn     = 0;

    /*
    +-------------------------------------------------------+
    | Time Estimate Array                                   |
    +-------------------------------------------------------+
    */
    // Get the timeestimates for this task
    $query = 'select
                manhours, hrgroup_id
            from
                timeestimate
            where
                project_id = '.$pid.' and
                stage_id = '.$stage_id.' and
                task_id = '.$task_id.' and
                version = "'.$ver.'"';

    //echo "<br>Q: ".$query;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $timeX[] = array("mh" => $row[0], "hid" => $row[1]);
        }

        $result->close();
    }

    $co_timeX = count($timeX);
    //echo '<br>co_timeX: '.$co_timeX;
    /*
    +-------------------------------------------------------+
    | Timesheet Array                                       |
    +-------------------------------------------------------+
    */
    $query = 'select
                t1.no_of_hours,
                t1.no_of_min,
                t2.userhrgroup_id
            from
                timesheet as t1,
                users_a as t2
            where
                t1.project_id = '.$pid.' and
                t1.projectstage_id = '.$stage_id.' and
                t1.task_id = '.$task_id.' and
                t1.active = 1 and
                t1.quality = 0 and
                t1.user_id = t2.user_id';
    //echo "<br>Q2: ".$query;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $tmsheetX[] = array("h" => $row[0], "m" => $row[1], "hid" => $row[2]);
        }

        $result->close();
    }

    $co_tmsheetX = count($tmsheetX);
    //echo '<br>co_tmsheetX: '.$co_tmsheetX;

    /*
    +-------------------------------------------------------+
    | Loop :: userhrgroup                                   |
    +-------------------------------------------------------+
    */
    $esX    = array (0,0,0,0,0,0,0,0,0,0,0); // Estimate Total
    $tmXH   = array (0,0,0,0,0,0,0,0,0,0,0); // Timesheet Total Hours
    $tmXM   = array (0,0,0,0,0,0,0,0,0,0,0); // Timesheet Total Minutes

    for ($x = 0; $x < count($hrgroupX); $x++) {

        $hid        = ($hrgroupX[$x]["id"] + 0); // Convert string to integer
        $hrgroup    = $hrgroupX[$x]["hrgroup"];

        // Estimate
        $manhours   = 0; // Reset

        /*
        +-------------------+
        |  Time Estimate    |
        +-------------------+
        */
        for ($a = 0; $a < $co_timeX; $a++) {

            $this_hid = $timeX[$a]["hid"];

            if ($this_hid == $hid) {
                $mh = $timeX[$a]["mh"];
                $manhours = $manhours + $mh;
                $total_budgeted_hr = $total_budgeted_hr + $mh;
            }

        }

        // Update the estimate role array
        $esX[$x] = $manhours;

        if ($manhours < 1) {
            $manhours = '&nbsp;';
        }

        /*
        +-------------------+
        |  Timesheets       |
        +-------------------+
        */
        $this_h = 0;
        $this_m = 0;

        for ($e = 0; $e < count($tmsheetX); $e++) {

            $this_tmsheetX_hid = ($tmsheetX[$e]["hid"] + 0);

            if ($hid === $this_tmsheetX_hid) {
                $this_h = $this_h + $tmsheetX[$e]["h"];
                $this_m = $this_m + $tmsheetX[$e]["m"];

            }

        }

        // Totals Row Variables
        $tmXH[$x] = $this_h;
        $tmXM[$x] = $this_m;

        // Formatting
        $ts_display = timeAdd($this_h, $this_m);
        if ($ts_display === '0:0') {
            $ts_display = '&nbsp;';
        } else {
            $tmX[$x] = $ts_display;
        }

        if ($this_h >= $manhours) {
            $color = 'Red';
        } else {
            $color = 'Green';
        }

        echo '<td class="dataRowCell2"><span style="color:Blue;">'.$manhours.'</span><br><span style="color:'.$color.'">'.$ts_display.'</span></td>';

    }

    /*
    +-------------------------------------------------------+
    | Total Column                                          |
    +-------------------------------------------------------+
    */
    if ($total_timesheet_hr >= $total_budgeted_hr) {
        $co = 'Red';
    } else {
        $co = 'Green';
    }

    // Format Time estimate
    if ($total_budgeted_hr < 1) {
        $est_display = '&nbsp;';
    } else {
        $est_display = '<span style="color:blue">'.$total_budgeted_hr.'</span>';
    }

    // Format Timesheet
    if ($total_timesheet_hr < 1 && $total_timesheet_mn < 1) {
        $ts2_display = '&nbsp;';
    } else {
        $ts2_display = '<span style="color:'.$co.'">'.$total_timesheet_hr.':'.$total_timesheet_mn.'</span>';
    }

    echo '<td class="dataRowCell2" style="background: RGB(220,220,220);font-weight:bold">'.$est_display.'<br>'.$ts2_display.'</td>';

    // Function return value
    $X = array ("esX" => $esX, "tmXH" => $tmXH, "tmXM" => $tmXM);
    return $X;

}

/*
+-------------------------------------------------------+
| Function stageEstimatedCost                         	|
+-------------------------------------------------------+
*/
function stageAccruedCost ($pid, $stageId, $mysqli) {

    $totalCost = 0;

    $hrgroupX   = getHRGroupArray ($mysqli);
    $rateX      = getManhourRate ($hrgroupX, $pid, $ver, $mysqli);

    $query = "select
                t1.no_of_hours,
                t1.no_of_min,
                t2.userhrgroup_id
            from
                timesheet as t1,
                users_a as t2
            where
                t1.project_id       = $pid and
                t1.projectstage_id  = $stageId and
                t1.quality          < 1 and
                t1.active           = 1 and
                t1.user_id          = t2.user_id";

    //echo 'Q: '.$query;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $timeX[] = array ("h" => $row[0], "m" => $row[1], "userhrgroup_id" => $row[2]);
        }

        $result->close();
    }

    // Calculate costs
    $co_timeX = count($timeX);

    for ($i = 0; $i < $co_timeX; $i++){
        
        $userhrgroup_id     = $timeX[$i]["userhrgroup_id"];
        $rate               = $rateX[$userhrgroup_id];

        $costH = $timeX[$i]["h"] * $rate;
        $costM = $timeX[$i]["m"] * ($rate / 60);

        $totalCost = $totalCost + $costH + $costM;

    }

    return $totalCost;
    
}

/*
+-------------------------------------------------------+
| Function stageEstimatedCost                         	|
+-------------------------------------------------------+
*/
function stageEstimatedCost ($pid, $stageId, $mysqli) {

    $totalCost = 0;

    $hrgroupX   = getHRGroupArray ($mysqli);
    $ver        = 'current';
    $rateX      = getManhourRate ($hrgroupX, $pid, $ver, $mysqli);

    $query = "select
                manhours,
                hrgroup_id
            from
                timeestimate
            where
                project_id  = $pid and
                stage_id    = $stageId and
                active      = 1";

    //echo 'Q: '.$query.'<br>';

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $timeX[] = array ("h" => $row[0], "userhrgroup_id" => $row[1]);
        }

        $result->close();
    }

    // Calculate costs
    $co_timeX = count($timeX);

    for ($i = 0; $i < $co_timeX; $i++){

        $userhrgroup_id     = $timeX[$i]["userhrgroup_id"];
        $rate               = $rateX[$userhrgroup_id];

        $costH = $timeX[$i]["h"] * $rate;        

        // Add to total Cost
        $totalCost = $totalCost + $costH;

    }

    return $totalCost;

}
?>
