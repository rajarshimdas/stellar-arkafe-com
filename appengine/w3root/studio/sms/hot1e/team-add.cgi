<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 						                    |
| Updated On: 21-Oct-2010				                |
+-------------------------------------------------------+
*/

// Get User Inputs
$memberUID = $_POST['uid'];    // User ID
$memberRID = $_POST['rid'];    // Role ID

$memberRID = 14;                // Fixed role_id
// echo "<br>Uname: $uname RoleID: $role ProjID: $projectid";

/* User role validation */
if ($roleid > 40) {
    $mc = "$loginname, you do not have Team editing rights for $projectname.";
    header("Location:project.cgi?a=t1xteam&mc=$mc");
    die;
}

/* Data Validation */
if (!$memberUID || !$memberUID) {
    $mc = "Incomplete Data";
    header("Location:project.cgi?a=t1xteam&mc=$mc");
    die;
}
/*
+-------------------------------------------------------+
| Database						                        |
+-------------------------------------------------------+
*/
include "foo/arnav/dblogin.cgi";

/* Validation: Check if the user is already registerd for this project */
$user_active_flag = 0;

$sql25 = "select 
            1
        from
            roleinproject
        where
            project_id = $projectid and
            user_id = $memberUID";
// echo "<br>SQL25: $sql25";

if ($r2 = $mysqli->query($sql25)) {

    $row = $r2->fetch_row();
    $user_active_flag = $row[0];

    $r2->close();
} else {
    printf("<br>Error[25]: %s\n", $mysqli->error);
    die;
}
// echo "<br>user_active_flag: $user_active_flag";

/* Register user in the roleinproject table */
if ($user_active_flag < 1) {

    $sql01 = "insert into 
                roleinproject (project_id, user_id, roles_id, active)
            values
                ($projectid, $memberUID, $memberRID, 1)";

    echo "<br>SQL01: $sql01";

    if (!$mysqli->query($sql01)) {
        printf("<br>Error[01]: %s\n", $mysqli->error);
        die;
    }
} else {
    $message = "&mc=Selected user is an existing team member";
}
$mysqli->close();

/*
+-------------------------------------------------------+
| Redirection						                    |
+-------------------------------------------------------+
*/
header("Location:project.cgi?a=t1xteam$message");
