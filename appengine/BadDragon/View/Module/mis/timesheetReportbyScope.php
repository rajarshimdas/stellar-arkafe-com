<style>
    .tabulation tr td {
        text-align: center;
    }
</style>

<table class="tabulation">
    <tr class="bannerRow">
        <td colspan="7">
            <div><?= $pname ?></div>
            <span><?= 'From ' . bdDateMysql2Cal($fdt) . ' to ' . bdDateMysql2Cal($tdt) ?></span>
        </td>
    </tr>
    <tr class="headerRow">
        <td style="width: 50px;">No</td>
        <td style="width: 80px;">Scope</td>
        <td style="width: 80px;">Milestone</td>
        <td style="text-align:left;">Team Members</td>
        <td style="width: 80px;">Manhours</td>
        <td style="width: 80px;">Manhours<br>Milestone</td>
        <td style="width: 80px;">Manhours<br>Scope</td>
    </tr>

    <?php
    $no = 1;
    $gth = 0;
    $gtm = 0;

    foreach ($pscope as $sc):

        $scId = $sc['id'];
        $scNm = $sc['scope'];
        $firstRowScope = 0;

        if (isset($tsX[$scId])):
            $tsScopeX = $tsX[$scId];
            // die(var_dump($tsScopeX));

            // https://stackoverflow.com/questions/9062770/multi-dimensional-array-count-in-php
            // $totalTickets = array_sum(array_map("count", $tickets));
            $scCount = array_sum(array_map("count", $tsScopeX));
            // echo '<br>++' . $scNm . ' | ' . $scCount;

    ?>
            <tr>
                <td rowspan="<?= $scCount ?>"><?= $no++ ?></td>
                <td rowspan="<?= $scCount ?>"><?= $scNm ?></td>


        <?php
            foreach ($pstage as $st):

                $stId = $st['id'];
                $stNm = $st['sname'];

                $firstRowStage = 0;

                if (isset($tsScopeX[$stId])):

                    $tsStageX = $tsScopeX[$stId];
                    $stCount = count($tsStageX);
                    // die(var_dump($tsStageX));

                    //echo '<br>-- ' . $stNm . ' | ' . $stCount;
                    if ($firstRowScope > 0) echo '<tr>';

                    if ($firstRowStage < 1) {
                        echo '<td rowspan="' . $stCount . '">' . $stNm . '</td>';
                    }

                    foreach ($usersX as $u) {

                        $uid = $u['user_id'];
                        $name = $u['displayname'];
                        // echo "<br>$uid | $name";

                        if (isset($tsStageX[$uid]['mh'])) {

                            if ($firstRowStage > 0) echo '<tr>';

                            echo '<td style="text-align:left;">' . $name . '</td>';
                            echo '<td>' . $tsStageX[$uid]['mh'] . '</td>';

                            $gth += $tsStageX[$uid]['h'];
                            $gtm += $tsStageX[$uid]['m'];

                            // Last 2 columns
                            if ($firstRowScope < 1) {
                                echo '<td rowspan="' . $stCount . '">' . bdAddHourMin($stageTo[$scId][$stId]['h'], $stageTo[$scId][$stId]['m']) . '</td>';
                                echo '<td rowspan="' . $scCount . '">' . bdAddHourMin($scopeTo[$scId]['h'], $scopeTo[$scId]['m']) . '</td>';
                                $firstRowStage++;
                            } elseif ($firstRowStage < 1) {
                                echo '<td rowspan="' . $stCount . '">' . bdAddHourMin($stageTo[$scId][$stId]['h'], $stageTo[$scId][$stId]['m']) . '</td>';
                                $firstRowStage++;
                            }

                            echo '</tr>';

                            $firstRowScope++;
                        }
                    }
                endif;
            endforeach;
        endif;
    endforeach;

        ?>
        <!-- -->
            <tr class="footerRow">
                <td style='text-align: right;' colspan="6">Total</td>
                <td style=''><?= bdAddHourMin($gth, $gtm) ?></td>
            </tr>

</table>
<div>&nbsp;</div>