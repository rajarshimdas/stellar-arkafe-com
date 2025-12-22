
<dialog id="dxViewDay">
    <table class='dxTable' style="width:800px;">
        <tr>
            <td style="font-weight: bold; color: var(--rd-nav-light);">
                Timesheets <span id="dxDate"></span>
            </td>
            <td style="width: 30px;">
                <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" onclick="dxClose('dxViewDay')">
            </td>
        </tr>
    </table>
    <style>
        .tsTable{
            border-collapse: collapse;
        }
        .tsTable tbody tr td {
            border: 1px solid gray;
            padding: 2px 5px;
        }
    </style>
    <table class="tsTable" style="font-size:80%;width:800px;" cellpadding="2" cellspacing="0">
        <tr style="background:#FFF6F4;font-weight:bold;">

            <td class="cellHeaderLeft" width="25%" style="text-align:left;">Project</td>
            <td class="cellHeader" width="20%">Milestone</td>
            <td class="cellHeader" style="text-align:left;">Work</td>
            <td class="cellHeader" width="10%">Percent<br>Completed</td>
            <td class="cellHeader" width="10%">Hours<br>Worked</td>
            
        </tr>
        <tbody id="dxViewDayTs"></tbody>

    </table>
</dialog>

