<?php

require_once __DIR__ . '/manage-ux.php';

$usersById = bdGetUsersById($mysqli);
// var_dump($usersById);
$subs = bdGetSubordinates($uid, $mysqli);
$leaveReq = bdGetLeaveRequestForUsers($subs, $mysqli);
// var_dump($leaveReq);

// Counts
$co_log = empty($leaveReq) ? 0 : count($leaveReq);
$co_app = 0; // Approval | status_id: 5
$co_rev = 0; // Revoke | status_id: 30

foreach ($leaveReq as $x) {
    if ($x['status_id'] == 5) {
        $co_app++;
    } elseif ($x['status_id'] == 30) {
        $co_rev++;
    }
}

// Get users leave status
// $leaveStats = bdGetAllUserLeaveEntitelments($mysqli);
// var_dump($leaveStats);
?>

<div id="navbox">
    Leave Requests
    <button class='active'>Approval | <?= $co_app ?></button>
    <button onclick="go2('aec/hrms/ui/leaves/manage-rev')">Revoke | <?= $co_rev ?></button>
    <button onclick="go2('aec/hrms/ui/leaves/manage-log')">Log | <?= $co_log ?></button>
</div>
<table class='rd-table'>
    <thead>
        <tr>
            <td rowspan="2" style="width: 35px;">No</td>
            <td rowspan="2" style="width: 100px;">Name</td>
            <td rowspan="2" style="width: 100px;">Applied On</td>
            <td colspan="2" style="width: 200px;">Leave</td>
            <td rowspan="2" style="width: 150px;">Type</td>
            <td rowspan="2" style="width: 80px;">Days<!--<br>(Balance)--></td>
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
    foreach ($leaveReq as $x):
        if ($x['status_id'] == 5):
            $noOfDays = $x['nod_units'];
    ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="hotKey" style="text-align: left;" onclick="viewManageEmp(<?= $x['user_id'] ?>)">
                    <?= $usersById[$x['user_id']]['displayname'] ?>
                </td>
                <td><?= $x['dt_applied'] ?></td>
                <td><?= $x['dt_from'] . ' ' . $x['from_dt_units'] ?></td>
                <td><?= $x['dt_end'] . ' ' . $x['end_dt_units'] ?></td>
                <td style="text-align: left;"><?= $x['attribute'] ?></td>
                <td><?= (float)$x['nod_units'] /*. ' (' . (float)$leaveStats[$x['user_id']][$x['leave_type_id']]['entNod'] . ')'*/ ?></td>
                <td style="text-align: left;"><?= $x['reason'] ?></td>
                <td style="border-right:0px;text-align:left;"><?= $x['status'] ?></td>
                <td style="border-left:0px;text-align:right;">
                    <button class="button-18" onclick="leaveApprove('<?= $x['id'] ?>', '<?= $x['leave_attr_id'] ?>', '<?= $noOfDays ?>')" <?php if ($x['status_id'] == 1) echo 'disabled'; ?> title="Approve">A</button>
                    <!-- Enabled editing leave type -->
                    <button class="button-18" onclick="leaveApproveDx('<?= $x['id'] ?>', '<?= $x['leave_attr_id'] ?>', '<?= $usersById[$x['user_id']]['displayname'] ?>', '<?= $noOfDays ?>')" <?php if ($x['status_id'] == 1) echo 'disabled'; ?> title="Overrride">O</button>
                    <button class="button-18" onclick="leaveReject('<?= $x['id'] ?>')" <?php if ($x['status_id'] == 2) echo 'disabled'; ?> title="Reject">R</button>
                </td>
            </tr>
    <?php
        endif;
    endforeach;
    ?>
</table>
<?php
include __DIR__ . '/dxApproveLeave.php';
// rx($x);
?>

<script>
    function leaveApprove(leaveId, attrId, noOfDays) {
        console.log('leaveApprove: ' + leaveId)

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveApprove")
        formData.append('leaveId', leaveId)
        formData.append('attrId', attrId)
        formData.append('noOfDays', noOfDays)
        formData.append('log', 'Approved')

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                // e$(response[2]).focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = response[1]
                e$("dxMessageBox").showModal()

            } else {
                // Success
                window.location.reload()
            }
        })

    }


    function leaveReject(leaveId) {
        console.log('leaveReject: ' + leaveId)

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveReject")
        formData.append('leaveId', leaveId)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                // e$(response[2]).focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = response[1]
                e$("dxMessageBox").showModal()

            } else {
                // Success
                window.location.reload()
            }
        })

    }

    function viewManageEmp(UId) {
        window.location = '<?= BASE_URL ?>aec/hrms/ui/leaves/manage-emp/' + UId;
    }
</script>