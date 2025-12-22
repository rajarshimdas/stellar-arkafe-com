<?php
# Snapshot :: dialog box
# Created: 08-May-2024
# Updated: 31-May-25
#

function minAccrued(string $mh): ?int
{
    $x = explode(":", $mh);
    return ($x[0] * 60) + $x[1];
}

?>

<style>
    .dataRow {
        background-color: white;
    }

    .dataRow td {
        border: 1px solid gray;
        vertical-align: top;
        line-height: 35px;
    }
</style>

<dialog id="dxTask">
    <table class='dxTable' style="width:650px;">
        <thead>
            <tr>
                <td style="font-weight: bold;">
                    Timesheets
                </td>
                <td style="width: 30px;">
                    <img class="fa5button" src="<?= BASE_URL ?>da/fa5/window-close.png" onclick="dxCloseTask('dxTask')">
                </td>
            </tr>
        </thead>
        <tr>
            <td colspan="2">
                <div id="dxWork" style="padding: 10px;"></div>
                <table class="tabulation" style="width: 100%; text-align:center;">
                    <tr class="headerRow">
                        <td class="headerRowCell1" style="width: 8%;">No</td>
                        <td class="headerRowCell2" style="text-align: left;">Teammate</td>
                        <td class="headerRowCell2" style="width: 16%;">Date</td>
                        <td class="headerRowCell2" style="width: 16%;">Completed %</td>
                        <td class="headerRowCell2" style="width: 16%;">Manhours</td>
                        <td class="headerRowCell2" style="width: 16%;">Total</td>
                    </tr>
                    <tbody id="dxTaskTable"></tbody>
                </table>
            </td>
        </tr>
    </table>
</dialog>
