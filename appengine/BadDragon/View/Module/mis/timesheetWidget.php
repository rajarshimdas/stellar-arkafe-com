<?php
# Configuration | 10-Jun-25
#
$removeUsers = ['ashok.patel', 'abhikalpan'];




require_once W3PATH . 'appengine/w3root/studio/foo/status45d.php';
include W3PATH . 'appengine/w3root/studio/foo/dialog/alert.cgi';

$users = bdGetUsersArray($mysqli);


$dt_from_cache = $dt_from;
$no = 1;

foreach ($users as $u):

    if ($u['active'] > 0 && !in_array(strtolower($u['loginname']), $removeUsers)):

        # Reset 
        $dt_from = $dt_from_cache;

        echo '<div style="padding:10px;">' . $no++ . '. ' . $u['displayname'];
        $uid = $u['user_id'];

        # Get User data
        $s = getUserData($uid, $dt_from, $mysqli);
        // var_dump($s);
        $uX = $s[0];
        $dt_from = $s[1];

        # Get Timesheet data
        $tsX = getUserDayRangeTimesheetSum($uid, $dt_from, $dt_today, $mysqli);
        // $co = isset($tsX) ? count($tsX) : 0;

        // Empty array if no result
        if (!isset($tsX)) $tsX = [];
        // var_dump($tsX);
        // var_dump($dt_upto);

        // Boxes
        $dayX = statusbox($tsX, $lockdt, $dt_today, $d, $m, $Y, $dayname, $dayTotalMin, $holiday, $dt_from);
        // Circle
        $x45d = status45d($uid, $tsX, $dt_from, $dt_upto, $dayTotalMin, $ts_lockdt, $holiday);

        // Generate Widget
        tsWidget($uid, $dayX, $x45d);

        echo '</div>';
    endif;
endforeach;

?>
<div style="padding:10px;">
    <?= 'Total Teammates: ' . ($no - 1) ?>
</div>