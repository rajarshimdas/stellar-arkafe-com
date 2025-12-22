<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 09-Jan-09					                |
| Updated On: 19-Mar-12                               	|
+-------------------------------------------------------+
var_dump($_POST);
die;
*/

// Get Variables
$projectname    = isset($_POST["pn"]) ? addslashes($_POST["pn"]) : "x|x";
$jobcode        = isset($_POST["jc"]) ? strtoupper($_POST["jc"]) : "x|x";
$pm_uid         = isset($_POST["pm_uid"]) ? $_POST["pm_uid"] : 0;
$pc_uid         = isset($_POST["pc_uid"]) ? $_POST["pc_uid"] : 0;
$branch_id      = $_POST["bid"];
$domain_id      = 2;

// New
$contract_dt    = (strlen($_POST['cdt']) > 8)? $_POST['cdt'] : '0000-00-00';

$contract_period_years      = $_POST['cpy'];
$contract_period_months     = $_POST['cpm'];

$escalation_startdt = (strlen($_POST['esdt']) > 8) ? $_POST['esdt'] : '0000-00-00';

// Kickoff checkbox
$kickoff = 0;
if (isset($_POST['ek'])) {
    if ($_POST['ek'] == 'on') $kickoff = 1;
}

// Escalation Rate and Note
$erate = isset($_POST['erate']) ? $_POST['erate'] : 0;
$enote = isset($_POST['enote']) ? $_POST['enote'] : '-';

/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/
$flag = 0;

// echo "pm_uid: $pm_uid | pc_uid: $pc_uid<br>";

// Check if all data is available
if (
    $projectname == 'x|x' ||
    $jobcode == 'x|x' ||
    $pm_uid < 1 ||
    $branch_id < 1
) {
    $message = "Invalid or Incomplete data input";
    $flag = 2;
}


// Check projectname
$query = "select 'T' as `pn` from `projects` where `projectname` = '$projectname'";
//echo ("Q: ".$query);

if ($result = $mysqli->query($query)) {
    $row = $result->fetch_assoc();

    if ($row["pn"] == "T") {
        $message = "Projectname ( $projectname ) already exists.";
        $flag = 2;
    }
}


// Check jobcode is unique
$query = "select 'T' as `flag` from `projects` where `jobcode` = '$jobcode'";
//echo ("Q: ".$query);

if ($result = $mysqli->query($query)) {
    $row = $result->fetch_assoc();

    if ($row["flag"] == "T") {
        $message = "Jobcode ( $jobcode ) already exists.";
        $flag = 2;
    }
}



// Escalation Rate
if (!numeric($erate)) {
    $message = "Escalation rate must be a real number. " . $erate;
    $flag = 2;
}


// echo "flag: $flag | message: $message";
if ($flag > 0) {

    header("Location:sysadmin.cgi?a=Project New&flag=$flag&message=$message");
    die;
}



/*
+-------------------------------------------------------+
| Save in database                                      |
+-------------------------------------------------------+
*/
if ($flag < 1) {

    // Add New Project
    require 'boo/admin/project-functions.php';
    require BD . 'Controller/Projects/Projects.php';

    $rx = addNewProject(
        $projectname,
        $jobcode,
        $pm_uid,
        $pc_uid,
        $branch_id,
        $domain_id,
        $contract_dt,
        $contract_period_years,
        $contract_period_months,
        $escalation_startdt,
        $kickoff,
        $erate,
        $enote,
        /* $mysqli */
        cn2()
    );

    if ($rx[0] == "T") {
        $message = "Project $projectname added.";
        $flag = 1;
        $thisPid = $rx[1];
    } else {
        $message = "Error in adding the Project. " . $rx[1];
        $flag = 2;
        $thisPid = 0;
    }

    $mysqli->close();

    header("Location:sysadmin.cgi?a=Project New&flag=$flag&message=$message&rx=$message&pid=$thisPid");
}
