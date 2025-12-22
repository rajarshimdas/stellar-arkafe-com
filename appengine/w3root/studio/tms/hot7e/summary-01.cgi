<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 28-May-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
| Timesheet :: Summary - Teammatewise                  	|
+-------------------------------------------------------+
*/

$this_userid    = $_GET["uid"];
$this_fullname  = $_GET["fn"];
$go             = $_GET["go"];
//echo "UserID: $userid<br>Go: $go";


if ($go === "View") {

    echo '<br><span style="font-weight:bold">'.$this_fullname.'</span>';
    // Tabulate timesheet data
    include('hot7e/teammate_timedata.cgi');

}

if ($go === "Approve" && $roleid < 45) {

    $query = "update timesheet set approved = 1 where user_id = $this_userid and project_id = $projectid and active = 1";

    include('foo/arnav/dblogin.cgi');
    if (!$mysqli->query($query)) echo "Q1 error";
    $mysqli->close();

    header("Location:rajarshi.cgi?a=t7xsummary");

}

?>


