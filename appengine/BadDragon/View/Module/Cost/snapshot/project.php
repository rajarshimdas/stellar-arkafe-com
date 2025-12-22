<?php
$displaySelectProject = 1;
/*
+-------------------------------------------------------+
| Date range selection form                             |
+-------------------------------------------------------+
*/
$formTitle = 'Project Cost';

?>
<table style="width: 100%; margin:auto;max-width:var(--rd-max-width);background-color: whitesmoke;">
    <tr>
        <td style="width: 650px;">
            <?php require BD . 'View/Module/setDateRange.php'; ?>
        </td>
        <td style="text-align: left;">
            <label for="pid" style="padding: 0 15px;">Select a Project</label>
            <?php require BD . 'View/Module/setActiveProject.php'; ?>
        </td>
        <td style="width:200px;">
            <label for="dxBtn">&nbsp;</label><br>
            <!-- <button class="button" id="dxBtn" onclick="generateReport(<?= $pid . ',\'' . $fdt . '\',\'' . $tdt . '\'' ?>)">Generate Report</button> -->
            <a class="button" href="<?= BASE_URL ?>cost/ui/snapshot/project/cost">Generate Report</a>
        </td>
    </tr>
</table>

<?php
// var_dump($route); 
if (!empty($route->parts[4])) {
    $fx = BD . "View/Module/Cost/snapshot/" . $route->parts[4] . ".php";
    // echo "fx: $fx";

    if (is_file($fx)) {
        require_once $fx;
    }
}
?>

<style>
    table.tabulation tr td {
        padding: 0px 8px;
        vertical-align: top;
        line-height: 35px;
    }

    table.tabulation tfoot tr td {
        border: 0px;
        font-weight: bold;
    }
</style>
<table id="rxtbl" class="tabulation" style="width:100%;visibility:hidden;">
    <thead>
        <tr class="headerRow">
            <td>Project</td>
            <td style="width:450px;">Team</td>
            <td style="width:150px;text-align:right;">Manhours</td>
            <td style="width:150px;text-align:right;">Cost/Hour</td>
            <td style="width:150px;text-align:right;">Cost</td>
        </tr>
    </thead>
    <tbody id="rxtblrows"></tbody>
    <tfoot>
        <tr>
            <td colspan="2">Total</td>
            <td style="text-align:right;" id="totalMH"></td>
            <td></td>
            <td style="text-align:right;" id="totalCost"></td>
        </tr>
    </tfoot>
</table>

<script>
    function generateReport(pid, fdt, tdt) {
        rx('generateReport: ' + pid)

        var formData = new FormData()
        formData.append("a", "cost-api-misProjectCost")
        formData.append('pid', pid)
        formData.append('fdt', fdt)
        formData.append('tdt', tdt)

        bdPostData(apiUrl, formData).then((response) => {

            console.log(response);

            if (response[0] == "T") {

                e$('rxtblrows').innerHTML = response[1]
                e$('totalCost').innerHTML = response[2]
                e$('totalMH').innerHTML = response[3]

                e$("rxtbl").style.visibility = 'visible'

            } else {
                dxAlertBox("Generate Report", response[1])
            }
        });

    }
</script>