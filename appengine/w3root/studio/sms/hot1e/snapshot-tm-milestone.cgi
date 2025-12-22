<?php
require_once $w3etc . '/foo/dateMysql2Cal.php';
require_once $w3etc . '/foo/timeAdd.php';

$projId     = $projectid;
$stageId    = $_GET["sid"];


// Get stage name
$query = "SELECT * FROM projectstage where id = $stageId";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();

$stageName = $row["name"];
// echo 'projId: ' . $projId . ' stageId: ' . $stageId;
$banner = 'Timesheets for ' . $stageName;

// All users - including deleted ones
$query = 'select id, fullname from users order by fullname';

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $u[] = [
        'id'        => $row['id'],
        'fullname'  => $row["fullname"],
    ];
    $users[$row["id"]] = $row["fullname"];
}
// var_dump($users);


// All timesheets for this project and stage
$query = 'SELECT 
            * 
        FROM 
            timesheet 
        where 
            project_id = ' . $projId . ' and
            projectstage_id = ' . $stageId . ' and
            active > 0 and
            quality < 1
        order by
            id DESC';

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $ts[$row["user_id"]][] = $row;
    // echo $i.'. '.$row["user_id"].' | '.$users[$row["user_id"]].'<br>';
}
?>

<style>
    .tsTable {
        padding: 0px;
        margin: 0px;
    }

    .tsTable thead tr td {
        vertical-align: middle;
        line-height: normal;
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
    <thead>
        <tr style="background:#FFF6F4;font-weight:bold;">
            <td class="cellHeaderLeft" width="5%" rowspan="">No</td>
            <td class="cellHeader" width="15%" style="text-align:left;">Team member</td>
            <td class="cellHeader" width="10%">Date</td>
            <td class="cellHeader" style="text-align:left;">Work Description</td>
            <td class="cellHeader" width="8%">Percent<br>Completed</td>
            <td class="cellHeader" width="8%">Hours<br>Worked</td>
            <td class="cellHeader" width="8%">Total<br>Hours</td>
        </tr>
    </thead>

    <?php
    // Data Rows
    $no = 0;
    $total_hr = 0;
    $total_mn = 0;


    for ($i = 0; $i < count($users); $i++) {
        // echo '<br>'.$i.'. uid: '.$u[$i]["id"];
        // echo '<div>'.$u[$i]["fullname"].'</div>';

        if (isset($ts[$u[$i]['id']])) {

            $fullname = $u[$i]["fullname"];
            $co = 0;
            $no++;

            // Timesheets for this member
            $tsX = $ts[$u[$i]['id']];
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
                    <?= $fullname ?>
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