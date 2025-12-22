<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 27-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
checkApiAccess($user_id, $mysqli);


$task_id    = $_POST["taskId"] + 0;

// Validation flag
$_SESSION['bdMessageFlag'] = 0;

/* Validation */
if (!is_int($task_id) || $task_id < 100) {

    rdReturnJsonHttpResponse(
        '200', ['F',"Server Error. Incorrect data received." ]
    );
    
};

/* task
+--------------------------+-----------------------+------+-----+---------------------+----------------+
| Field                    | Type                  | Null | Key | Default             | Extra          |
+--------------------------+-----------------------+------+-----+---------------------+----------------+
| id                       | bigint(20) unsigned   | NO   | PRI | NULL                | auto_increment |
| project_id               | mediumint(8) unsigned | NO   | MUL | NULL                |                |
| work                     | text                  | NO   |     | NULL                |                |
| remark                   | varchar(200)          | NO   |     | NULL                |                |
| projectscope_id          | tinyint(3) unsigned   | NO   |     | NULL                |                |
| projectstage_id          | tinyint(3) unsigned   | NO   |     | NULL                |                |
| displayorder             | bigint(20) unsigned   | NO   |     | NULL                |                |
| status_last_month        | tinyint(4)            | NO   |     | NULL                |                |
| status_this_month_target | tinyint(4)            | NO   |     | NULL                |                |
| dt                       | datetime              | NO   |     | current_timestamp() |                |
| active                   | tinyint(4)            | NO   |     | 1                   |                |
| allocation_flag          | tinyint(4)            | NO   |     | 0                   |                |
+--------------------------+-----------------------+------+-----+---------------------+----------------+
*/

$query = "UPDATE 
            `task` 
        SET 
            `active` = 0 
        WHERE 
            (`id` = '$task_id')";

$mysqli = cn2();

if ($mysqli->query($query)){
    rdReturnJsonHttpResponse(
        '200',
        ["T"]
    );
} else {
    rdReturnJsonHttpResponse(
        '200',
        ["F", "Server Error"]
    );
}
