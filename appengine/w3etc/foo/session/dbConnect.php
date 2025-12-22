<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 30-Jan-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/

// Select User
function cn1() {

    $host   = DB_HOST;
    $un     = CN1_UNAME;
    $pw     = CN1_PASSWD;
    $db     = DB_NAME;

    $mysqli = new mysqli($host, $un, $pw, $db);
    if (mysqli_connect_errno()) {
        printf("MySQL[cn1]: %s\n", mysqli_connect_error());
        die;
    }
    // printf("MySQL[1]: %s\n", $mysqli->host_info);
    return $mysqli;

}

// Super User
function cn2() {

    $host   = DB_HOST;
    $un     = CN2_UNAME;
    $pw     = CN2_PASSWD;
    $db     = DB_NAME;

    $mysqli = new mysqli($host, $un, $pw, $db);
    if (mysqli_connect_errno()) {
        printf("MySQL[cn2]: %s\n", mysqli_connect_error());
        die;
    }
    // printf("MySQL[1]: %s\n", $mysqli->host_info);
    return $mysqli;

}
