<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 07-Jan-09					                |
| Updated On: 25-May-10					                |
+-------------------------------------------------------+
*/

$rootFolderName = 'admin';
$superAdminEmail = 'concert@arkafe.com';



require_once 'foo/session/sx.php';

if ($sX["validUser"] < 1) {
    // Re-Login
    header("Location:".$hostname);
} else {
    // Data from Authentication Module
    // User Info
    $sessionid		= $sX["sessionid"];
    $userid         = $sX["user_id"];
    $user_domain_id = $sX["user_domain_id"];
    $loginname		= $sX["loginname"];
    $fullname		= $sX["fullname"];
}


/*
+-------------------------------------------------------+
| Module Authentication                                 |
+-------------------------------------------------------+
*/
$moduleId = 1;
require_once 'foo/session/moduleAuth.php';

if (userModuleAuth($moduleId, $user_id, $loginname, $sessionid, $mysqli) !== TRUE) {
    die('<div align="center" style="font-size:120%;color:Red;">Module Access Denied.</div>');
}


?>
