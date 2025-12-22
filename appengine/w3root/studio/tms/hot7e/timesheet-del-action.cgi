<?php

/*
  +-------------------------------------------------------+
  | Rajarshi Das						|
  +-------------------------------------------------------+
  | Created On: 18-Aug-2009       			|
  | Updated On: 16-Aug-2010				|
  +-------------------------------------------------------+
  | Timesheet :: Delete                                	|
  +-------------------------------------------------------+
 */

// Get variables
$tsid = $_POST["tsid"];
$go = $_POST["go"];
$display_no_of_days = $_POST["no"];


/*
  +-----------------------------------------------------+
  | Delete                                              |
  +-----------------------------------------------------+
 */
// If the user confirmed delete
if ($go === "Delete") {

    // The timesheet entry data
    include 'hot7e/tsid2data.cgi';
    $date = $timesheetX["date"];

    // Validate
    require_once 'foo/timesheets/timesheetLockDt.php';
    require_once 'foo/dateCal2UnixTS.php';
    require_once 'foo/dateMysql2UnixTS.php';
    require_once 'foo/dateMysql2Cal.php';

    // Instantiate
    $ts = new timesheetLockDt($uid, $holidayListFile, cn2());

    // GetTimesheetLockDt
    $lockdt = $ts->getTimesheetLockDt();

    // Formatting dates into UNIX timestamp
    $tsDtUser = dateCal2UnixTS($date) + 1; // add 1 to convert to int
    $tsDtLock = dateMysql2UnixTS($lockdt) + 1; // add same here as well
    // The User Timestamp must bigger than the Lock Timestamp
    $x = $tsDtUser - $tsDtLock;
    if ($x < 0) {

        $lockdtCal = dateMysql2Cal($lockdt);
        echo 'You do not have permission to edit timesheet before ' . $lockdtCal . '.';
        die;
    }


    /*
      +-----------------------------------------------------+
      | Delete                                    	|
      +-----------------------------------------------------+
     */


    $mysqli = cn2();

    $query = "update
                timesheet
            set
                active = 0
            where
                id = $tsid and
                user_id = $user_id and
                approved = 0";

    if (!$mysqli->query($query)) {
        printf("Error: %s\n", $mysqli->error);
    }

    $mysqli->close();
}

// Re-direct the user
if ($display_no_of_days)
    $params = "a=timesheet&no=$display_no_of_days";
else
    $params = "a=timesheet";
header("Location:timesheets.cgi?$params");
