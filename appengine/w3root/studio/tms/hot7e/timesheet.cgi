<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 21-May-2009       			            |
| Updated On: 13-Apr-2011				                |
+-------------------------------------------------------+
| Timesheet :: Form and Table                         	|
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| 13-Apr-11 Saturday On/Off                             |
|           satmode 0 - Holiday                         |
|           satmode 1 - Working 1,3,5th Sats            |
|           satmode n - Nth combination                 |
+-------------------------------------------------------+
$path = $pathStudio.'/da/w3root/'.$rootFolderName;
echo 'Path: '.$path;
*/

if (isset($_GET["no"])) {
    $display_no_of_days = $_GET["no"];
} else {
    $display_no_of_days = 45;
}

// Form Variables
$FormBackgroundColor    = "#E8E9FF";
$FormButtonName         = "Add";
$Form_hidden_a          = "timesheet-add";
$Form_show_Cancel       = "No";
$form_projectname       = $projectname;
$form_projectid         = $projectid;

/*
+-------------------------------------------------------+
| Get Cache Data                                        |
+-------------------------------------------------------+
*/
require_once 'foo/timesheets/timesheetCache.php';
require_once 'foo/pid2pname.php';
require_once 'foo/sid2stagename.php';

$cacheDataX = getTMFormCacheData ($userid, $mysqli);
// var_dump($cacheDataX);

if ($sessionid == $cacheDataX["sessionid"]) {

    $form_projectid     = $cacheDataX["project_id"];
    $form_projectname   = pid2pname($cacheDataX["project_id"], $mysqli);
    $form_date          = $cacheDataX["calanderdate"];
    /*
    $form_projectstage_id   = $cacheDataX["stage_id"];
    $form_projectstage_name = sid2stagename($cacheDataX["stage_id"], $mysqli);
    */

}

/*
+-------------------------------------------------------+
| Dispaly the form                                      |
+-------------------------------------------------------+
*/
$FormBackgroundColor = "#d4d5e9";
include 'hot7e/timesheet-form.cgi';

/*
+-------------------------------------------------------+
| Table Header                                          |
+-------------------------------------------------------+
*/
if (!$display_no_of_days) $display_no_of_days = $_GET["no"];

if (!$display_no_of_days) {

    $display_no_of_days = 21;
    $noOfDaysToDate     = 0;    // Compatibility to mgnt/moo/tmReport.cgi
    $noOfDaysFromDate   = 21;   // Compatibility to mgnt/moo/tmReport.cgi
    $no_of_days_flag    = 0;

} else {

    $noOfDaysToDate     = 0;
    $noOfDaysFromDate   = $display_no_of_days;
    $no_of_days_flag    = 1;

}

// echo "<br>Timesheet (all projects) for the last $display_no_of_days days";
/*
+-------------------------------------------------------+
| Tabulate Timesheet                                    |
+-------------------------------------------------------+
*/
$HideIOdata = "N";
$this_userid = $userid;

include 'hot7e/tabulate_a.cgi';     // Abhikalpan Format


/*
+-------------------------------------------------------+
| Show edit & delete buttons                            |
+-------------------------------------------------------+
*/
function showEditButtons(
    $dateX,
    $i,
    $this_userid,
    $userid,
    $showAddEditDeleteButtons,
    $display_no_of_days,
    $no_of_days_flag,
    $base_url
) {

    if ($this_userid == $userid && $dateX[$i]["approved"] < 1 && $showAddEditDeleteButtons > 0 && $dateX[$i]["project_id"] > 10) {
?>
        <a href="timesheets.cgi?a=timesheet-edit&tsid=<?php
                                                        echo $dateX[$i]["timesheet_id"];
                                                        if ($display_no_of_days && $no_of_days_flag > 0)
                                                            echo "&no=$display_no_of_days";
                                                        ?>"><img class="tmButton" src='<?= $base_url ?>da/fa5/edit.png' title="Edit Timesheet"></a>
        <a href="timesheets.cgi?a=timesheet-del&tsid=<?php
                                                        echo $dateX[$i]["timesheet_id"];
                                                        if ($display_no_of_days && $no_of_days_flag > 0)
                                                            echo "&no=$display_no_of_days";
                                                        ?>"><img class="tmButton" src='<?= $base_url ?>da/fa5/delete.png' title="Delete Timesheet"></a>
<?php
    }
}
