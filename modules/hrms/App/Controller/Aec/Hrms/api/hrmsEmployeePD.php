<?php
$thisUId        = $_POST['uid'];
$dxDisplayName  = $_POST['dxDisplayName'];
$dxLoginName    = $_POST['dxLoginName'];
$dxFname        = $_POST['dxFname'];
$dxMname        = $_POST['dxMname'];
$dxLname        = $_POST['dxLname'];
$dxDOB          = empty($_POST['dxDOB']) ? '1900-01-01' : $_POST['dxDOB'];
$dxGender       = $_POST['dxGender'];
$dxBloodGroup   = $_POST['dxBloodGroup'];
$message        = 'ok';


if (!isAlphaNum($dxDisplayName)) {
    $message = '<div class="rd-messagebox-fail">Displayname must contain Alpha-numeric characters only.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

if (!isAlphaNumDot($dxLoginName)) {
    $message = '<div class="rd-messagebox-fail">Loginname must contain Alpha-numeric and Dot characters only.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

if (!isAlphaNumDotAndEmpty($dxFname)) {
    $message = '<div class="rd-messagebox-fail">First name must contain Alpha-numeric characters only.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

if (!isAlphaNumDotAndEmpty($dxMname)) {
    $message = '<div class="rd-messagebox-fail">Middle name must contain Alpha-numeric characters only.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

if (!isAlphaNumDotAndEmpty($dxLname)) {
    $message = '<div class="rd-messagebox-fail">Last name must contain Alpha-numeric characters only.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

if (!isValidISODate($dxDOB)) {
    $message = '<div class="rd-messagebox-fail">Invalid Date of Birth.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

if(!isAlphaNumAndSafe($dxBloodGroup)){
    $message = '<div class="rd-messagebox-fail">Blood group can contain Alphabets and + or -.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

$mysqli = cn2();
$mysqli->autocommit(FALSE);

$query = "UPDATE 
            `users` 
        SET 
            `loginname` = '$dxLoginName', 
            `fullname` = '$dxDisplayName' 
        WHERE 
            (`id` = '$thisUId')";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    $message = '<div class="rd-messagebox-fail">Error[Q1] :: ' . $mysqli->error . '</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

$query = "UPDATE 
            `users_a` 
        SET 
            `fname` = '$dxFname', 
            `lname` = '$dxLname', 
            `mname` = '$dxMname', 
            `dob` = '$dxDOB', 
            `gender` = '$dxGender',
            `bloodgroup` = '$dxBloodGroup'
        WHERE 
            (`user_id` = '$thisUId')";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    $message = '<div class="rd-messagebox-fail">Error[Q1] :: ' . $mysqli->error . '</div>';
    die(bdReturnJSON(["F", $message, 'x']));
} else {
    $mysqli->commit();
    bdReturnJSON(['T', $message, 'x']);
}
