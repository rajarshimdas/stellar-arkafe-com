<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:						                    |
| Updated On: 21-Apr-08					                |
+-------------------------------------------------------+
| Tabulate the Summary					                |
+-------------------------------------------------------+
*/
function SummaryTabulate($blocks, $blockX, $projectid, $atag, $mysqli)
{

    $stageX = getStageArray($mysqli);
    $co = count($stageX);
    //var_dump($stageX);

    $query = "SELECT * FROM discipline where active > 0 order by displayorder";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $dx[] = $row;
    }

    $query = "SELECT 
                1 as n, 
                stage as stageno, 
                disciplinecode as dc,
                r0issuedflag as r0
            FROM 
                view_drawing_list 
            where 
                project_id = '$projectid'";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $n[$row["dc"]][$row["stageno"]][] = 1;

        // GFC count
        $r0row[$row['dc']] = (isset($r0row[$row['dc']])) ? $r0row[$row['dc']] : 0;
        $r0row[$row['dc']] = ($row["r0"] > 0) ? $r0row[$row['dc']] + 1 : $r0row[$row['dc']];
    }
    // var_dump($n);
?>

    <table class="tabulation" cellspacing="0" width="100%">

        <tr class="headerRow">
            <td class="headerRowCell1" rowspan='2' style='width:30px;'>No</td>
            <td class="headerRowCell2" rowspan='2' style="text-align: left;">Discipline</td>
            <td class="headerRowCell2" colspan="<?= $co ?>" style="text-align: center">Milestone</td>
            <td class="headerRowCell2" rowspan='2' style='width:100px;'>GFC's<br>Released</td>
            <td class="headerRowCell2" rowspan='2' style='width:100px;'>Total</td>
        </tr>

        <tr class="headerRow">
            <?php
            for ($i = 0; $i < $co; $i++) {
                echo '<td class="headerRowCell3a" width="85px" title="' . $stageX[$i]["stage"] . '">
                        <a href="project.cgi?a=t2xsummary-phase&stageid=' . $stageX[$i]["id"] . '" style="line-height:30px;">' . $stageX[$i]["sname"] . '</a>
                    </td>';
            }
            ?>
        </tr>

        <?php

        $srno = 1;
            $r0col = 0;

        for ($e = 0; $e < count($dx); $e++) {

            $d = $dx[$e];
            $dc = $d["disciplinecode"];

            $t = 0;
            $r0 = 0;


            echo "<tr class='dataRow'>
                    <td class='dataRowCell1'>" . $srno++ . "</td>
                    <td class='dataRowCell2a' style='text-align:left;'>
                        <a href='" . BASE_URL . "studio/sms/project.cgi?a=t2xsummary-list&dc=" . $d["discipline"] . "'>" . $d["discipline"] . "</a></td>";

            for ($x = 0; $x < $co; $x++) {

                $s = $stageX[$x];
                $nod = isset($n[$dc][$s["stageno"]]) ? count($n[$dc][$s["stageno"]]) : 0;

                // Row totals
                $t = $t + $nod;
                // Column totals
                $tm[$s["stageno"]] = isset($tm[$s["stageno"]]) ? $tm[$s["stageno"]] + $nod : $nod;

                echo "<td class='dataRowCell2'>" . (($nod > 0) ? $nod : '<!-- 0 -->') . "</td>";

            }

            // GFC Released
            $r0t = 0;
            if (isset($r0row[$dc])) {
                $r0t = ($r0row[$dc] > 0) ? $r0row[$dc] : 0;
                $r0col = $r0col + $r0t;
            }
            echo "<td class='dataRowCell2'>" . (($r0t > 0) ? $r0t : "<!-- 0 -->") . "</td>";

            // Total
            echo "<td class='dataRowCell2'>" . (($t > 0) ? $t : "<!-- 0 -->") . "</td>";

            echo "</tr>";
        }
        ?>
        <tr style="font-weight: bold;">
            <td></td>
            <td style="text-align: left;">Totals</td>
            <?php
            $tno = 0;
            for ($i = 0; $i < $co; $i++) {
                $no = $tm[$stageX[$i]["stageno"]];
                $tno = $tno + $no;
                echo "<td>" . (($no > 0) ? $no : "<!-- 0 -->") . "</td>";
            }
            ?>
            <td><?= $r0col ?></td>
            <td><?= $tno ?></td>
        </tr>
    </table>

<?php
}
?>