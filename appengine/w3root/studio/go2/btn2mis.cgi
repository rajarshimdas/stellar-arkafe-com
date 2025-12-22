<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 14-Dec-12					                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';
require_once 'foo/session/moduleAuth.php';


// Static Variables
$moduleId   = 3;            // MIS module id
$cookieName = 'CONCERT';    // Session Cookie Name
// $mysqli     = cn1();

// Variables
$sx         = $_GET["sx"];
$uid        = $_GET["uid"];
$loginname  = $_GET["ln"];

/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/
if ((userModuleAuth($moduleId, $uid, $loginname, $sessionid, $mysqli) === FALSE) || $sx != $sessionid){
    die('<div align="center" style="font-size:120%;color:Red;">
        You are not allowed to access the Management Information Module.
        <br>Click back button to go back to Home page...
        </div>');
}

/*
+-------------------------------------------------------+
| Redirect						                        |
+-------------------------------------------------------+
*/
header("Location:".$base_url."mis/concert.cgi");
