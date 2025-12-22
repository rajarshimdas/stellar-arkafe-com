<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 09-Feb-12					|
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

$mysqli = cn1();

// Required
$stageid        = $_GET["stageid"];
$workgroupid    = $_GET["workgroupid"];
//echo 'stageid: '.$stageid.'<br>workgroupid: '+$workgroupid;


// Optional - to set the default task
$taskid         = $_GET["taskid"];
$task           = $_GET["task"];

//echo 'stageid: '.$stageid.'<br>taskid: '.$taskid.'<br>task: '.$task;

// Get the stage array
$query = 'select
            t2.id,
            t2.name
        from
            projectstagetasks as t1,
            timesheettasks as t2
        where
            t1.projectstage_id = '.$stageid.' and
            t1.department_id = '.$workgroupid.' and
            t1.timesheettask_id = t2.id
        order by
            t1.displayorder';

//echo '<br>Q: '.$query;

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {
        $taskX[] = array ("taskid" => $row[0], "task" => $row[1]);
    }
    $result->close();

}
$mysqli->close();

?>
<select name="taskid" style="width: 100%" onchange="javascript:onChangeTask($(this).val());">
    <?php

    // Set the Visible Option
    if ($taskid && $task){
        // Task is known
        echo '<option value="'.$taskid.'">'.$task.'</option>';
    } else {
        // Task needs to be selected
        echo '<option value="0">-- Select --</option>';
    }

    // Other options
    for ($i = 0; $i < count($taskX); $i++) {
        $tid = $taskX[$i]["taskid"];
        if ($tid !== $taskid) {
            echo '<option value="'.$tid.'">'.$taskX[$i]["task"].'</option>';
        }
    }
    ?>
</select>
