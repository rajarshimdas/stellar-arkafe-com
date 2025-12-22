<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 22-Mar-2012				|
| Updated On:                                           |
+-------------------------------------------------------+
| Timesheet Form User data cache management functions   |
+-------------------------------------------------------+
*/



/*
+-------------------------------------------------------+
| Function: saveTMFormData2Cache                        |
+-------------------------------------------------------+
*/
function saveTMFormData2Cache (

        $userId,
        $sessionId,
        $projectId,        
        $calanderDate,
        $stageId,
        $memo,
        $mysqli
) {

    /*
    +-------------------------------------------------------+
    | Check there is a data row for this user               |
    +-------------------------------------------------------+
    */
    $userCacheFlag = 0;
    $query = "select 1 from timesheetcache where user_id = $userId";
    //echo 'Q1: '.$query.'<br>';

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $userCacheFlag = $row[0];
        $result->close();

    } else {
        printf("Error: %s\n", $mysqli->error);
    }


    /*
    +-------------------------------------------------------+
    | Data (Insert|Update)                                  |
    +-------------------------------------------------------+
    */
    if ($userCacheFlag < 1) {

        $query = "insert into
                    `timesheetcache`
                        (`user_id`, `sessionid`, `project_id`, `calanderdate`, `stage_id`, `memo`)
                    values
                        ($userId, '$sessionId', $projectId, '$calanderDate', $stageId, '$memo')";

    } else {

        $query = "update
                    `timesheetcache`
                set
                    `sessionid`     = '$sessionId',
                    `project_id`    = $projectId,                    
                    `calanderdate`  = '$calanderDate',
                    `stage_id`      = '$stageId',
                    `memo`          = '$memo'
                where
                    `user_id`       = $userId";

    }
    //echo 'Q2: '.$query.'<br>';

    if (!$mysqli->query($query)) {

        printf("Error[]: %s\n", $mysqli->error);
        return false;
    }

    return true;

}

/*
+-------------------------------------------------------+
| Function: getTMFormCacheData                          |
+-------------------------------------------------------+
| Returns cache data in an array                        |
+-------------------------------------------------------+
*/

function getTMFormCacheData ($userId, $mysqli) {

    $query = "select
                `sessionid`,
                `project_id`,                
                `calanderdate`,
                `stage_id`,                 
                `memo`
            from
                timesheetcache
            where
                user_id = $userId";

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();

        $cacheDataX = array (

                "sessionid"     => $row[0],
                "project_id"    => $row[1],                
                "calanderdate"  => $row[2],
                "stage_id"      => $row[3],
                "memo"          => $row[4]

        );

        $result->close();

    } else {

        printf("Error[ getTMFormCacheData]: %s\n", $mysqli->error);
        return false;

    }

    return $cacheDataX;

}

?>
