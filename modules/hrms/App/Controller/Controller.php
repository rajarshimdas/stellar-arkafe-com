<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   14-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/


require_once W3APP . '/Controller/Config.php';
require_once W3APP . '/Controller/Session.php';
require_once W3APP . '/Controller/Validation.php';

// Set user type name
$bdUserTypeId = $user_type;
if (empty($_SESSION['user_type_name'])) {
    $bdUserTypeName = $bdUserType[$bdUserTypeId][0];
    $_SESSION['user_type_name'] = $bdUserTypeName;
} else {
    $bdUserTypeName = $_SESSION['user_type_name'];
}

// Store Route info in Session
// Todo

// Existing DB
if (!function_exists('cn0')) {
    function cn0(): object
    {
        $x = explode('|', db_aec2db);
        // $mysqli = new mysqli("localhost", "user", "password", "database");
        $mysqli = new mysqli($x[0], $x[1], $x[2], $x[3]);
        //echo $mysqli->host_info;

        if ($mysqli->connect_error) {
            die('Connection error[cn0]: ' . $mysqli->connect_error);
        }

        return $mysqli;
    }
}

// New DB | Read
if (!function_exists('cn2')) {
    function cn1(): object
    {
        $x = explode('|', db_aec25r);
        // $mysqli = new mysqli("localhost", "user", "password", "database");
        $mysqli = new mysqli($x[0], $x[1], $x[2], $x[3]);
        // echo $mysqli->host_info;

        if ($mysqli->connect_error) {
            die('Connection error[cn1]: ' . $mysqli->connect_error);
        }

        return $mysqli;
    }
}

// New DB | Write
if (!function_exists('cn2')) {
    function cn2(): object
    {
        $x = explode('|', db_aec25w);
        // $mysqli = new mysqli("localhost", "user", "password", "database");
        $mysqli = new mysqli($x[0], $x[1], $x[2], $x[3]);
        // echo $mysqli->host_info;

        if ($mysqli->connect_error) {
            die('Connection error[cn2]: ' . $mysqli->connect_error);
        }

        return $mysqli;
    }
}

function bdGetUsers(object $mysqli_cn0): ?array
{
    $query = "select * from `view_users` order by `displayname`";

    $result = $mysqli_cn0->query($query);
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}

function bdGetUsersById(object $mysqli_cn0): ?array
{
    $query = "select * from `view_users` order by `displayname`";

    $result = $mysqli_cn0->query($query);
    while ($row = $result->fetch_assoc()) {
        $users[$row['user_id']] = $row;
    }

    return $users;
}

// Get Subordinates for given uid
function bdGetSubordinates(int $uid, object $mysql_cn0): array
{
    $subordinates = [];
    $users = bdGetUsers($mysql_cn0);

    foreach ($users as $u) {
        if ($u['reports_to_user_id'] == $uid) {
            // echo $u['fname'].'<br>';
            $subordinates[] = $u['user_id'];
        }
    }
    return $subordinates;
}


function bdGetPeers($uid, $mysql_cn0)
{
    $peers = [];
    $u = bdGetUsersById($mysql_cn0);
    $rmId = $u[$uid]['reports_to_user_id'];

    $users = bdGetUsers($mysql_cn0);

    foreach ($users as $u) {
        if ($u['reports_to_user_id'] == $rmId) {
            // echo $u['fname'].'<br>';
            $peers[] = $u['user_id'];
        }
    }
    $peers[] = $rmId;
    return $peers;
}

function bdGetSubordinatesAndMe(int $uid, object $mysql_cn0): array
{

    $subs = bdGetSubordinates($uid, $mysql_cn0);
    $subs[] = $uid;
    return $subs;
}


function bdGetSubordinatesPeerAndMe(int $uid, object $mysql_cn0): array
{

    $subs = bdGetSubordinates($uid, $mysql_cn0);
    $subs[] = bdGetPeers($uid, $mysql_cn0);
    // $subs[] = ['user_id' => $uid];
    $subs = flattenArray($subs);

    return $subs;
}


if (!function_exists("bdReturnJSON")) {

    function bdReturnJSON(array $data, string $httpCode = '200')
    {
        // remove any string that could create an invalid JSON 
        // such as PHP Notice, Warning, logs...
        ob_start();
        ob_clean();

        // this will clean up any previously added headers, to start clean
        header_remove();

        // Set the content type to JSON and charset 
        // (charset can be set to something else)
        // add any other header you may need, gzip, auth...
        header("Content-type: application/json; charset=utf-8");

        // Set your HTTP response code, refer to HTTP documentation
        http_response_code($httpCode);

        // encode your PHP Object or Array into a JSON string.
        // stdClass or array
        echo json_encode($data);

        // making sure nothing is added
        // die();
    }
}


function bdTabAccessFlag(string $tab, string $bdUserTypeName, array $navtabs): bool
{

    // Find this tab
    foreach ($navtabs as $n) {
        if ($n[0] == $tab) {
            $thisTabAccess = $n[2];
            break;
        }
    }

    // Does this user type have access?
    foreach ($thisTabAccess as $ux) {

        if ($ux == "All") {
            return true;
        }

        if ($ux == $bdUserTypeName) {
            return true;
        }
    }

    return false;
}


function flattenArray($array)
{
    $result = [];

    foreach ($array as $item) {
        if (is_array($item)) {
            $result = array_merge($result, flattenArray($item));
        } else {
            $result[] = $item;
        }
    }

    return $result;
}

// Example usage:
// $nested = [1, [2, 3], [4, [5, 6]], 7];
// $flat = flattenArray($nested);
// print_r($flat);


function getPreviousISODate($ISODate)
{
    /*
    $timestamp = strtotime($ISODate);
    $previousTimestamp = strtotime('-1 day', $timestamp);
    return date('Y-m-d', $previousTimestamp);
    */
    return getPreviousDate($ISODate);
}

function getNextISODate($ISODate)
{
    /*
    $timestamp = strtotime($ISODate);
    $nextTimestamp = strtotime('+1 day', $timestamp);
    return date('Y-m-d', $nextTimestamp);
    */
    return getNextDate($ISODate);
}

function getNextDate($isoDate)
{
    $timestamp = strtotime($isoDate);
    $nextTimestamp = mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp) + 1, date('Y', $timestamp));
    return date('Y-m-d', $nextTimestamp);
}

function getPreviousDate($isoDate)
{
    $timestamp = strtotime($isoDate);
    $prevTimestamp = mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp) - 1, date('Y', $timestamp));
    return date('Y-m-d', $prevTimestamp);
}


function bdgetHRGroups($mysqli)
{
    $query = "SELECT * FROM `userhrgroup` order by `displayorder`";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $g[] = $row;
    }
    return $g;
}


function bdISODateToCal(string $date): ?string
{
    if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return null;
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return null;
    }

    return date('d-M-y', $timestamp);
}



function bdGeneratePassword($length)
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
}


function bd2hd($string)
{
    return ($string == '-') ? '' : $string;
}

function bd2fd($date)
{
    return ($date != 'NA') ? $date : '';
}

function bd2fdH($date){
    return ($date != 'NA') ? bdISODateToCal($date) : '';
}