<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 15-Dec-2012				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| Function:						                        |
+-------------------------------------------------------+
*/
function userModuleAuth ($moduleId, $uid, $loginname, $sessionid, $mysqli) {

    $allowFlag  = 0;    // Assume no access
    /*
    +-------------------------------------------------------+
    | Validate                                              |
    +-------------------------------------------------------+
    | 1. Check Session Id                                   |
    | 2. check moduletick                                   |
    | 3. Unconditional access to members of daemon table    |
    +-------------------------------------------------------+
    */

    // 1. Check Session Id
    $sessionid = 'Not used!';

    // 2. Check Moduletick
    $query = "select 1 from `moduletick` where `user_id` = '$uid' and `modules_id` = '$moduleId'";

    if ($result = $mysqli->query($query)) {
        $allowFlag = $allowFlag + $result->num_rows;        
        $result->close();
    } else {
        die('Error[userModuleAuth.1]: '.$mysqli->error);
    }

    // 3. Daemons
    $query = "select 1 from `daemons` where `name` = '$loginname'";

    if ($result = $mysqli->query($query)) {
        $allowFlag = $allowFlag + $result->num_rows;        
        $result->close();
    } else {
        die('Error[userModuleAuth.2]: '.$mysqli->error);
    }

    if ($allowFlag < 1) {
        return FALSE;
    } else {
        return TRUE;
    }

}

/*
+-------------------------------------------------------+
| Function:						                        |
+-------------------------------------------------------+
*/
function showSysAdminButton($uid, $loginname, $sessionid, $mysqli) {

    $moduleId = 1;

    if (userModuleAuth($moduleId, $uid, $loginname, $sessionid, $mysqli) === TRUE) {
        echo '<a class="button" href="go2/btn2sysadm.cgi?sx='.$sessionid.'&uid='.$uid.'&ln='.$loginname.'">System Admin</a>';
    }
}


function showMVCButton($uid, $loginname, $sessionid, $mysqli) {

    $moduleId = 1;

    if (userModuleAuth($moduleId, $uid, $loginname, $sessionid, $mysqli) === TRUE) {
        echo '<a class="button" href="go2/btn2mvc.cgi?sx='.$sessionid.'&uid='.$uid.'&ln='.$loginname.'">MVC</a>';
    }
}


/*
+-------------------------------------------------------+
| Function:						                        |
+-------------------------------------------------------+
*/
function showFinanceButton($uid, $loginname, $sessionid, $mysqli) {

    $moduleId = 2;
    
    if (userModuleAuth($moduleId, $uid, $loginname, $sessionid, $mysqli) === TRUE) {
        echo '<a class="button" href="go2/btn2fn.cgi?sx='.$sessionid.'&uid='.$uid.'&ln='.$loginname.'">Finance</a>&nbsp;';
    }

}

/*
+-------------------------------------------------------+
| Function:						                        |
+-------------------------------------------------------+
*/
function showMISButton($uid, $loginname, $sessionid, $mysqli) {

    $moduleId = 3;

    if (userModuleAuth($moduleId, $uid, $loginname, $sessionid, $mysqli) === TRUE) {
        echo '<a class="button" href="go2/btn2mis.cgi?sx='.$sessionid.'&uid='.$uid.'&ln='.$loginname.'">MIS</a>';
    }

}
