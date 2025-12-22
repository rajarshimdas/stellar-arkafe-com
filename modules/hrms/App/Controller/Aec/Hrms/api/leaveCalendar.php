<?php
$sdt = $_POST['sdt'];
$edt = $_POST['edt'];
$displayTeam = $_POST['displayTeam'];
$fxUId = empty($_POST['fxUId']) ? 0 : $_POST['fxUId'];

$query = "SELECT 
            * 
        FROM 
            holidays 
        where 
            active > 0 and 
            dt >= '$sdt' and
            dt <= '$edt'";


$events = [];
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()) {

    $events[] = [
        'title' => $row['holiday'],
        'start' => $row['dt'],
        // 'backgroundColor' => 'lightslategray',
        'display' => 'background',
    ];
}

# Event Color
#
$color = [
    '5' => 'Blue',      // Approval Pending
    '6' => 'Red',       // Leave Without Pay
    '10' => 'Green',    // Leave Approved
    '11' => 'Gray',     // Leave Rejected
    '33' => 'Blue',     // Revoke Rejected
];

$cn0 = $mysqli;
$usersById = bdGetUsersById($cn0);
// var_dump($usersById);

if ($displayTeam == 'all') {
    $subs = [];
    $users = bdGetUsers($cn0);
    foreach ($users as $u) {
        $subs[] = $u['user_id'];
    }
} elseif ($displayTeam == 'emp') {
    $subs[] = $fxUId;
} else {
    $subs = bdGetSubordinatesPeerAndMe($uid, $cn0);
    /*
    $subs = [];
    require_once $APP . '/appengine/w3etc/foo/getTeamList.php';
    $team = rdGetMyTeamList($uid, $mysqli);

    foreach ($team as $u) {
        $subs[] = $u['uid'];
    }
    */
}

## Get Leaves
##
$leaveReq = bdGetLeaveRequestForUsers($subs, $mysqli);

foreach ($leaveReq as $e) {

    if (
        $e['revoke'] < 3
        /* exclude short leaves 
        && !($e['from_dt_units'] == 'LC' || $e['from_dt_units'] == 'EG')
        */
    ) {

        $cx = empty($color[$e['status_id']]) ? 'Orange' : $color[$e['status_id']];

        $flag = ""; // Empty string
        if ($e['from_dt'] == $e['end_dt']) {
            if ($e['from_dt_units'] != 'F') $flag = " (" . $e['from_dt_units'] . ')';
        } else {
            if ($e['from_dt_units'] != 'F' || $e['end_dt_units'] != 'F') {
                $flag = ' (' . $e['from_dt_units'] . ' | ' . $e['end_dt_units'] . ' )';
            }
        }

        $sdt = $e['from_dt'];
        $edt = getNextISODate($e['end_dt']);

        $events[] = [
            'title'         => $usersById[$e['user_id']]['displayname'] . $flag,
            'start'         => $sdt,
            'end'           => $edt,
            'color'         => $cx,
            'description'   => $e['reason'] . ' | ' . (float)$e['nod_units'],
        ];
    }
}


bdReturnJSON($events);
