<?php
/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 10-Feb-2024		                	    |
| Updated On:                                           |
+-------------------------------------------------------+
*/

## Status Widget | 14-Jun-2025
#
require_once W3PATH . 'appengine/w3root/studio/foo/status45d.php';

## $fastApproveRights defined in LocalSetting.php
#
$fastApproveflag = in_array(strtolower($loginname), $fastApproveRights) ? 1 : 0;
// echo strtolower($loginname) . (in_array(strtolower($loginname), $fastApproveRights) ? " | yes" : " | no");

/* view_users
+--------------------+-----------------------+------+-----+---------+-------+
| Field              | Type                  | Null | Key | Default | Extra |
+--------------------+-----------------------+------+-----+---------+-------+
| user_id            | mediumint(8) unsigned | NO   |     | 0       |       |
| domain_id          | smallint(5) unsigned  | NO   |     | NULL    |       |
| displayname        | varchar(50)           | NO   |     | NULL    |       |
| loginname          | varchar(50)           | NO   |     | NULL    |       |
| passwd             | varchar(50)           | NO   |     |         |       |
| fname              | varchar(45)           | NO   |     | -       |       |
| lname              | varchar(45)           | NO   |     | -       |       |
| emailid            | varchar(301)          | YES  |     | NULL    |       |
| gender             | varchar(5)            | NO   |     | NULL    |       |
| department_id      | int(10) unsigned      | NO   |     | 1       |       |
| departmentname     | varchar(100)          | NO   |     | NULL    |       |
| branch_id          | smallint(5) unsigned  | NO   |     | 1       |       |
| branchname         | varchar(250)          | NO   |     | NULL    |       |
| reports_to         | varchar(50)           | NO   |     | NULL    |       |
| active             | tinyint(1)            | YES  |     | NULL    |       |
| designation        | varchar(50)           | NO   |     | NULL    |       |
| userhrgroup_id     | tinyint(3) unsigned   | NO   |     | NULL    |       |
| hrgroup            | varchar(45)           | NO   |     | NULL    |       |
| reports_to_user_id | int(10) unsigned      | NO   |     | NULL    |       |
+--------------------+-----------------------+------+-----+---------+-------+
*/
$query = "select * from view_users where reports_to_user_id = $userid and active > 0";
// echo $query;

$result = $mysqli->query($query);

/* fetch associative array */
while ($row = $result->fetch_assoc()) {
    // printf("%s (%s)\n", $row["loginname"], $row["passwd"]);
    $teamX[] = $row;
}

// var_dump($teamX);
?>
<style>
    .tabulation {
        width: 100%;
        font-size: 0.9em;
    }

    .tabulation tr:hover {
        background-color: rgb(203, 228, 248);
    }

    .tabulation tr td {
        text-align: center;
    }

    .tabulation tr:first-child {
        background-color: #FFF6F4;
    }

    .tabulation tr td:nth-child(3) {
        text-align: left;
        /* padding-left: 15px; */
    }
</style>
<table class="tabulation">
    <tr>
        <td class="cellHeaderLeft" style="width: 120px;">No</td>
        <td class="cellHeader" style="width:300px;">Status</td>
        <td class="cellHeader" style="padding-left: 15px;">Team member</td>
        <td class="cellHeader" style="width: 120px;">New Entries</td>
        <td class="cellHeader" style="width: 120px;">&nbsp;</td>
        <?= ($fastApproveflag > 0) ? '<td class="cellHeader" style="width:150px;">&nbsp;</td>' : ""; ?>
    </tr>

    <?php
    /*
    +-------------------------------------------------------+
    | Data Rows                                             |
    +-------------------------------------------------------+
    */
    for ($i = 0; $i < count($teamX); $i++) {

        $thisUId = $teamX[$i]["user_id"];

        // Get the number of new timesheet entries for this employee
        $new_no = 0;
        $query = 'select 
                    1 
                from 
                    timesheet 
                where 
                    user_id = ' . $thisUId . ' and 
                    active > 0 and 
                    approved < 1 and 
                    pm_review_flag < 1 and 
                    project_id > 15';
        // echo "<br>Q: ".$query;

        if ($result = $mysqli->query($query)) {
            $new_no = $result->num_rows;
            $result->close();
        }

        $fa = ($fastApproveflag > 0) ? '<td class="cellRow">
                    <a class="button" href="manage-fastapprove.cgi?uid=' . $thisUId . '">Fast Approve</a>
                </td>' : '';

        echo '<tr>
                <td class="cellRowLeft">' . ($i + 1) . '</td>
                <td class="cellRow"><div style="text-align:center;background-color:#d7d7d7;">';

        tsStatusWidget($thisUId, $lockdt, $dt_today, $d, $m, $Y, $dayname, $dayTotalMin, $holiday, $dt_from, $dt_upto, $ts_lockdt, $mysqli);

        echo '</div></td>
                <td class="cellRow" style="padding-left: 15px;">' . $teamX[$i]["displayname"] . '</td>
                <td class="cellRow">' . $new_no . '</td>
                <td class="cellRow">
                    <a class="button" href="manage-rm.cgi?uid=' . $thisUId . '">Review</a>
                </td>
                ' . $fa . '
            </tr>';
    }

    ?>

</table>


<?php
// echo $w3root . '/studio/foo/dialog/alert.cgi';
include $w3root . '/studio/foo/dialog/alert.cgi';
$dt_from_cache = $dt_from;

function tsStatusWidget($uid, $lockdt, $dt_today, $d, $m, $Y, $dayname, $dayTotalMin, $holiday, $dt_from, $dt_upto, $ts_lockdt, $mysqli)
{
    # Get User data
    $s = getUserData($uid, $dt_from, $mysqli);
    // var_dump($s);
    $uX = $s[0];
    $dt_from = $s[1];
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
}
