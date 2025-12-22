<?php


## Month Selector
##
$monthNo = empty($_SESSION['leaveMISMonth']) ? date('n') : (int)$_SESSION['leaveMISMonth'];
require_once W3APP . '/View/Widgets/fxMonthSelect.php';

// $subs = bdGetSubordinates($uid, $mysqli);

$subs = [];
require_once $APP . '/appengine/w3etc/foo/getTeamList.php';
$team = rdGetMyTeamList($uid, $mysqli);

foreach ($team as $u) {
    $subs[] = $u['uid'];
}



$users = bdGetUsers($mysqli);
$usersById = bdGetUsersById($mysqli);

// var_dump($route);
$leaveReportUserId = empty($route->parts[5]) ? $uid : $route->parts[5];
// Validate
if ($leaveReportUserId != $uid && !in_array($leaveReportUserId, $subs)) {
    die('<div class="alert">Access denied</div>');
}
?>
<table class="rd-table-fx">

    <tr>
        <td style="text-align:left;font-family:'Roboto Bold';color:var(--rd-light-gray);">
            Monthly Leave Record
        </td>
        <td style="width: 300px; text-align:right;">Filter</td>
        <td style="width: 200px;">
            <select name="fxUId" id="fxUId" onchange="leaveReportUserId(this.value)">

                <?php
                if ($leaveReportUserId != $uid) {
                    echo '<option value="' . $leaveReportUserId . '">' . $usersById[$leaveReportUserId]['displayname'] . '</option>';
                }
                echo '<option value="' . $uid . '">Me/Select Employee</option>';

                foreach ($subs as $x):
                    $u = $usersById[$x];
                    if ($u['active'] > 0):
                ?>
                        <option value="<?= $u['user_id'] ?>"><?= $u['displayname'] ?></option>
                <?php
                    endif;
                endforeach;
                ?>
            </select>
        </td>
    </tr>

</table>

<script>
    function leaveReportUserId(uid) {
        const uri = '<?= BASE_URL ?>aec/hrms/ui/leaves/log/' + uid;
        window.location = uri;
    }
</script>

<style>
    table.rd-table-card {
        border-collapse: collapse;
        /* background-color: rgb(24 118 120); */
        width: 100%;
        margin: auto;
    }

    table.rd-table-card thead tr {
        background-color: var(--rd-home-box2);
    }

    table.rd-table-card tr td {
        border: 1px solid gray;
        text-align: center;
        /* color: white; */
        height: 30px;
    }
</style>
<?php

$thisUId = empty($leaveReportUserId) ? $uid : $leaveReportUserId;
$leave_type_id = 2;

require_once W3APP . '/View/Widgets/wxLeaveCardMo.php';
?>