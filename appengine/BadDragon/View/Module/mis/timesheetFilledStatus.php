<?php
// var_dump($status);
$no = 1;
?>
<style>
    .tabulation {
        font-size: 1em;
        width: 100%;
        overflow-x: scroll;
    }

    .tabulation tr td {
        border-bottom: 1px solid gray;
        text-align: center;
        min-width: 60px;
    }

    .tabulation tr:hover {
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
        <tr style="background-color: gray; color: white;">
            <td width='30px'>No</td>
            <td>Name</td>
            <td width='90px'>
                <div>Today</div>
                <div><?= $dateX[0] ?></div>
            </td>
            <td width='90px'>
                <div>Yesterday</div>
                <div><?= $dateX[1] ?></div>
            </td>
            <?php
            for ($i = 2; $i < count($dateH); $i++) {
                echo "<td width='90px'><div>" . $dateH[$i] . "</div><div>" . $dateX[$i] . "</div></td>";
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