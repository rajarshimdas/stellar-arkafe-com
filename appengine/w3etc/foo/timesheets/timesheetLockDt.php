<?php

/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 07-May-2013				                |
| Updated On: 11-Aug-2024                               |
+-------------------------------------------------------+
*/
require_once $w3root . '/studio/tms/hot7e/getHolidayList.php';


class timesheetLockDt
{

    private $uid;
    private $mysqli;
    // private $lockDt;
    private $holidayListFile;
    // Timesheets can be filled how many Working Days 
    private $noOfWorkingDays4TimesheetEntry = 4; // 3 previous days + today = 4 days

    /*
      +-----------------------------------------------------+
      | instantiation                                       |
      +-----------------------------------------------------+
     */

    function __construct($userid, $holidayListFile, $mysqliSuperUser)
    {

        $this->uid = $userid;
        $this->mysqli = $mysqliSuperUser;
        $this->holidayListFile = $holidayListFile;

        return TRUE;
    }

    /*
      +-----------------------------------------------------+
      | instantiation                                       |
      +-----------------------------------------------------+
     */

    function getTimesheetLockDt()
    {

        // Check the override for this user
        if ($lockdt = timesheetLockDt::getOverrideLockDt()) {
            return $lockdt;
        };

        // Get the lockdt from the timesheetlockdt table
        /*
         * timesheetlockdt
          +---------------+------+------+-----+---------+-------+
          | Field         | Type | Null | Key | Default | Extra |
          +---------------+------+------+-----+---------+-------+
          | lockdt        | date | NO   |     | NULL    |       |
          | lastupdatedon | date | NO   |     | NULL    |       |
          +---------------+------+------+-----+---------+-------+
         */

        $mysqli = $this->mysqli;

        $query = "select lockdt, lastupdatedon from timesheetlockdt";

        if ($result = $mysqli->query($query)) {

            $row = $result->fetch_row();
            $lockdt = $row[0];
            $lastupdatedon = $row[1];

            $result->close();
        } else {
            printf("Error[timesheetLockDt::getTimesheetLockDt()]: %s\n", $mysqli->error);
        }


        // Make sure that this info is current
        $now = date("Y-m-d");
        // echo 'Now: ' . $now . ' | lockdt: ' . $lockdt;

        // Refresh cache once daily when called first time
        if ($lastupdatedon != $now) {

            // Update the timesheetlockdt table
            $lockdt = timesheetLockDt::updateTimesheetLockDt();
        }

        // $this->lockDt = $lockdt;

        # Hack to remove timesheet lockdt.
        # 28-Nov-2023 as requested by Tanvisha at Abhikalpan
        return $lockdt;
        # return '2003-01-23';

    }

    /*
      +-----------------------------------------------------+
      | instantiation                                       |
      +-----------------------------------------------------+
     */

    private function updateTimesheetLockDt()
    {

        $debugFlag = 0;
        $mysqli = $this->mysqli;

        if ($debugFlag > 0) {
            echo '<br>updateTimesheetLockDt<br>';
        }

        // Get the Holiday List Array
        $hX = getHolidayList(0, $this->mysqli);
        //var_dump($hX);

        // Get the previous days
        $now = date("Y-m-d");
        $noOfWorkingDays = 0;

        for ($i = 1; $noOfWorkingDays < $this->noOfWorkingDays4TimesheetEntry; $i++) {

            $holidayFlag = 0;

            $dayBeforeActiveDay = mktime(0, 0, 0, date('m'), date('d') - $i, date('Y'));
            //$thisDate = date("d-M-Y", $dayBeforeActiveDay);
            $thisDateMySQL = date("Y-m-d", $dayBeforeActiveDay);

            if ($debugFlag > 0) {
                echo $i . '. Date: ' . $thisDateMySQL;
            }

            // Check if its a sunday
            if (date("D", $dayBeforeActiveDay) == 'Sun') {
                if ($debugFlag > 0) {
                    echo ' :: Sun';
                }
                $holidayFlag = 1;
            }

            // Is this a Holiday
            if ($debugFlag > 0) echo "h: " . $hX[$thisDateMySQL][0];
            if (isset($hX[$thisDateMySQL][0])) {
                $holidayFlag = 1;
            }

            // Count working days found
            if ($holidayFlag < 1) {
                $noOfWorkingDays++;
            }
            if ($debugFlag > 0) {
                echo ' (' . $noOfWorkingDays . ')<br>';
            }
        }

        // Update the timesheetlockdt
        $query = "update timesheetlockdt set lockdt = '$thisDateMySQL', lastupdatedon = '$now'";
        if ($debugFlag > 0) echo 'Q: ' . $query . '<br>';

        if (!$mysqli->query($query)) {
            printf("Error[timesheetLockDt::updateTimesheetLockDt()]: %s\n", $mysqli->error);
        }

        return $thisDateMySQL;
    }

    /*
      +-----------------------------------------------------+
      | instantiation                                       |
      +-----------------------------------------------------+
     */

    private function getOverrideLockDt()
    {

        $mysqli = $this->mysqli;
        $foundFlag = 0;

        // Timestamp 24 hours ago
        $unixTimestamp24hoursAgo = time() - 86400;
        $dtime = date("Y-m-d H:i:s", $unixTimestamp24hoursAgo);
        //echo 'dtime: '.$dtime;

        $query = "select 
                    1, 
                    `templockdt` 
                from 
                    `timesheetlockdtoverride` 
                where 
                    `user_id` = " . $this->uid . " and 
                    `dtime` > '$dtime' and
                    `active` > 0
                order by
                    `dtime` DESC";
        //echo '<br>Q: ' . $query . '<br>';
        //$mysqli = cn2();
        if ($result = $mysqli->query($query)) {

            if ($row = $result->fetch_row()) {
                $foundFlag = $foundFlag + $row[0];
                $templockdt = $row[1];
            }

            $result->close();
        } else {
            printf("Error: %s\n", $mysqli->error);
        }

        if ($foundFlag > 0) {
            return $templockdt;
        } else {
            return FALSE;
        }
    }

    /*
      +-----------------------------------------------------+
      | instantiation                                       |
      +-----------------------------------------------------+
     */

    function setOverride2LockDt($templockdt, $admin_uid, $reason)
    {

        $dtime = date("Y-m-d H:i:s");
        $mysqli = $this->mysqli;

        // Disable old locks
        $query = "update `timesheetlockdtoverride` set active = 0 where `user_id` = " . $this->uid;
        /* Save in Database */
        if (!$mysqli->query($query)) {
            //printf("Error: %s\n", $mysqli->error);
            return FALSE;
        }


        /*
        timesheetlockdtoverride;
        +------------+------------------+------+-----+---------+-------+
        | Field      | Type             | Null | Key | Default | Extra |
        +------------+------------------+------+-----+---------+-------+
        | user_id    | int(10) unsigned | NO   |     | NULL    |       |
        | templockdt | date             | NO   |     | NULL    |       |
        | admin_uid  | int(10) unsigned | NO   |     | NULL    |       |
        | reason     | text             | NO   |     | NULL    |       |
        | dtime      | datetime         | NO   |     | NULL    |       |
        +------------+------------------+------+-----+---------+-------+
        */

        $query = "insert into 
                    `timesheetlockdtoverride`
                        (`user_id`, `templockdt`, `admin_uid`, `reason`, `dtime`) 
                    values
                        ($this->uid, '$templockdt', $admin_uid, '$reason', '$dtime')";

        // echo '<br>Q: '.$query;

        /* Save in Database */
        if (!$mysqli->query($query)) {
            //printf("Error: %s\n", $mysqli->error);
            return FALSE;
        }

        return TRUE;
    }

    /*
      +-----------------------------------------------------+
      | instantiation                                       |
      +-----------------------------------------------------+
     */

    function removeOverride2LockDt()
    {

        $mysqli = $this->mysqli;
        $query = "update `timesheetlockdtoverride` set `active` = 0 where `user_id` = " . $this->uid;
        /* Save in Database */
        if (!$mysqli->query($query)) {
            //printf("Error: %s\n", $mysqli->error);
            return FALSE;
        }
        return TRUE;
    }

    function getActiveOverrideRecords()
    {

        $mysqli = $this->mysqli;

        // Time 24 hours ago
        $timestamp24hourAgo = time() - 86400;
        $time24hourAgo = date('Y-m-d H:i:s', $timestamp24hourAgo);

        // Get the data rows
        $query = "select
                        t2.fullname as teammate,
                        t1.templockdt,
                        t1.reason,
                        t1.dtime,
                        t3.fullname as admin,
                        t1.user_id as teammate_uid
                    from
                        timesheetlockdtoverride as t1,
                        users as t2,
                        users as t3
                    where
                        t1.user_id = t2.id and
                        t1.admin_uid = t3.id and
                        t1.active = 1 and
                        t1.dtime > '$time24hourAgo'
                    order by
                        t2.fullname";

        // echo 'Q: '.$qurey;

        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                $rowX[] = array(
                    "tm" => $row[0],
                    "lockdt" => $row[1],
                    "reason" => $row[2],
                    "dtime" => $row[3],
                    "admin" => $row[4],
                    "tm_uid" => $row[5]
                );
            }

            $result->close();
        } else {
            printf("Error: %s\n", $mysqli->error);
        }

        //echo 'rowXcount: '.count($rowX).'<br>';
        return $rowX;
    }
}
