<?php
$displaySelectProject = 0;

$dtY = json_decode($activeFinancialYear, true);
// var_dump($dtY);

$users = bdGetUsersArray($mysqli);

$mhCost = bdManhourCost($mysqli);
// var_dump($mhCost);
?>
<style>
    .tabulation tr td {
        padding: 0px 10px;
    }

    .tabulation tr td:nth-child(1),
    .tabulation tr td:nth-child(5),
    .tabulation tr td:nth-child(6),
    .tabulation tr td:nth-child(8) {
        text-align: center;
    }
</style>
<table class="tabulation" style="width: 100%; border-top: 1px solid var(--rd-nav-dark);">
    <thead>
        <tr style="background-color: var(--rd-nav-dark);">
            <td colspan="6" style="text-align:center;color:white; border: 1px solid var(--rd-nav-dark);">Financial Year: <?= $dtY['name'] ?></td>
            <td style="text-align: right; color: white; border: 1px solid var(--rd-nav-dark);">Ex-employees</td>
            <td style="border: 1px solid var(--rd-nav-dark);">
                <?php
                // sysShowDeletedUser (Show: 1 | Hide: 0)
                if ($sysShowDeletedUser < 1) {
                    echo '<a class="button" style="width:40px;" onclick="sysShowDeletedUser(\'1\')">Show</a>';
                } else {
                    echo '<a class="button" style="width:40px;" onclick="sysShowDeletedUser(\'0\')">Hide</a>';
                }
                ?>
            </td>
        </tr>

        <tr>
            <td style="width:50px;">No</td>
            <td>Team member</td>
            <td style="width:200px;">Designation</td>
            <td style="width:200px;">Reports To</td>
            <td style="width:120px;">DOJ</td>
            <td style="width:120px;">DOE</td>
            <td style="width:120px;border-right:0px;text-align:right;">Manhour Cost</td>
            <td style="width:80px;"></td>
        </tr>
    </thead>

    <?php
    // var_dump($users);

    $no = 1;
    foreach ($users as $u):
        if ($sysShowDeletedUser < 1) {
            // hide in-active users
            if ($u['active'] > 0) {
                dataRow($no, $u, $mhCost, $dtY['finStartYear']);
                $no++;
            }
        } else {
            // show all users
            dataRow($no, $u, $mhCost, $dtY['finStartYear']);
            $no++;
        }
    endforeach;
    ?>
</table>


<?php
function dataRow($no, $u, $mhCost, $finStartYear)
{

    $cost = isset($mhCost[$finStartYear][$u['user_id']]['costRs']) ?
        number_format($mhCost[$finStartYear][$u['user_id']]['costRs'], 2, '.', ',') :
        0;
?>
    <tr>
        <td><?= $no ?></td>
        <td style="font-weight: bold;"><?= $u['displayname'] ?></td>
        <td><?= $u['hrgroup'] ?></td>
        <td><?= $u['reports_to'] ?></td>
        <td><?= $u['doj'] ?></td>
        <td><?= $u['doe'] ?></td>
        <td id="mh-<?= $u['user_id'] ?>" style="border-right:0px;text-align:right;">
            <?= $cost ?>
        </td>
        <td>
            <button class="button"
                onclick="dxUserManhourCost(
                    '<?= $u['user_id'] ?>', 
                    '<?= $u['displayname'] ?>',
                    '<?= isset($mhCost[$finStartYear][$u['user_id']]['rs']) ? $mhCost[$finStartYear][$u['user_id']]['rs'] : 0; ?>',
                    '<?= isset($mhCost[$finStartYear][$u['user_id']]['ps']) ? $mhCost[$finStartYear][$u['user_id']]['ps'] : 0; ?>')">Edit</button>
        </td>
    </tr>
<?php
}
?>

<dialog id="dxUserManhourCost">
    <table>
        <tr>
            <td style="font-weight: bold;">Manhour Cost</td>
            <td><label for="dxUserMHCostRs">Rupees</label></td>
            <td><label for="dxUserMHCostPs">Paise</label></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td id="dxUserNm" style="text-align:right;width: 250px;"></td>
            <td style="width:80px;">
                <input type="hidden" id="dxUserId">
                <input type="text" name="dxUserMHCostRs" id="dxUserMHCostRs" placeholder="Rupees">
            </td>
            <td style="width:80px;">
                <input type="text" name="dxUserMHCostPs" id="dxUserMHCostPs" placeholder="Paise">
            </td>
            <td style="width:80px;">
                <button class="button" style="width: 100%;" onclick="setUserManhourCost()">Save</button>
            </td>
            <td style="width:40px;text-align:center;">
                <img class="fa5button" src="<?= BASE_URL ?>da/fa5/window-close.png" alt="Close" onclick="dxClose('dxUserManhourCost')">
            </td>
        </tr>
    </table>
</dialog>

<script>
    function setUserManhourCost() {

        var uid = e$("dxUserId").value

        var formData = new FormData()
        formData.append("a", "cost-api-setUserManhourCost")
        formData.append("dxUserId", uid)
        formData.append("finStartYear", '<?= $dtY['finStartYear'] ?>')
        formData.append("dxUserMHCostRs", e$("dxUserMHCostRs").value)
        formData.append("dxUserMHCostPs", e$("dxUserMHCostPs").value)

        bdPostData(apiUrl, formData).then((response) => {

            if (response[0] == "T") {
                window.location.reload()
                //e$("mh-" + uid).innerHTML = response[1]
                //e$("dxUserManhourCost").close()
            } else {
                dxAlertBox("Error", response[1])
            }
        });
    }

    function dxUserManhourCost(dxUserId, dxUserNm, rs, ps) {

        e$('dxUserId').value = dxUserId
        e$("dxUserNm").innerHTML = dxUserNm
        e$("dxUserMHCostRs").value = rs
        e$("dxUserMHCostPs").value = ps

        e$("dxUserManhourCost").showModal()
    }


    function sysShowDeletedUser(flag) {

        // console.log('sysShowDeletedUser: flag', flag)
        var formData = new FormData()
        formData.append("a", "sysadmin-api-setShowDeletedUser")
        formData.append("flag", flag)

        bdPostData(apiUrl, formData).then((response) => {

            if (response[0] == "T") {
                window.location.reload()
            } else {
                dxAlertBox("Error", response[1])
            }
        });
    }
</script>