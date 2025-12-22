<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 11-Oct-24 (Astami)                        |
| Updated On:                                           |
+-------------------------------------------------------+
*/
?>
<style>
    table#excel {
        width: 100%;
        margin: auto;
        border-spacing: 0px;
        border-collapse: collapse;
    }

    table#excel tr {
        height: 35px;
    }

    table#excel tr td {
        border: 1px solid lightgray;
        padding: 0px 10px;
        text-align: center;
    }

    .alert {
        color: red;
    }
</style>
<?php
$file = W3PATH . '/w3filedb/import/task_' . $sid . '.csv';

// Read CSV File
if (($handle = fopen($file, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 500, ",")) !== FALSE) {
        $taskX[] = $data;
    }

    fclose($handle);
}

// var_dump($taskX);

// Data Validation



// Formatted Data Rows
$tX[] = ["Flag", "TaskId", "Scope", "Milestone", "Work Description", "Completed%", "Target%", "AH", "AM"];

// Data Rows
$flag = 0;
$co = 0;

for ($i = 1; $i < count($taskX); $i++) {

    $rem = "";
    $errorflag = 0;
    $style = 'style="background-color:white;"';
    $color = 'white';

    $this_task_id   = trim($taskX[$i][1]);

    $this_scope     = strtoupper(trim($taskX[$i][2]));
    $this_stage     = strtoupper(trim($taskX[$i][3]));

    $this_work      = trim($taskX[$i][4]);

    $this_percent_comp  = trim($taskX[$i][5]);
    $this_percent_targ  = trim($taskX[$i][6]);

    $this_manhours  = trim($taskX[$i][7]);
    $this_minutes   = trim($taskX[$i][8]);

    // echo $this_manhours.'<br>';

    // Check all data is available
    for ($e = 1; $e < 9; $e++) {

        if (isset($taskX[$i][$e])) {

            if (strlen($taskX[$i][$e]) < 1) {
                $errorflag++;
                $rem = "Invalid data";
            }
        } else {
            $errorflag++;
            $rem = "Incomplete data";
        }
    }

    // Validate all fields
    if ($errorflag < 1 && $taskX[$i][0] == "@") {

        // Check Work Description
        if (alpha_numeric_text($this_work) !== true) {
            $rem = $rem . '<div>Work contains restricted special characters</div>';
            $this_work = '<div class="alert">' . $this_work . '</div>';
            $errorflag++;
        }

        // Check Manhours
        if (integer($this_manhours) != true || $this_manhours < 0) {
            $rem = $rem . '<div>Manhours is invalid</div>';
            $this_manhours = '<div class="alert">' . $this_manhours . '</div>';
            $errorflag++;
        }

        // Check Manminutes
        if (integer($this_minutes) != true || $this_minutes < 0 || $this_minutes > 59) {
            $rem = $rem . '<div>Minutes is invalid</div>';
            $this_minutes = '<div class="alert">' . $this_minutes . '</div>';
            $errorflag++;
        }

        // Completed %
        if (integer($this_percent_comp) != true || $this_percent_comp < 0 || $this_percent_comp > 100) {
            $rem = $rem . '<div>Completed % is invalid</div>';
            $this_percent_comp = '<div class="alert">' . $this_percent_comp . '</div>';
            $errorflag++;
        }

        // Target %
        if (integer($this_percent_targ) != true || $this_percent_targ < 0 || $this_percent_targ > 100) {
            $rem = $rem . '<div>Target % is invalid</div>';
            $this_percent_targ = '<div class="alert">' . $this_percent_targ . '</div>';
            $errorflag++;
        }

        if ($errorflag > 0) 
            $color = '#fbe5bd';
        else 
            $co++;

    } else {
        $color = 'lightgray';
    }

    $style = 'style="background-color:' . $color . ';"';

    $tX[] = [
        $taskX[$i][0],
        $this_task_id,
        $this_scope,
        $this_stage,
        $this_work,
        $this_percent_comp,
        $this_percent_targ,
        $this_manhours,
        $this_minutes,
        $style,

    ];

    $flag = $flag + $errorflag;
}

// Show next steps
if ($flag > 0 || $co < 1) {
    $rx = "Fix errors in CSV File and try again...";
    $btn = '<a class="button" style="width:150px" href="' . BASE_URL . 'concert/portal/tasks/update">Back</a>';
} else {
    $rx = "Update tasks";
    $btn = '<a class="button" style="width:150px" href="' . BASE_URL . 'concert/portal/tasks/update/save">Save</a>';
}
?>

<div style="padding: 15px; background-color: #d4d5e9;">
    <table style="width: 100%;">
        <tr>
            <td><?= $rx ?></td>
            <td style="width: 150px;"><?= $btn ?></td>
        </tr>
    </table>
</div>

<table id="excel">
    <tr style="background-color: grey; color: white; text-align:center;">
        <td style="width: 35px; padding: 0px;"></td>
        <td style="width: 40px;">A</td>
        <td style="width: 70px;">B</td>
        <td style="width: 70px;">C</td>
        <td style="width: 70px;">D</td>
        <td>E</td>
        <td style="width: 70px;">F</td>
        <td style="width: 70px;">G</td>
        <td style="width: 70px;">H</td>
        <td style="width: 70px;">I</td>
    </tr>

    <?php
    $ro = 1;
    foreach ($tX as $t) :
    ?>
        <tr <?= $t[9] ?>>
            <td style="background-color: grey; color: white; text-align:center;"><?= $ro++ ?></td>
            <td><?= $t[0] ?></td>
            <td><?= $t[1] ?></td>
            <td><?= $t[2] ?></td>
            <td><?= $t[3] ?></td>
            <td style="text-align: left;"><?= $t[4] ?></td>
            <td><?= $t[5] ?></td>
            <td><?= $t[6] ?></td>
            <td><?= $t[7] ?></td>
            <td><?= $t[8] ?></td>
        </tr>

    <?php endforeach; ?>
</table>