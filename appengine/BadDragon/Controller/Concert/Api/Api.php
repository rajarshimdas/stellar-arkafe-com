<?php
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

/* taskallotmhlog
+-------------+------------------+------+-----+---------+-------+
| Field       | Type             | Null | Key | Default | Extra |
+-------------+------------------+------+-----+---------+-------+
| task_id     | int(10) unsigned | NO   |     | NULL    |       |
| month       | date             | NO   |     | NULL    |       |
| allottedmin | int(11)          | NO   |     | NULL    |       |
+-------------+------------------+------+-----+---------+-------+
*/

function bdAddTask(
    int $pid,
    string $work,
    int $scopeId,
    int $stageId,
    int $hr,
    int $mn,
    int $targetPercent,
    object $mysqli
): array {

    $mysqli->autocommit(false);

    $fx                 = 0;
    $cm_date_flag       = date("Y-m-01");
    $cm_allotted_mh     = ($hr * 60) + $mn;
    $lm_allotted_mh     = $cm_allotted_mh;

    $query = "INSERT INTO `task` 
            (`project_id`, `work`, `projectscope_id`, `projectstage_id`, `manhours`, `manminutes`, `status_this_month_target`, `cm_date_flag`, `cm_allotted_mh`, `lm_allotted_mh`, `cm_added_mh`) 
        VALUES 
            ('$pid', '$work', '$scopeId', '$stageId', '$hr', '$mn', '$targetPercent', '$cm_date_flag', '$cm_allotted_mh', '0', '$cm_allotted_mh')";

    // die($query);

    if ($mysqli->query($query)) {
        $task_id = $mysqli->insert_id;
    } else {
        $fx++;
        $bdMessageTxt = "Server Error Q1.";
    }

    /* Task monthly alloted manhour log | Sep 25 */
    $query = "INSERT INTO `taskallotmhlog` 
                (`task_id`, `month`, `allottedmin`) 
            VALUES 
                ('$task_id', '$cm_date_flag', '$lm_allotted_mh')";

    if (!$mysqli->query($query)) {
        $fx++;
        $bdMessageTxt = "Server Error Q2.";
    }

    if ($fx < 1) {
        $mysqli->commit();
        return ['T', 'ok'];
    } else {
        $mysqli->rollback();
        return ['F', 'Task not added :: ' . $bdMessageTxt];
    }
}


function bdEditTask(
    int $task_id,
    array $taskData,        // Task data array to avoid mulitple querry when function used inside loop
    int $scope_id,
    int $stage_id,
    string $work,
    int $manhours,
    int $manminutes,
    int $comp_per,          // Completed %
    int $targ_per,          // Target %
    int $additonalMin,      // Additional Mh allotted in current month | 04-Oct-2025
    object $mysqli
): array {

    $mysqli->autocommit(false);

    $fx = 0;
    $mo = date('Y-m-01');
    $cm_allotted_mh = ($manhours * 60) + $manminutes;

    if ($mo != $taskData['cm_date_flag']) {
        // Update last month
        $q = " `cm_date_flag` = '$mo', `cm_allotted_mh` = '$cm_allotted_mh', `lm_allotted_mh` = '" . $taskData['cm_allotted_mh'] . "' ";
    } else {
        // Same month
        $q = " `cm_date_flag` = '$mo', `cm_allotted_mh` = '$cm_allotted_mh' ";
    }

    $query = "UPDATE 
                `task` 
            SET 
                `work` = '$work', 
                `projectscope_id` = '$scope_id', 
                `projectstage_id` = '$stage_id', 
                `status_last_month` = '$comp_per', 
                `status_this_month_target` = '$targ_per',
                `manhours` = '$manhours',
                `manminutes` = '$manminutes',
                `cm_added_mh` = '$additonalMin',
                $q
            WHERE 
                (`id` = '$task_id')";


    if (!$mysqli->query($query)) {
        $fx++;
        $bdMessageTxt = "Server Error Q1.";
    }

    /* Task monthly alloted manhour log | Sep 25 */

    // Check if record exists
    $flag = 'F';

    $query = "SELECT 
            'T' as `flag` 
        FROM 
            `taskallotmhlog` 
        where 
            `task_id` = '$task_id' and 
            `month` = '$mo'";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $flag = $row['flag'];
        };
        $result->close();
    }

    // Add or Update record
    $totalmin = ($manhours * 60) + $manminutes;

    if ($flag != 'T')
        $query = "INSERT INTO `taskallotmhlog` 
                (`task_id`, `month`, `allottedmin`) 
            VALUES 
                ('$task_id', '$mo', '$totalmin')";
    else
        $query = "UPDATE `taskallotmhlog` 
            SET 
                `allottedmin` = '$totalmin' 
            WHERE 
                (`task_id` = '$task_id') and (`month` =  '$mo')";

    if (!$mysqli->query($query)) {
        $fx++;
        $bdMessageTxt = "Server Error Q2.";
    }

    if ($fx < 1) {
        $mysqli->commit();
        return ['T', 'ok'];
    } else {
        $mysqli->rollback();
        return ['F', 'Task edit failed :: ' . $bdMessageTxt];
    }
}
