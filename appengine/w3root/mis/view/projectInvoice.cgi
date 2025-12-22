<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 05-Mar-2024				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
?>
<div>
    <h3>Project Current Milestone Status  (<?= date("d-M-Y") ?>)</h3>
</div>
<?php

$query = "select 
            t1.projectname,
            t2.name as milestone,
            t2.stageno as milestone_no
        from
            projects as t1,
            projectstage as t2
        where
            t1.currentstage_id = t2.id and
            t1.active = 1
        order by
            t1.projectname;";


$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $projX[] = $row;
}

// var_dump($projX);

?>
<style>
    tr td {
        text-align: center;
    }

    tr td:nth-child(2),
    tr td:nth-child(4) {
        text-align: left;
        padding-left: 10px;
    }
</style>
<table cellpadding="0" cellspacing="0" style="width: 1000px;">
    <tr>
        <td class="headerRowCell1" width="35px">No</td>
        <td class="headerRowCell2">Project</td>
        <td class="headerRowCell2" width="125px">Milestone No</td>
        <td class="headerRowCell2" width="200px">Milestone</td>
    </tr>
    <?php
    for ($i = 0; $i < count($projX); $i++) {
        echo "<tr>
            <td class='dataRowCell1'>" . ($i + 1) . "</td>
            <td class='dataRowCell2'>" . $projX[$i]['projectname'] . "</td>
            <td class='dataRowCell2'>" . $projX[$i]['milestone_no'] . "</td>
            <td class='dataRowCell2'>" . $projX[$i]['milestone'] . "</td>
        </tr>";
    }
    ?>
</table>