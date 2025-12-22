<?php


require_once 'foo/timesheets/timesheetLockDt.php';
require_once 'foo/dateCal2UnixTS.php';
require_once 'foo/dateMysql2UnixTS.php';
require_once 'foo/dateMysql2Cal.php';


function validateTimesheetFormData(
    $uid,
    $holidayListFile,
    $date,              // Timesheet entry date
    $this_project_id,
    $hours,
    $minutes,
    $work,
    $percent
) {
    /* Removed - Keep for filling the data
    +-------------------------------------------------------+
    | On Leave		                              	        |
    +-------------------------------------------------------+
    if ($worked_from == 30) {

        $this_project_id = 2;       // Overheads
        $task_id = 1;               // Tasks -> NA
        $projectstage_id = 1;       // Project Stage -> NA
        $minutes = 0;

        //if ($hours < 5) $work = "Leave: Half Day."; else $work = "Leave: Full Day.";
        if ($leaveFH < 1) {
            $work = "Leave: Half Day.";
            $hours = 4;
        } else {
            $work = "Leave: Full Day.";
            $hours = 8;
        }
    }
    */

    /*
    +-------------------------------------------------------+
    | Timesheet Lock: 3 Previous Working Days               |
    +-------------------------------------------------------+
    */
    // Instantiate
    $ts = new timesheetLockDt($uid, $holidayListFile, cn2());

    // GetTimesheetLockDt
    $lockdt = $ts->getTimesheetLockDt();
    // die('lockdt: '.$lockdt);

    // Formatting dates into UNIX timestamp
    $tsDtUser = dateCal2UnixTS($date) + 1; // add 1 to convert to int
    $tsDtLock = dateMysql2UnixTS($lockdt) + 1; // add same here as well

    // The User Timestamp must bigger than the Lock Timestamp
    $x = $tsDtUser - $tsDtLock;

    if ($x < 0) {
        $lockdtCal = dateMysql2Cal($lockdt);
        $mc = 'You do not have permission to edit timesheet before ' . $lockdtCal . '.';
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    /*
    +-----------------------------------------------------+
    | Data Validation                                     |
    +-----------------------------------------------------+
    */

    // Project validation
    if ($this_project_id < 1) {
        $mc = "Please select Project...";
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    // Project (Overheads)
    if ($this_project_id > 1 && $this_project_id < 100) {
        $projectstage_id = 1;
        $task_id = 1;
    }

    // Date validation
    if ($date === "-- Calendar --" || $date === "") {
        $mc = "Date was not selected. Please select date from the drop down calendar...";
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    // Check that the date is not in future
    $today = date('Y-m-d');

    /* Use strtotime to calculate the difference */
    $dx = 0;
    if (($dx = (int) ((strtotime($date) - strtotime($today)) / 86400)) === false)
        die("Internal Error in compairing dates...");

    /* echo "<br>Difference between 2 dates = $dx"; */
    if ($dx > 0) {
        $mc = "Selected Date [$date] is $dx days in future. Re-Enter with correct date..";
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    // Time
    if ($hours == "0" && $minutes == "0") {
        $mc = "Time should be greater than Zero. Select Hours and/or minutes and try agian...";
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    // Validate Project stage
    if ($projectstage_id == "0") {
        $mc = "Stage was not selected...";
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    // Validate the task
    if ($task_id == "0") {
        $mc = "Task was not selected...";
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    // Validate Work
    if (strlen($work) < 1) {
        $mc = "Work description cannot be empty...";
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    // Validate Percent
    if (is_int($percent + 0)) {
        if ($percent < 0 || $percent > 100) {
            $mc = "Percentage must be between 0 to 100";
            header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
            die;
        }
    } else {
        $mc = "Percent must be an Integer";
        header("Location: rajarshi.cgi?a=timesheet&mc=$mc");
        die;
    }

    return true;
}
