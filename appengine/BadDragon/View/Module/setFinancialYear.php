<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 14-Mar-2025 Holi                          |
| Updated On:                                           |
+-------------------------------------------------------+
| Require: $activeFinancialYear                         |
+-------------------------------------------------------+
*/
// rx($activeFinancialYear);

$finYear = json_decode($activeFinancialYear, true);

$thisFinYear = date('Y');
$uptoFinYear = 2024;

function dxSetActiveFinYear()
{
    // echo "<button class='button' onclick='e$(\"dxSetActiveFinYear\").showModal()'>Change</button>";
    echo "<img class='fa5button' src='" . BASE_URL . "da/fa5/edit.png' alt='Set Financial Year' onclick='e$(\"dxSetActiveFinYear\").showModal()'>";
}

?>

<dialog id="dxSetActiveFinYear">
    <table class='dxTable' style="width:300px;">
        <tr>
            <td style="font-weight:bold;" colspan="2">
                Set Financial Year
            </td>
            <td style="width:50px;text-align:right;">
                <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" onclick="dxClose('dxSetActiveFinYear')">
            </td>
        </tr>
        <tr>
            <td style="width:120px;text-align:right;">
                Financial Year
            </td>
            <td style="padding: 0px 4px;">
                <select name="finStartYear" id="finStartYear" style="width:100%;">
                    <option value="<?= $finYear['finStartYear'] ?>">
                        <?= $finYear['name'] ?>
                    </option>
                    <?php
                    while ($thisFinYear >= $uptoFinYear) {

                        $fy = bdFinancialYear($thisFinYear . date("-m-d"));
                        if ($fy['name'] != $finYear['name']) {
                            echo "<option value='" . $fy['finStartYear'] . "'>" . $fy['name'] . "</option>";
                        }
                        $thisFinYear--;
                    }
                    ?>
                </select>
            </td>
            <td>
                <button class="button" onclick="setsActiveFinYear()">Set</button>
            </td>
        </tr>
    </table>
</dialog>
<script>
    function setsActiveFinYear() {

        let finStartYear = e$("finStartYear").value
        rx("setsActiveFinYear: " + finStartYear)

        var formData = new FormData()
        formData.append("a", "cost-api-setActiveFinYear")
        formData.append("finStartYear", finStartYear)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response);
            if (response[0] == "T") {
                window.location.reload()
            } else {
                dxAlertBox("Timesheet Add Error", response[1])
            }
        });
    }
</script>