<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   14-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/
?>
<style>
    button.button-18a:disabled {
        background-color: white;
        border-color: gray;
        color: gray;
    }

    tr.emoji-1 {
        background-color: lightgoldenrodyellow;
    }

    tr.emoji-2 {
        background-color: #baf3ba;
    }

    tr.emoji-3 {
        background-color: #f9bebe;
    }
</style>
<?php
# date range defined in Controller Hrms.php
// $dateRangeStart = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 60, date('Y')));
// $dateRangeEnd = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 90, date('Y')));


$holidays = getSundaysInRange($dateRangeStart, $dateRangeEnd);
$hX = bdGetHolidayListInRange($dateRangeStart, $dateRangeEnd, $mysqli);

foreach ($hX as $h) {
    $holidays[] = $h['dt'];
    // array_push($holidays, $h['dt']);
}

$leaveTypes = bdGetLeaveTypes($mysqli);
//var_dump($leaveTypes);

$leaveRec = bdGetUserLeaveRecords($uid, $year, $mysqli);
// var_dump($leaveRec);

$leaveReqById = [];
foreach ($leaveRec as $r) {
    $leaveReqById[$r['id']] = $r;
}


$fullDayLeaves = [];
$halfDayLeaves = [];
$applied_nod = 0;

foreach ($leaveRec as $n) {

    // skip Revoked or Deleted
    if (($n['revoke'] > 0) || ($n['active'] < 1)) continue;

    $sdt = $n['from_dt'];
    $edt = $n['end_dt'];
    $leave_type_id = $n['leave_type_id'];
    $status_id = $n['status_id'];

    # Count Leave Applied, Not approved
    if ($status_id == 5) $applied_nod += $n['nod_units'];

    # Single day
    if ($sdt == $edt) {
        if ($n['from_dt_units'] == 'F') {
            $fullDayLeaves[] = $sdt;
        } else {
            $halfDayLeaves[] = $sdt;
        }
        continue;
    }

    # Multiple days

    // Weed out half days
    if ($n['from_dt_units'] != 'F') {
        // echo 'sdt: Half';
        $halfDayLeaves[] = $sdt;
        $sdt = getNextDate($sdt);
    }

    if ($n['end_dt_units'] != 'F') {
        // echo 'edt: Half ' . $n['end_dt_units'];
        $halfDayLeaves[] = $edt;
        $edt = getPreviousDate($edt);
    }

    $fullDayLeaves[] = getAllDatesInRange($sdt, $edt);
}
$fullDayLeaves = flattenArray($fullDayLeaves);
// var_dump($fullDayLeaves);

require_once __DIR__ . '/dxApplyLeave.php';
require_once __DIR__ . '/dxShortLeave.php';
require_once __DIR__ . '/dxRevokeLeave.php';
require_once __DIR__ . '/dxDeleteLeave.php';

// Set status as approved
$query = "UPDATE `rd_leave_records` 
            SET 
                `emoji` = '0'
            WHERE 
                `emoji` > 1";

$mysqli = cn2();
if (!$mysqli->query($query)) {
    die(bdReturnJSON(["F", "System error[1] in leave Revoke Reject."]));
}

?>

<div style="background-color:cadetblue;width:100%;max-width:var(--rd-max-width);margin:auto;">
    <table style="width:100%;max-width:var(--rd-max-width);">
        <tr>
            <td style="padding:10px;width:720px;">
                <?php
                $thisUId = $uid;
                $leave_type_id = 2;

                // Widget - Leave Card
                require_once W3APP . '/View/Widgets/wxLeaveCard.php';
                ?>
            </td>
            <td></td>
            <td style="width:360px;text-align:center;">
                <button class="button-18 button-18a"
                    <?php
                    $shortLeaveNod = bdShortLeaveNod($uid, $mysqli);
                    if ($shortLeaveNod > 0) echo 'disabled';
                    ?>
                    onclick="dxApplyShortLeave()">
                    Short Leave | <?= $shortLeaveNod ?>
                </button>
                <button class="button-18" onclick="dxApplyLeave()">Apply Leave</button>
            </td>
        </tr>
    </table>
</div>

<table class='rd-table'>
    <thead>
        <tr>
            <td rowspan="2" style="width: 35px;">No</td>
            <td rowspan="2" style="width: 100px;">Applied On</td>
            <td colspan="2" style="width: 200px;">Leave</td>
            <td rowspan="2" style="width: 150px;">Type</td>
            <td rowspan="2" style="width: 80px;">Days</td>
            <td rowspan="2">Reason</td>
            <td rowspan="2" style="width: 140px;border-right:0px;text-align:left;">Status</td>
            <td rowspan="2" style="width: 180px;border-left:0px;"><!-- buttons --></td>
        </tr>
        <tr>
            <td style="width:100px;">From</td>
            <td style="width:100px;">To</td>
        </tr>
    </thead>
    <?php
    $no = 1;
    foreach ($leaveRec as $x):
    ?>
        <tr class="emoji-<?= $x['emoji'] ?>">
            <td><?= $no++ ?></td>
            <td><?= $x['dt_applied'] ?></td>
            <td><?= $x['dt_from'] . ' ' . $x['from_dt_units'] ?></td>
            <td><?= $x['dt_end'] . ' ' . $x['end_dt_units'] ?></td>
            <td style="text-align: left;"><?= $x['attribute'] ?></td>
            <td><?= (float)$x['nod_units'] ?></td>
            <td style="text-align: left;"><?= $x['reason'] ?></td>
            <td style="border-right:0px;text-align:left;"><?= $x['status'] ?></td>
            <td style="border-left:0px;text-align:right;">
                <?php if ($x['status_id'] == 10): ?>
                    <button class="button-18" onclick="dxRevokeLeave(<?= $x['id'] ?>)">Revoke</button>
                <?php elseif ($x['status_id'] == 5): ?>
                    <button class="button-18" onclick="dxDeleteLeave(<?= $x['id'] ?>)">Delete</button>
                <?php endif ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
    /* Leave Types */
    <?php
    $leaveTypesArray = 'const leaveTypes = [';
    foreach ($leaveTypes as $t) {
        if ($t['is_normal'] > 0 && $t['active']) {

            $t5Id = $t['id'];
            $t5Nm = $t['type'];
            // [ id, type, entitlement ]
            $leaveTypesArray = $leaveTypesArray . "[ '$t5Id', '$t5Nm', '5' ],";
        }
    }
    $leaveTypesArray = $leaveTypesArray . "];";

    echo $leaveTypesArray;
    ?>

    /* Leave applications */
    var leaves = [];
    <?php
    foreach ($leaveRec as $x) {
        echo 'leaves[' . $x['id'] . '] = ["' . $x['dt_applied'] . '", "' . $x['reason'] . '"]; ';
    }
    ?>

    /* Leave Revoke reason */
    var revokeRx = [];
    <?php
    $co = empty($revokeRx) ? 0 : count($revokeRx);
    for ($i = 0; $i < $co; $i++) {
        echo 'revokeRx[' . $i . '] = "' . $revokeRx[$i] . '"; ';
    }
    ?>

    const dxRevokeLeaveBox = e$("dxRevokeLeave")

    let revokeLeaveId = 0

    const revokeLeave = () => {
        console.log('revokeLeave: ' + revokeLeaveId)

        revokeRxTxt = revokeRx[e$('dxRevokeRx').value]
        // console.log('dxRevokeRx id: ' + revokeRxTxt)

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveRevoke")
        formData.append('leaveId', revokeLeaveId)
        formData.append('dxRevokeOp', e$('dxRevokeRx').value)
        formData.append('dxRevokeRx', revokeRxTxt)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)

            if (response[0] != "T") {
                // Error
                e$(response[2]).focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = response[1]
                e$("dxMessageBox").showModal()

            } else {
                // Success
                window.location.reload()
            }
        });
    }

    function dxRevokeLeave(leaveId) {

        revokeLeaveId = leaveId

        var lx = leaves[leaveId]
        console.log('dxRevokeLeave: ' + lx)

        e$('dxAppliedOn').innerHTML = lx[0]
        e$('dxReason').innerHTML = lx[1]

        dxRevokeLeaveBox.showModal()
    }


    /*
    +-------------------------------------------------------+
    | Working Data                                          |
    +-------------------------------------------------------+
    */
    const dateRange = [
        '<?= $dateRangeStart ?>',
        '<?= $dateRangeEnd ?>',
    ];
    const holidays = <?= empty($holidays) ? '[]' : json_encode($holidays) ?>;
    const leaveRec = <?= empty($leaveRec) ? '[]' : json_encode($leaveRec) ?>;
    const fullDayLeaves = <?= empty($fullDayLeaves) ? '[]' : json_encode($fullDayLeaves) ?>;
    const halfDayLeaves = <?= empty($halfDayLeaves) ? '[]' : json_encode($halfDayLeaves) ?>;
    const leaveReqById = <?= json_encode($leaveReqById) ?>;
</script>