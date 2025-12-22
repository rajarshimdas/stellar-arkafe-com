<?php /* Sign-in
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jan-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientPaths.cgi';
require_once $w3etc . '/LocalSettings.php';
require_once $w3etc . '/env.php';
require_once $w3etc . '/foo/session/dbConnect.php';
require_once $w3etc . '/foo/ci3_form_validation.php';

if (empty($_POST['uname']) || empty($_POST['passwd'])) {
    failedLoginAttempt("Empty-username-and/or-password");
}

$loginname = (strlen($_POST['uname']) > 5) ? strtolower(trim($_POST['uname'])) : "F";
$passwd = (strlen($_POST['passwd']) > 5) ? trim($_POST['passwd']) : "F";

// die($loginname . ' | ' . $passwd);
/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/

// Received valid inputs?
if ($loginname == "F" || $passwd == "F") {
    failedLoginAttempt("Invalid-username-and/or-password");
}


// Honeypot
if (strlen($_POST["captcha"]) > 0) {
    failedLoginAttempt("invalid-inputs");
}


// Validate and Sanitize input data
if (!alpha_numeric_dot($loginname) || strlen($loginname) < 1) {
    failedLoginAttempt("invalid-loginname");
}


/*
+-------------------------------------------------------+
| Validate Loginname and Password match                 |
+-------------------------------------------------------+
*/
$mysqli = cn1();

// Check Username & Password 
$query = 'select `id`,`passwd`, `fullname` from `users` where `loginname` ="' . $loginname . '" and `active` > 0';

$result = $mysqli->query($query);

if ($row = $result->fetch_assoc()) {

    if ($row['passwd'] == $passwd) {

        /* Start sessions */
        session_save_path($w3path . 'w3filedb/session');
        session_name($cookieName);

        session_start();
        session_regenerate_id();

        $sessionid = session_id();

        $_SESSION["user_id"] = $row["id"];
        $_SESSION["login"] = "T";
        $_SESSION["loginname"]  = $loginname;
        $_SESSION["displayname"] = $row['fullname'];

        $_SESSION["loginexp"]   = time() + $LoginTimeOut;
    } else {

        // Password did not match
        failedLoginAttempt("e1");
    }
} else {

    // Username not found
    failedLoginAttempt("e2");
}


/*
+-------------------------------------------------------+
| Save session in db                                    |
+-------------------------------------------------------+
*/
$mysqli = cn2();

/* sessi0ns
+----------------+-----------------------+------+-----+---------+-------+
| Field          | Type                  | Null | Key | Default | Extra |
+----------------+-----------------------+------+-----+---------+-------+
| sessionid      | varchar(30)           | NO   | PRI |         |       |
| loginname      | varchar(50)           | NO   |     | NULL    |       |
| project_id     | mediumint(8) unsigned | NO   |     | NULL    |       |
| projectname    | varchar(100)          | NO   |     |         |       |
| jobcode        | varchar(50)           | NO   |     | NULL    |       |
| role_id        | tinyint(3) unsigned   | NO   |     | NULL    |       |
| logintime      | datetime              | NO   |     | NULL    |       |
| logouttime     | datetime              | NO   |     | NULL    |       |
| intranet       | tinyint(1)            | YES  |     | NULL    |       |
| userpreference | varchar(250)          | NO   |     | NULL    |       |
| module         | varchar(45)           | NO   |     | studio  |       |
| active         | tinyint(1)            | YES  |     | NULL    |       |
+----------------+-----------------------+------+-----+---------+-------+
*/

$query = "insert into `sessi0ns` 
            (`sessionid`, `loginname`) 
        values (
            '$sessionid', '$loginname')";
// die($query);

if (!$mysqli->query($query)) {
    die("System error:: sid: $sessionid | loginname: $loginname");
}


/*
+-------------------------------------------------------+
| Redirect User to the Home Page						|
+-------------------------------------------------------+
*/
log2file("T", "$loginname | sid: $sessionid");
header("Location:" . $base_url . "studio/home.cgi");


/*
+-------------------------------------------------------+
| Error message and log                                 |
+-------------------------------------------------------+
*/
function failedLoginAttempt(
    $message
) {

    // Log this login attempt
    log2file("F", $message);

    // Deter bots by delaying response
    sleep(5);

    // Redirect to Login Page
    header("Location:" . BASE_URL . "login/index.cgi?e=$message");

    // Halt further execution
    die();
}

function log2file($status, $message)
{

    $dt = date("Y-m-d");
    $tm = date("H:i:s");

    $post = ($status == "F") ? json_encode($_POST) : 'ok';

    $log = "$status | $dt | $tm | REMOTE_ADDR: " . $_SERVER["REMOTE_ADDR"] . " | M: " . $message . " | " . $post/*." | ".$_SERVER["HTTP_USER_AGENT"] */;
    $logfile = W3PATH . "w3filedb/log/sign-in.log";

    $f = fopen($logfile, "a");
    fwrite($f, $log . "\n");
    fclose($f);
}
