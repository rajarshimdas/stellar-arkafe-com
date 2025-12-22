<?php
/*
  +-------------------------------------------------------+
  | Rajarshi Das						|
  +-------------------------------------------------------+
  | Created On: 						|
  | Updated On: 21-Oct-2010				|
  +-------------------------------------------------------+
 */

$memberUID = $_GET["uid"]; // This member's User ID
//$memberRID = $_GET["rid"]; // This member's Role ID
$memberRID = 14;

/* User rights validation */
if ($roleid > 40) {
    $mc = "$loginname, you do not have Team editing rights for $projectname.";
    header("Location: rajarshi.cgi?a=t1xteam&mc=$mc");
    die;
}

/* Get Details for this user */
$query = "select fullname from users where id = $memberUID";
if ($result = $mysqli->query($query)) {

    $row = $result->fetch_row();
    $memberName = $row[0];
    $result->close();
}

// $query = "select roles from roles where id = $memberRID";
$query = "select name from userhrgroup where id = $memberRID";
if ($result = $mysqli->query($query)) {

    $row = $result->fetch_row();
    $memberRole = $row[0];
    $result->close();
}

//$query = "select id, roles from roles where id > 30 and id < 200 order by id";
$query = "select id, name from userhrgroup where id > 12 order by displayorder";
if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {
        $rolesX[] = array("id" => $row[0], "roles" => $row[1]);
    }

    $result->close();
}

$mysqli->close();
?>
<form action='execute.cgi' method='POST' style="background:#E8E9FF;">
    
    <input type='hidden' name='a' value='t1xteam-edit'>
    <input type="hidden" name="sx" <?php echo 'value="' . $sessionid . '"'; ?>>
    <input type='hidden' name='username' value="<?php echo $username; ?>">
    <input type='hidden' name='fn' value="<?php echo $memberName; ?>">
    <input type="hidden" name="uid" value="<?php echo $memberUID; ?>">
    
    <table style="text-align: left; width: 100%;background:#E8E9FF;" border="0"  cellpadding="2" cellspacing="0">
        <tbody>
            <tr>
                <td align="center" valign="top" style="height: 50px; vertical-align: middle">
                    Confirm Remove Teammate :: <?php echo $memberName; ?>&nbsp;
                    <input type="submit" name="go" value="Yes" style="width:80px;">
                    <input type="submit" name="go" value="No" style="width:80px;">
                </td>
            </tr>

        </tbody>
    </table>
</form>
