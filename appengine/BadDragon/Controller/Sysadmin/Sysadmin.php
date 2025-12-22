<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jul-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/


/*
+-------------------------------------------------------+
| Module Authentication                                 |
+-------------------------------------------------------+
*/
$moduleId = 1;
require_once W3PATH . 'appengine/w3etc/foo/session/moduleAuth.php';

if (userModuleAuth($moduleId, $user_id, $loginname, $sessionid, $mysqli) !== TRUE) {
    // die('<div align="center" style="font-size:120%;color:Red;">Module Access Denied.</div>');

    rdReturnJsonHttpResponse(
        '200',
        ["F", "Module access denied"]
    );

    die;
    
}
