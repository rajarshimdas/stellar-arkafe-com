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
        $taskX[] = $data;
    }

    fclose($handle);
}

// Delete file
if (unlink($file) !== true) {
    die('<div class="messagebarError">Server Error: File unlink failed.</div>');
}



// Save data in db
$mysqli = cn2();
$no = 0;
$eo = 0;

for ($i = 1; $i < count($taskX); $i++) {

    $scope_sc   = strtoupper(trim($taskX[$i][1]));
    $stage_sc   = strtoupper(trim($taskX[$i][2]));
    $work       = trim($taskX[$i][3]);
    $hr         = trim($taskX[$i][4]);
    $mn         = trim($taskX[$i][5]);
    $tp         = trim($taskX[$i][6]);

    $scopeId = $scope[$scope_sc][0];
    $stageId = $stage[$stage_sc];

    // die($pid . ' | ' . $work . ' | ' . $scopeId . ' | ' . $stageId . ' | ' . $hr . ' | ' . $mn . ' | ' . $tp);
    $rx = bdAddTask($pid, $work, $scopeId, $stageId, $hr, $mn, $tp, $mysqli);

    if ($rx[0] == 'T') $no++;
    else $eo++;
}
?>
<div style="padding: 15px; background-color: #d4d5e9;">
    <?php
    if ($no > 0) echo "<div>Added $no tasks</div>";
    if ($eo > 0) echo "<div>Failed to save $eo tasks</div>";
    ?>
</div>