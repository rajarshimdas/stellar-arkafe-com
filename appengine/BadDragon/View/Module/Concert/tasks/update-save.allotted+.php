<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 11-Oct-24 (Astami)                        |
| Updated On: 25-Sep-25                                 |
+-------------------------------------------------------+
*/
require_once BD . "Controller/Concert/Api/Api.php";
$file = W3PATH . '/w3filedb/import/task_' . $sid . '.csv';

// Read CSV File
if (($handle = fopen($file, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 500, ",")) !== FALSE) {
        $tasks[] = $data;
    }

    fclose($handle);
}

// var_dump($tasks);
// ["Flag", "TaskId", "Scope", "Milestone", "Work", "Completed%", "Target%", "AH", "AM"]


$mcode = $sid . '-' . rand(100, 999);
$co = 0;

$mysqli = cn2();


// Update
foreach ($tasks as $t) {

    if ($t[0] == '@') {
        //var_dump($t);

        /*
        "Flag"          => $t[0],
        "TaskId"        => $t[1],
        "Scope"         => $t[2],
        "Milestone"     => $t[3],
        "Work"          => $t[4],
        "Completed%"    => $t[5],
        "Target%"       => $t[6],
        "AH"            => $t[7],
        "AM"            => $t[8],
        */

        $task_id = $t[1];

        $taskData = bdTaskById($task_id, $mysqli);
        // die(var_dump($taskData));

        // die($pid . ' | ' . $taskData['project_id']);
        if ((int)$pid !== (int)$taskData['project_id'])
            die('<div class="messagebarError">Template contains task(s) that does not belong to this project.</div>');

        // Readonly Fields
        $scope_id = $taskData['scope_id'];
        $stage_id = $taskData['stage_id'];

        // Calculate additional hour and min
        $additonalMin = ($t[7] * 60) + $t[8];
        $existingMin = ((int)$taskData['manhours'] * 60) + (int)$taskData['manminutes'];

        $totalMin = $existingMin + $additonalMin;
        $h = floor($totalMin / 60);
        $m = $totalMin % 60;

        // Update Fields
        $work           = $t[4];
        $manhours       = $h;
        $manminutes     = $m;
        $comp_per       = $t[5];
        $targ_per       = $t[6];

        $rx = bdEditTask(
            $task_id,
            $taskData,
            $scope_id,
            $stage_id,
            $work,
            $manhours,
            $manminutes,
            $comp_per,
            $targ_per,
            $mysqli
        );

        if ($rx[0] == 'T') $co++;
    }
}

$co = ($co < 2) ? $co . ' task.' : $co . ' tasks.';
?>
<div style="padding: 15px; background-color: #d4d5e9;">
    Updated <?= $co ?>
</div>