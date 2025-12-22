<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 24-Jan-2012				|
| Updated On:                                           |
+-------------------------------------------------------+
*/

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
    ?>
<tr class="headerRow">
    <td class="headerRowCell" rowspan="2" width="40px">Row<br>No</td>
    <td class="headerRowCell1" rowspan="2">Tasks</td>
    <td class="headerRowCell1" colspan="<?php echo $co; ?>" align="center" width="660px">Manhours</td>
    <td class="headerRowCell1" rowspan="2" width="60px">Total</td>
</tr>
<tr class="headerRow">
        <?php
        for ($i = 0; $i < count($hrgroupX); $i++) {
            echo '<td class="headerRowCell2">'.$hrgroupX[$i]["hrgroup"].'</td>';
        }
        ?>
</tr>
    <?php
    return true;
}
/*
+-------------------------------------------------------+
| Function generateDataRow                           	|
+-------------------------------------------------------+
| $pid      projectid                                   |
| $line     csv file line containing the data           |
| $rowno    row number of excel file                    |
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
function generateDataRow($pid,$line,$rowno,$mysqli) {

    // Cell
    $cell = explode(",",$line);

    // Get task
    $query = 'select `name` from timesheettasks where id = '.$cell[2];

    if ($result = $mysqli->query($query)) {
        $row = $result->fetch_row();
        $task = $row[0];
        $result->close();
    }

    // Testing
    if ($cell[0] === '#') {
        ?>
<tr>
    <td class="dataRowCell1"><?php echo $rowno; ?></td>
    <td class="dataRowCell2"><?php echo $task; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[3]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[4]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[5]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[6]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[7]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[8]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[9]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[10]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[11]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[12]; ?></td>
    <td class="dataRowCell2"><?php echo '&nbsp;'.$cell[13]; ?></td>
    <td class="dataRowCell2">T</td>
</tr>
        <?php
    }

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
function saveDataRow($pid,$fx,$line,$dtime,$hrgroupX,$mysqli) {

    // echo '<br>dtime: '.$dtime." | ".$line;
    $dataflag   = 0;
    $cell       = explode(',',$line);
    $stage_id   = $cell[1];
    $task_id    = $cell[2];

    $query = 'insert into timeestimate (project_id, stage_id, task_id, hrgroup_id, manhours, version, dtime) values ';

    for ($i = 3; $i <= 13; $i++) {

        $cellValue = trim($cell[$i]);
        $hrgroup_id = $hrgroupX[$i]["id"];

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

    $cell = explode(',', $line);
    $query = "insert into timeestimaterate (project_id, hrgroup_id, hrgroup_name, rateperhour, version, dtime) values ";

    for ($e = 3; $e <= 13; $e++) {

        $rateperhour    = $cell[$e];
        $hrgroup_id     = $hrgroupX[$e]["id"];
        $hrgroup_name   = $hrgroupX[$e]["nm"];

        if ($e <= 3) {
            $query = $query." ($pid, $hrgroup_id, '$hrgroup_name', $rateperhour, '$fx', '$dtime')";
        } else {
            $query = $query.", ($pid, $hrgroup_id, '$hrgroup_name', $rateperhour, '$fx', '$dtime')";
        }

    }

    //echo '<br>Q: '.$query;
    if (!$mysqli->query($query)) die ('Error: 5');
}

/*
+-------------------------------------------------------+
| Function getStageArray                           	|
+-------------------------------------------------------+
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
function getStageArray($mysqli) {

    $query = 'select `id`,`name`,`stageno`,`sname` from projectstage where active = 1 order by stageno';

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            $stageX[] = array(

                "id" => $row[0],
                "stage" => $row[1],
                "stageno" => $row[2],
                "sname" =>$row[3],

            );
        }
        $result->close();
    }
    //var_dump($stageX);

    return $stageX;
}
/*
+-------------------------------------------------------+
| Function getHRgroupArray                           	|
+-------------------------------------------------------+
| $mysqli   database link                               |
+-------------------------------------------------------+
*/
function getHRgroupArray ($mysqli) {
    $query = 'select `id`,`name` from userhrgroup where active = 1 order by displayorder';
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            $hrgroupX[] = array("id" => $row[0], "hrgroup" => $row[1]);
        }
        $result->close();
    }
    return $hrgroupX;
}
/*
+-------------------------------------------------------+
| Function getStageArray                           	|
+-------------------------------------------------------+
| $pid      project id                                  |
| $stage_id Stage Id                                    |
| $stage_nm Stage Name                                  |
| $mysqli   database link                               |
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

    // Task Rows
    $rowno = 1;
    $co = count($taskX);
    for ($e = 0; $e < $co; $e++) {

        $task_id = $taskX[$e]["id"];
        
        echo '
            <!-- ['.$stage_id.'] '.$task_id.'. '.$taskX[$e]["task"].' -->
            <tr><td class="dataRowCell1">'.$rowno++.'</td><td class="dataRowCell2" style="text-align:left;">'.$taskX[$e]["task"].'</td>';
        taskTimeTabulate($pid, $stage_id, $task_id, $hrgroupX, $ver, $mysqli);

    }

    // Column Totals Row


    // Grand Total for this Stage Row


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
    $total_budgeted_hr = 0;
    $total_timesheet_hr = 0;
    $total_timesheet_mn = 0;
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
                t1.user_id = t2.user_id';
    //echo "<br>Q2: ".$query;

    // Reset Variables
    $this_h = 0;
    $this_m = 0;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            $this_h = $this_h + $row[0];
            $this_m = $this_m + $row[1];

            //$tmsheetX[] = array("h" => $this_h, "m" => $this_m, "hid" => $row[2]);

        }

        $result->close();
    }

    //$co_tmsheetX = count($tmsheetX);
    //echo '<br>co_tmsheetX: '.$co_tmsheetX;
    /*
    +-------------------------------------------------------+
    | Loop hrgroup                                          |
    +-------------------------------------------------------+
    */
    for ($x = 0; $x < count($hrgroupX); $x++) {

        $hid        = $hrgroupX[$x]["id"];
        $hrgroup    = $hrgroupX[$x]["hrgroup"];

        // Estimate
        $manhours   = 0; // Reset

        // Time Estimate
        for ($a = 0; $a < $co_timeX; $a++) {

            $this_hid = $timeX[$a]["hid"];

            if ($this_hid == $hid) {
                $mh = $timeX[$a]["mh"];
                $manhours = $manhours + $mh;
                $total_budgeted_hr = $total_budgeted_hr + $mh;
            }

        }

        if ($manhours < 1) $manhours = '&nbsp;';

        // Timesheets
        // Total time for this stage, task, hrgroup filter
        $query = 'select
                t1.no_of_hours,
                t1.no_of_min                
            from
                timesheet as t1,
                users_a as t2
            where
                t1.project_id = '.$pid.' and
                t1.projectstage_id = '.$stage_id.' and
                t1.task_id = '.$task_id.' and
                t2.userhrgroup_id = '.$hid.' and
                t1.active = 1 and
                t1.user_id = t2.user_id';

        // Reset Variables
        $this_h = 0;
        $this_m = 0;

        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                $this_h = $this_h + $row[0];
                $this_m = $this_m + $row[1];

                //$tmsheetX[] = array("h" => $this_h, "m" => $this_m, "hid" => $row[2]);

            }

            $result->close();
        }        

        // Formatting         
        $ts_display = timeAdd($this_h, $this_m);
        if ($ts_display === '0:0') $ts_display = '&nbsp;';

        if ($this_h >= $manhours) {
            $color = 'Red';
        } else {
            $color = 'Green';
        }

        echo '<td class="dataRowCell2"><span style="color:Blue">'.$manhours.'</span><br><span style="color:'.$color.'">'.$ts_display.'</span></td>';

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

    echo '<td class="dataRowCell2">'.$est_display.'<br>'.$ts2_display.'</td>';
}

?>
