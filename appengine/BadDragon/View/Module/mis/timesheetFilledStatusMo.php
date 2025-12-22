<?php
// var_dump($status);
$no = 1;
?>
<style>
    table.tabulation {
        font-size: 1em;
        width: 100%;
        overflow-x: scroll;
    }

    table.tabulation tr td {
        border-bottom: 1px solid gray;
        text-align: center;
        min-width: 80px;
        line-height: 1.2em;
        vertical-align: middle;
    }

    table.tabulation tr:hover {
        background-color: #d9d9d9;
    }

    .datarows td:nth-child(2) {
        text-align: left;
        background-color: #d9d9d9;
        padding-left: 10px;
    }

    /* Sundays and Holidays */
    <?php
    require_once $w3root . '/studio/tms/hot7e/getHolidayList.php';
    $h = getHolidayList(0, $mysqli);

    for ($i = 0; $i < $nod; $i++) {
        if ($dateX[$i] == "Sun" || isset($h[$dateM[$i]])) {
            echo ".datarows td:nth-child(" . ($i + 3) . ") { background-color: #d9d9d9; }";
        }
    }
    ?>

</style>
<table class="tabulation">
    <thead class="stickyTop">
        <tr style="background-color: #f7eeaa;">
            <td width='5%'>No</td>
            <td>Name</td>
            <?php
            for ($i = 0; $i < count($dateH); $i++) {
                echo "<td width='9%'><div>" . $dateH[$i] . "</div><div>" . $dateX[$i] . "</div></td>";
            }
            ?>
        </tr>
    </thead>
    <?php
    for ($e = 0; $e < count($status); $e++) {
    ?>
        <tr class="datarows">
            <td><?= $no++ ?></td>
            <td><?= $status[$e]["name"] ?></td>
            <?php
            for ($i = 0; $i < count($status[$e]["dayMH"]); $i++) {
                echo "<td>" . $status[$e]['dayMH'][$i] . "</td>";
            }
            ?>
        </tr>
    <?php
    }
    ?>
    <tr>
        <td colspan="9"><?= $companyname ?></td>
    </tr>
</table>