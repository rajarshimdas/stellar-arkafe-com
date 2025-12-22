<table class="tabulation" style="width: 600px; margin: auto;">
    <thead>
        <tr class="bannerRow">
            <td colspan="3" style="text-align: center;">
                <div style="font-weight: bold; padding:4px;">Firmwide Projects Timesheet Summary</div>
                <span>Month: <?= $month ?></span>
            </td>
        </tr>
        <tr class="headerRow">
            <td class="headerRowCell1" style="width: 45px;text-align: center;">No</td>
            <td class="headerRowCell2" style="width: 450px;">Project</td>
            <td class="headerRowCell2" style="text-align: center;">Manhours</td>
        </tr>

    </thead>
    <tbody>
        <?php
        $no = 1;
        $gt_min = 0;

        foreach ($pX as $p):

            $pid = $p["pid"];

            if ($pid < 500) {
                $pname = $p["projectname"];
            } else {
                $pname = $p["jobcode"] . ' - ' . $p["projectname"];
            }

            $total_min = $ts[$pid]["total_min"];
            if ($total_min > 0) {

                $gt_min = $gt_min + $total_min;
                $mh = bdMinutes2Manhours($total_min);
        ?>
                <tr class="dataRow">
                    <td class="dataRowCell1" style="text-align: center;"><?= $no++ ?></td>
                    <td class="dataRowCell2"><?= $pname ?></td>
                    <td class="dataRowCell2" style="text-align: center;"><?= $mh ?></td>
                </tr>
        <?php
            }
        endforeach
        ?>
        <tr class="footerRow">
            <td style="text-align: right;" colspan="2">Total</td>
            <td style="text-align: center;"><?= bdMinutes2Manhours($gt_min) ?></td>
        </tr>
    </tbody>
</table>