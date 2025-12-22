<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 						                    |
| Updated On: 22-Oct-10					                |
+-------------------------------------------------------+
*/ 

$memberUID  = $_POST["uid"];
$memberRID  = $_POST["rid"];
$submit     = $_POST["go"];
// echo "memberIID: $memberUID memberRID: $memberRID<br>";

/* User rights validation */
if ($roleid > 40) {
    $mc = "$loginname, you do not have Team editing rights for $projectname.";
    header("Location:project.cgi?a=t1xteam&mc=$mc");
    die;
}

include 'foo/arnav/dblogin.cgi';
/*
+-------------------------------------------------------+
| Delete						                        |
+-------------------------------------------------------+
*/
$sql33 = "delete from
                roleinproject
            where
                project_id  = $projectid and
                user_id     = $memberUID";
// echo "SQL33: $sql33";

if ($submit === "Yes") {
    if (!$mysqli->query($sql33)) {
        printf("Error[33]: %s\n", $mysqli->error);
        die;
    }
}

/*
+-------------------------------------------------------+
| Edit  						                        |
+-------------------------------------------------------+

$sql35 = "update
            roleinproject
        set
            roles_id    = $memberRID
        where
            project_id  = $projectid and
            user_id     = $memberUID";
// echo "<br>SQL35: $sql35";

if ($submit === "Edit") {
    if (!$mysqli->query($sql35)) {
        printf("Error[35]: %s\n", $mysqli->error);
        die;
    }
}
*/

$mysqli->close();

/*
+-------------------------------------------------------+
| Redirect						                        |
+-------------------------------------------------------+
*/
header("Location:project.cgi?a=t1xteam");

?>