<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 09-Dec-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
| Require: $formTitle                                   |
+-------------------------------------------------------+
*/

$today = date("Y-m-d");

$fdt = isset($_SESSION["dateRangeFrom"]) ? $_SESSION["dateRangeFrom"] : date("Y-m-d", mktime(0, 15, 0, date("m"), date("d") - 30, date("Y")));
$tdt = isset($_SESSION["dateRangeTo"]) ? $_SESSION["dateRangeTo"] : $today;
?>

<style>
    table#setDateRange {
        width: 100%;
        background-color: whitesmoke;
    }

    table#setDateRange tr td {
        text-align: left;
    }
</style>

<table id="setDateRange">
    <tr style="height: 70px;">
        <td style="width: 15px;"></td>
        <td>
            <?= '<h3>' . $formTitle . '</h3><span style="color:gray">From ' . bdDateMysql2Cal($fdt) . ' to ' . bdDateMysql2Cal($tdt) . '</span>' ?>
        </td>
        <td style="width:120px;">
            <label for="fdt">From date</label>
            <input type="date" name="fdt" id="fdt" min="2024-04-01" max="<?= $today ?>" value="<?= $fdt ?>">
        </td>
        <td style="width:120px;">
            <label for="tdt">To date</label>
            <input type="date" name="tdt" id="tdt" min="2024-04-01" max="<?= $today ?>" value="<?= $tdt ?>">
        </td>
        <td style="width:80px;">
            <label for="ms">&nbsp;</label>
            <input type="submit" id="ms" value="Set" onclick="setDateRange()">
        </td>
        <td style="width:15px;"></td>
    </tr>
</table>

<script>
    function setDateRange() {

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi"
        var formData = new FormData()
        formData.append("a", "concert-api-setActiveDateRange")
        formData.append("fdt", e$('fdt').value)
        formData.append("tdt", e$('tdt').value)

        bdPostData(apiUrl, formData).then((response) => {

            console.log(response);

            if (response[0] == "T") {
                console.log("Added.")
                window.location.reload()
            } else {
                dxAlertBox("Timesheet Add Error", response[1])
            }
        });
    }
</script>