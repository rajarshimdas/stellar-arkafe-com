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
/* task
+--------------------------+-----------------------+------+-----+---------------------+----------------+
| Field                    | Type                  | Null | Key | Default             | Extra          |
+--------------------------+-----------------------+------+-----+---------------------+----------------+
| id                       | int(10) unsigned      | NO   | PRI | NULL                | auto_increment |
| project_id               | mediumint(8) unsigned | NO   | MUL | NULL                |                |
| work                     | text                  | NO   |     | NULL                |                |
| remark                   | varchar(50)           | NO   |     | -                   |                |
| projectscope_id          | tinyint(3) unsigned   | NO   |     | NULL                |                |
| projectstage_id          | tinyint(3) unsigned   | NO   |     | NULL                |                |
| displayorder             | tinyint(3) unsigned   | NO   |     | 10                  |                |
| status_last_month        | tinyint(4)            | NO   |     | 0                   |                |
| status_this_month_target | tinyint(4)            | NO   |     | 0                   |                |
| allocation_flag          | tinyint(4)            | NO   |     | 0                   |                |
| mandays                  | smallint(6)           | NO   |     | 0                   |                |
| manhours                 | smallint(5) unsigned  | NO   |     | 0                   |                |
| manminutes               | tinyint(3) unsigned   | NO   |     | 0                   |                |
| dt                       | datetime              | NO   |     | current_timestamp() |                |
| active                   | tinyint(4)            | NO   |     | 1                   |                |
| mcode                    | varchar(45)           | NO   |     | x                   |                |
| onhold                   | tinyint(4)            | NO   |     | 0                   |                |
| cm_date_flag             | date                  | NO   |     | 2000-01-01          |                |
| cm_allotted_mh           | int(10) unsigned      | NO   |     | 0                   |                |
| lm_allotted_mh           | int(10) unsigned      | NO   |     | 0                   |                |
+--------------------------+-----------------------+------+-----+---------------------+----------------+
*/


// Data Validation

// Formatted Data Rows
$tX[] = [
    'No',
    'Scope Id',
    'Milestone Id',
    'Work Description',
    'Manhours',
    'Minutes',
    'Target %',
    'Remark',
];

// Data Rows
$flag = 0;
for ($i = 1; $i < count($taskX); $i++) {

    $rem = "";
    $errorflag = 0;
    $style = 'style="background-color:white;"';

    $this_scope     = strtoupper(trim($taskX[$i][1]));
    $this_stage     = strtoupper(trim($taskX[$i][2]));
    $this_work      = trim($taskX[$i][3]);
    $this_manhours  = trim($taskX[$i][4]);
    $this_minutes   = trim($taskX[$i][5]);
    $this_percent   = trim($taskX[$i][6]);

    // echo $this_manhours.'<br>';

    // Check all data is available
    for ($e = 1; $e < 5; $e++) {

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
    if ($errorflag < 1) {

        // Check Scope
        $x = checkProjectScope($this_scope, $scope);

        if ($x[0] != "T") {
            $rem = $rem . '<div>' . $x[1] . '<div>';
            $this_scope = '<div class="alert">' . $this_scope . '</div>';
            $errorflag++;
        }

        // Check Milestone
        if (!isset($stage[$this_stage])) {
            $rem = $rem . "<div>Stage shortcode is invalid</div>";
            $this_stage = '<div class="alert">' . $this_stage . '</div>';
            $errorflag++;
        }

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


        if (integer($this_percent) != true || $this_percent < 0 || $this_percent > 100) {
            $rem = $rem . '<div>Target % is invalid</div>';
            $this_percent = '<div class="alert">' . $this_percent . '</div>';
            $errorflag++;
        }
    }

    $style = ($errorflag > 0) ? 'style="background-color:lightgray;"' : 'style="background-color:white;"';
    // echo "<div>rem: $rem | flag: $errorflag</div>";

    $tX[] = [
        $taskX[$i][0],
        $this_scope,
        $this_stage,
        $this_work,
        $this_manhours,
        $this_minutes,
        $this_percent,
        $rem,
        $style,

    ];

    $flag = $flag + $errorflag;
}
// var_dump($tX);

$co = empty($tX) ? 0 : count($tX);

// Show next steps
if ($flag > 0 || $co < 2) {
    $rx = "Fix errors in CSV File and try again...";
    $btn = '<a class="button" style="width:150px" href="' . BASE_URL . 'concert/portal/tasks/import">Back</a>';
} else {
    $rx = "Import data";
    $btn = '<a class="button" style="width:150px" href="' . BASE_URL . 'concert/portal/tasks/import/save">Save</a>';
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
        <td style="width: 90px;">C</td>
        <td>D</td>
        <td style="width: 70px;">E</td>
        <td style="width: 70px;">F</td>
        <td style="width: 70px;">G</td>
        <td style="width: 300px;">Server Response</td>
    </tr>

    <?php


    $ro = 1;
    foreach ($tX as $t) :
    ?>
        <tr <?= $t[8] ?>>
            <td style="background-color: grey; color: white; text-align:center;"><?= $ro++ ?></td>
            <td style="text-align: center;"><?= $t[0] ?></td>
            <td><?= $t[1] ?></td>
            <td><?= $t[2] ?></td>
            <td><?= $t[3] ?></td>
            <td style="text-align: center;"><?= $t[4] ?></td>
            <td style="text-align: center;"><?= $t[5] ?></td>
            <td style="text-align: center;"><?= $t[6] ?></td>
            <td><?= $t[7] ?></td>
        </tr>

    <?php endforeach; ?>
</table>