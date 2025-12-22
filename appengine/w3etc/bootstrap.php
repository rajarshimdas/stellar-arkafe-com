<?php /*
+---------------------------------------------------------------+
| Rajarshi Das						                            |
+---------------------------------------------------------------+
| Created On: 20-Jan-2012				                        |
| Updated On: 10-Feb-2024                                       |
+---------------------------------------------------------------+
| */ require_once $w3etc.'/foo/session/dbConnect.php';      /*  |
| */ require_once $w3etc.'/foo/session/startSession.php';   /*  |
| */ require_once $w3etc.'/foo/session/sx.php';             /*  |
| */ require_once $w3etc.'/foo/session/moduleAuth.php';     /*  |
+---------------------------------------------------------------+
*/

// Form Validation
require_once $w3etc.'/foo/ci3_form_validation.php';

// session_save_path($cookiePath);
$mysqli = cn1();  // Do not close connection
/*
printf("MySQL[1]: %s\n", $mysqli->host_info);
die;
*/

/*
+-------------------------------------------------------+
| User Authentication                                   |
+-------------------------------------------------------+
*/
$sX = sessionHandler($base_url, $LoginTimeOut, $cookieName, $w3path, $mysqli);
/*
var_dump($sX);
die();
*/
if ($sX["validUser"] < 1 || $sX["loginExpFlag"] == "T") {

    // Re-Login    
    header("Location:".$base_url."index.cgi?e=Re-Login");
    die;
    
} else {
    
    // Data from Authentication Module

    // User Info
    $sessionid		    = $sX["sessionid"];
    $userid             = $sX["user_id"];
    $loginname		    = $sX["loginname"];
    $daemon             = $sX["daemon"];
    $fullname		    = $sX["fullname"];
    $dept_id            = $sX["dept_id"];
    $dept_name          = $sX["dept_name"];
    $branch_id          = $sX["branch_id"];
    $branch_name        = $sX["branch_name"];
    $emailid            = $sX["emailid"];
    $hrgroup            = $sX["hrgroup"];
    $hrgroup_id         = $sX["hrgroup_id"];
    $avatar             = $sX["avatar"];

    // Project Info (if using project binder)
    $projectid 		    = $sX["projectid"];
    $jobcode		    = $sX["jobcode"];
    $projectname 	    = $sX["projectname"];
    $roleid		        = $sX["roleid"];
    
    // Project Ownership Domain
    $domainid		    = $sX["domainid"];
    $domainname		    = $sX["domainname"];
    $corporatename	    = $sX["corporatename"];    
    $corporatename      = $sX["corporatename"];
    $corporateaddress   = $sX["corporateaddress"];
    
    
}

// Caveats
$project_id     = $projectid;
$user_id        = $userid;
$role_id        = $roleid;

// Caveats - Short forms
$pid            = $project_id;
$uid            = $user_id;
$sid            = $sessionid;
$rid            = $role_id;

// Avatar
if ($avatar == '-'){
    $avatar = $base_url.'da/fa5/user-circle-avatar.png';
} else {
    $avatar = $base_url.'login/box/avatar/'.$avatar;
}