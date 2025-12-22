<?php /*
+-----------------------------------------------------+
| Rajarshi Das					                    |
+-----------------------------------------------------+
| Created On: 07-Jan-09				                |
| Updated On: 25-May-10				                |
+-----------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
require_once $w3etc . '/env.php';

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

/*
+-------------------------------------------------------+
| ci3 hacked Form Validation class.                     |
+-------------------------------------------------------+
*/
require_once $w3etc . '/foo/ci3_form_validation.php';


// Parse Action File
$a = (isset($_GET["a"]))? $_GET["a"]: $_POST["a"];

// Action program
if ($a) {

    $actionFile = "boo/admin/$a.php";
    //echo 'actionFile: '.$actionFile;
    require $actionFile;

} else {

    // Error in loading actionFile
    echo 'AJAX error.';
    
}
