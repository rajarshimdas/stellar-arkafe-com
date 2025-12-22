<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   01-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/
// die(sessionSavePath);

if (defined('sessionSavePath')) {
    session_save_path(sessionSavePath);
    session_name("CONCERT");
    session_start();
}

// die(var_dump($_SESSION));

# Check Session login status
if (empty($_SESSION['login']) || $_SESSION['login'] != "T") {
    header("Location:" . BASE_URL);
}

# Check session time-out
$now = time();

/* Are session variables available */
if (!empty($_SESSION["loginexp"])) {
    /* Has the session expired */
    $loginexp = $_SESSION["loginexp"];
    if (($loginexp + 5) < ($now + 5)) {
        header("Location:" . BASE_URL . "login/index.cgi?e=session-time-out");
        die;
    } else{
        $_SESSION['loginexp'] = $now + (8 * 3600);
    }
} else {
    // This is a new session. Redirect to login page.
    header("Location:" . BASE_URL . "login/");
    die;
}


# Session
$sid            = session_id();

# User
$uid            = $_SESSION['user_id'];
$username       = $_SESSION['loginname'];
$displayname    = $_SESSION['displayname'];

$user_type      = empty($_SESSION['user_type']) ? leaveModuleRole($username, $leaveModuleRole) : $_SESSION['user_type'];

function leaveModuleRole($username, $leaveModuleRole)
{
    $roleId = in_array($username, $leaveModuleRole['4']) ? 4 : 1;
    return $roleId;
}

/*
// activeCalYear
if (empty($_SESSION['activeCalYear'])) {
    $activeCalYear = date('Y');
    $_SESSION['activeCalYear'] = $activeCalYear;
} else {
    $activeCalYear = $_SESSION['activeCalYear'];
}
*/

// Testing | 12-Oct-2025
// $activeCalYear = '2024';

/*
+-------------------------------------------------------+
| Session Vars                                          |
+-------------------------------------------------------+
*/
$logMessage             = $_SESSION['displayname'];
$leaveCalendarUserId    = empty($_SESSION['leaveCalendarUserId']) ? 0 : $_SESSION['leaveCalendarUserId'];
$leaveMISMonth          = empty($_SESSION['leaveMISMonth']) ? date('m') : $_SESSION['leaveMISMonth'];
$leaveMISYear           = empty($_SESSION['leaveMISYear']) ? date('Y') : $_SESSION['leaveMISYear'];


# hrmsActiveUser
$hrmsActiveUser = empty($_SESSION['hrmsActiveUser']) ? 0 : $_SESSION['hrmsActiveUser'];
$hrmsDeletedUsersToggle = empty($_SESSION['hrmsDeletedUsersToggle']) ? 'Hide' : $_SESSION['hrmsDeletedUsersToggle'];
