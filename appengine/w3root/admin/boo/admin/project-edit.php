<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 12-Jan-09					                |
| Updated On: 21-Oct-10					                |
+-------------------------------------------------------+
| */ $mysqli = cn2(); /*                                |
+-------------------------------------------------------+ 
*/

$projectid      = $_POST["pid"];
$projectname    = addslashes($_POST["pn"]);
$jobcode        = strtoupper($_POST["jc"]);
$pm_uid         = $_POST["pm_uid"];             // Project Leader's uid
$pc_uid         = $_POST["pc_uid"];             // Project Coordinator
$branch_id      = $_POST["bid"];                // Branch ID
$go             = $_POST["go"];
$url            = $_SERVER["HTTP_HOST"];
$did            = 2;                            // Domain Id
$active         = isset($_POST["active"])? 1: 0;
$pm_roleId      = 10;                           // PM's roleId
$pc_roleId      = 12;

$cdt            = $_POST["cdt"];
$cpy            = $_POST["cpy"];
$cpm            = $_POST["cpm"];
$ek             = (isset($_POST["ek"]))? 1: 0;
$esdt           = $_POST["esdt"];
$erate          = $_POST["erate"];
$enote          = $_POST["enote"];


// var_dump($_POST);
if (!$cdt) $cdt = "0000-00-00";
if (!$esdt) $esdt = "0000-00-00";
if (!$erate) $erate = 0;

// echo 'cdt: '.$cdt.'<br>';


// No action required if Cancel button
if ($go === "Cancel") {
    header("Location:sysadmin.cgi?a=Project Edit");
    die;
}

// Get Project Leader loginname

// Delete the current project leader row in roleinproject

// Check if this Project Leader is already a member of this project - update or insert

$mysqli->autocommit(FALSE);

$flag           = 0; /* 0 is hidden, 1 is success and 2 is error */
$error_flag     = 0;
$message        = "M: ";

// Insert data into the Projects Table
$query = "update 
            projects 
        set 
            projectname             = '$projectname',
            jobcode                 = '$jobcode',
            branch_id               = '$branch_id',
            domain_id               = $did,
            active                  = $active,
            contractdt              = '$cdt',
            contract_period_years   = $cpy,
            contract_period_months  = $cpm,
            escalation_kickoff      = $ek,
            escalationdt_start      = '$esdt',
            escalation_rate         = $erate,
            escalation_note         = '$enote'
        where
            id                      = $projectid";

// echo "Q1: $query"; die;

if (!$mysqli->query($query)) {
    $message = $message . "Error[Q1] :: " . $mysqli->error;
    $flag++;
    $error_flag++;
}


// Set Project Heads

// 1. Project Manager
$rx = setProjectHeads($projectid, $pm_uid, $pm_roleId, $mysqli, $flag, $error_flag, $message);
$flag = $rx[0];
$error_flag = $rx[1];
$message = $rx[2];

// 2. Project Coordinator
//    Check PC is not same as PM
if ($pc_uid != $pm_uid) {
    $rx = setProjectHeads($projectid, $pc_uid, $pc_roleId, $mysqli, $flag, $error_flag, $message);
    $flag = $rx[0];
    $error_flag = $rx[1];
    $message = $rx[2];
} else {
    $error_flag++;
    $flag = 2; /* 0 is hidden, 1 is success and 2 is error */
    $message = "Error :: Project Coordinator selected was same as Project Manager";
}

// Commit Data
if ($error_flag > 0) {

    $mysqli->rollback();
    // echo $m2; 
    // die;
    $message = $projectname . ' update failed.';
} else {
    $mysqli->commit();

    $flag = 1;  // Success
    $message = $projectname . ' update is done.';
}

$mysqli->close();

header("Location:sysadmin.cgi?a=Project Edit&pid=$projectid&flag=$flag&rx=$message");

/*
+-------------------------------------------------------+
| Functions                                             |
+-------------------------------------------------------+
*/
function setProjectHeads(
    $projectid,
    $pm_uid,        // Project Head user_id
    $pm_roleId,     // Project Head roles_id
    $mysqli,
    $flag,
    $error_flag,
    $message
) {


    // Delete the current Project Head
    $query = "delete from roleinproject where project_id = $projectid and roles_id = $pm_roleId";
    if (!$mysqli->query($query)) {
        $message = "Error[Q2] :: " . $mysqli->error;
        $flag++;
        $error_flag++;
    }

    // Check if the Project Head is already a member of this project
    $member_flag = 0;
    $query = "select 1 from roleinproject where project_id = $projectid and user_id = $pm_uid";

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $member_flag = $row[0];

        $result->close();
    }

    // Register Project Head in roleinproject table
    if ($member_flag > 0) {

        $query = "update
                    roleinproject
                set
                    roles_id    = $pm_roleId,
                    active      = 1
                where
                    project_id  = $projectid and
                    user_id     = $pm_uid";
        // echo "Q3a: $query";

        if (!$mysqli->query($query)) {
            $message = "Error[Q3a] :: " . $mysqli->error;
            $flag = 2;
            $error_flag = 1;
        }
    } else {

        $query = "insert into roleinproject
                    (project_id,user_id,roles_id,active)
                values
                    ($projectid,$pm_uid,$pm_roleId,1)";
        // echo "Q3b: $query";

        if (!$mysqli->query($query)) {
            $message = "Error[Q3b] :: " . $mysqli->error;
            $flag = 2;
            $error_flag = 1;
        }
    }

    return [$flag, $error_flag, $message];
}
