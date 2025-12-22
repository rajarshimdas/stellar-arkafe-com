<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 27-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
checkApiAccess($user_id, $mysqli);


$task_id    = $_POST["dxTaskId"] + 0;
$stage_id   = $_POST["dxStageId"] + 0;
$scope_id   = $_POST["dxScopeId"] + 0;
$work       = trim(($_POST["dxWork"]));
$comp_per   = $_POST["dxCompPer"] + 0;
$targ_per   = $_POST["dxTargPer"] + 0;
//$mandays  = $_POST["dxMd"] + 0;
$manhours   = $_POST["dxMhr"] + 0;
$manminutes = $_POST["dxMmn"] + 0;


// Validation flag
$_SESSION['bdMessageFlag'] = 0;

/* Validation */
if (!is_int($task_id) || $task_id < 100) {

    rdReturnJsonHttpResponse(
        '200',
        ['F', "Server Error. Incorrect data received."]
    );
};

if (!is_int($stage_id) || $stage_id < 1) {

    rdReturnJsonHttpResponse(
        '200',
        ['F', "Select stage and try again"]
    );
};

if (!is_int($scope_id) || $scope_id < 1) {

    rdReturnJsonHttpResponse(
        '200',
        ['F', "Select scope and try again"]
    );
};

if (strlen($work) < 5) {

    rdReturnJsonHttpResponse(
        '200',
        ['F', "Work description must have at least 5 characters. Please try again."]
    );
} else {
    if (alpha_numeric_text($work) !== true) {

        rdReturnJsonHttpResponse(
            '200',
            ['F', "Restricted special character in Work. Please try again."]
        );
    }
}



if (!is_int($comp_per) || $comp_per < 0 || $comp_per > 100) {

    rdReturnJsonHttpResponse(
        '200',
        ['F', "Completed % should have value between 0 to 100."]
    );
};


if (!is_int($targ_per) || $targ_per < 0 || $targ_per > 100) {

    rdReturnJsonHttpResponse(
        '200',
        ['F', "Target % should have value between 0 to 100."]
    );
};

if (!is_int($manhours) || $manhours < 0) {

    rdReturnJsonHttpResponse(
        '200',
        ["F", "Manhours should be 0 or a positive integer"]
    );
}

if (!is_int($manminutes) || $manminutes < 0 || $manminutes > 59) {

    rdReturnJsonHttpResponse(
        '200',
        ["F", "Minutes should be a positive integer between 0 and 59."]
    );
}


# Get current details for this task
#
$taskData = bdTaskById($task_id, $mysqli);
/*
+-------------------------------------------------------+
| Get Utilized Manhours
+-------------------------------------------------------+
*/
$tkMh = bdTaskUtilizedMH($pid, $mysqli, "LM");
$utilizedMin = empty($tkMh[$task_id]) ? 0 : $tkMh[$task_id]['totalmin'];

$additonalMin = (($manhours * 60) + $manminutes) - $utilizedMin;
/*
rdReturnJsonHttpResponse(
    '200',
    ["F", "tk: " . json_encode($taskData)]
);
*/

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

$mysqli = cn2();

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
    $additonalMin,
    $mysqli
);

if ($rx[0] == 'T') {
    rdReturnJsonHttpResponse(
        '200',
        ["T", "ok"]
    );
} else {
    rdReturnJsonHttpResponse(
        '200',
        ["F", "Error :: " . $rx[1]]
    );
}
