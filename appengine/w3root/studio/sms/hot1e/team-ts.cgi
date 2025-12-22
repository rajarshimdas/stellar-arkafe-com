<?php
require_once $w3etc . '/foo/dateMysql2Cal.php';
require_once $w3etc . '/foo/timeAdd.php';

$projId     = $projectid;
$tmid       = $_GET["tmid"];  // Teammate user id

// Get User details
$query = 'select * from users where id='.$tmid;
$result = $mysqli->query($query);
$row = $result->fetch_assoc();

$banner = $row["fullname"];


// Get Stages
/* projectstage
+---------+---------------------+------+-----+---------+----------------+
| Field   | Type                | Null | Key | Default | Extra          |
+---------+---------------------+------+-----+---------+----------------+
| id      | tinyint(3) unsigned | NO   | PRI | NULL    | auto_increment |
| name    | varchar(150)        | NO   |     | NULL    |                | Fullnamem
| stageno | tinyint(3) unsigned | NO   |     | NULL    |                |
| active  | tinyint(1)          | NO   |     | 1       |                |
| sname   | varchar(45)         | YES  |     | NULL    |                | Abbreviation
+---------+---------------------+------+-----+---------+----------------+
*/
$query = "SELECT * FROM `projectstage` where `active` > 0 order by `stageno`";

$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()) {
    $stageX[] = $row;
    $sX[$row["id"]] = $row["name"];
}


// Get Timesheet entries
/* timesheet
+-----------------+-----------------------+------+-----+---------------------+----------------+
| Field           | Type                  | Null | Key | Default             | Extra          |
+-----------------+-----------------------+------+-----+---------------------+----------------+
| id              | bigint(20) unsigned   | NO   | PRI | NULL                | auto_increment |
| dt              | date                  | NO   | MUL | NULL                |                |
| user_id         | mediumint(8) unsigned | NO   |     | NULL                |                |
| project_id      | mediumint(8) unsigned | NO   |     | NULL                |                |
| projectstage_id | tinyint(3) unsigned   | NO   |     | 1                   |                |
| department_id   | tinyint(3) unsigned   | NO   |     | NULL                |                |
| task_id         | tinyint(3) unsigned   | NO   |     | NULL                |                |
| subtask         | varchar(250)          | NO   |     | NULL                |                |
| no_of_hours     | tinyint(4)            | NO   |     | NULL                |                |
| no_of_min       | tinyint(4)            | NO   |     | NULL                |                |
| work            | text                  | NO   |     | NULL                |                |
| worked_from     | tinyint(3) unsigned   | NO   |     | 10                  |                |
| approved        | tinyint(1)            | NO   |     | 0                   |                |
+-----------------+-----------------------+------+-----+---------------------+----------------+
| quality         | tinyint(1)            | NO   |     | 0                   |                | flag 
+-----------------+-----------------------+------+-----+---------------------+----------------+
| tmstamp         | timestamp             | NO   |     | current_timestamp() |                |
| percent         | tinyint(4)            | NO   |     | 0                   |                |
| pm_review_flag  | tinyint(4)            | NO   |     | 0                   |                |
| active          | tinyint(1)            | NO   |     | 1                   |                |
| projectscope_id | smallint(6)           | NO   |     | 1                   |                |
+-----------------+-----------------------+------+-----+---------------------+----------------+
*/

$query = 'SELECT 
            * 
        FROM 
            timesheet 
        where 
            project_id = ' . $projId . ' and
            user_id = ' . $tmid . ' and
            active > 0 and
            quality < 1
        order by
            id DESC';


$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()) {
    $ts[$row["projectstage_id"]][] = $row;
}

//var_dump($tsX);
?>
<style>
    .tsTable {
        padding: 0px;
        margin: 0px;
    }

    .tsTable tr td {
        text-align: center;
        height: 30px;
        vertical-align: top;
        line-height: 30px;
    }
</style>
<div class="divBanner"><?= $banner ?></div>
<table class="tsTable" width="100%" style="font-size:80%;" cellpadding="2" cellspacing="0">
    <tr style="background:#FFF6F4;font-weight:bold;">
        <td class="cellHeaderLeft" width="5%" rowspan="">No</td>
        <td class="cellHeader" width="15%" style="text-align:left;">Milestone</td>
        <td class="cellHeader" width="10%">Date</td>
        <td class="cellHeader" style="text-align:left;">Work Description</td>
        <td class="cellHeader" width="8%">Percent<br>Completed</td>
        <td class="cellHeader" width="8%">Hours<br>Worked</td>
        <td class="cellHeader" width="8%">Total<br>Hours</td>
    </tr>



    <?php
    // Data Rows
    $no = 0;
    $total_hr = 0;
    $total_mn = 0;


    for ($i = 0; $i < count($stageX); $i++) {
        
        $stageId = $stageX[$i]['id'];

        if (isset($ts[$stageId])) {

            $stagename = $sX[$stageId];
            $co = 0;
            $no++;

            // Timesheets for this member
            $tsX = $ts[$stageId];
            $co = count($tsX);

            $rowspan = ($co > 0) ? "$co" : "1";

            // Calculate Total HH"MM
            $hr = 0;
            $mn = 0;
            for ($e = 0; $e < $co; $e++) {
                $hr = $hr + $tsX[$e]["no_of_hours"];
                $mn = $mn + $tsX[$e]["no_of_min"];
            }
            $tmh = timeadd($hr, $mn);

            $total_hr = $total_hr + $hr;
            $total_mn = $total_mn + $mn;

            // Generate first row
    ?>
            <tr>
                <td class="cellRowLeft" rowspan="<?= $rowspan ?>"><?= $no ?></td>
                <td class="cellRow" style="text-align:left;" rowspan="<?= $rowspan ?>">
                    <?= $stagename ?>
                </td>
                <td class="cellRow"><?= dateMysql2Cal($tsX[0]["dt"]) ?></td>
                <td class="cellRow" style="text-align:left;">
                    <div><?= $tsX[0]["work"] ?></div>
                </td>
                <td class="cellRow"><?= $tsX[0]["percent"] ?></td>
                <td class="cellRow">
                    <?= $tsX[0]["no_of_hours"] . ':' . $tsX[0]["no_of_min"] ?>
                </td>
                <td class="cellRow" rowspan="<?= $rowspan ?>"><?= $tmh ?></td>
            </tr>
            <?php

            // Generate next rows if any
            for ($e = 1; $e < $co; $e++) {
            ?>

                <tr>
                    <td class="cellRow"><?= dateMysql2Cal($tsX[$e]["dt"]) ?></td>
                    <td class="cellRow" style="text-align:left;">
                        <div><?= $tsX[$e]["work"] ?></div>
                    </td>
                    <td class="cellRow"><?= $tsX[$e]["percent"] ?></td>
                    <td class="cellRow">
                        <?= $tsX[$e]["no_of_hours"] . ':' . $tsX[$e]["no_of_min"] ?>
                    </td>
                </tr>

    <?php

            }
        }
    }

    // Generate Totals
    ?>
    <tr>
        <td colspan="6" style="text-align: right;">Total</td>
        <td>
            <?= timeadd($total_hr, $total_mn) ?>
        </td>
    </tr>
</table>

