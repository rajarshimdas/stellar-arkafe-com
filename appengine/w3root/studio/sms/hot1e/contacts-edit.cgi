<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 2007					                    |
| Updated On: 10-Feb-2012				                |
+-------------------------------------------------------+
| Edit Contacts						                    |
+-------------------------------------------------------+
*/

$name         = $_GET['name'];
$role         = $_GET['role'];
$company     = $_GET['company'];
$phoneno     = $_GET['phoneno'];
$email         = $_GET['email'];
$transname_id    = $_GET["transname_id"];
$rid            = $_GET["role_id"];
$tid            = $_GET["transadd_id"];

include('foo/arnav/angels.cgi');
?>

<table style="text-align: left; width: 100%;background:#E8E9FF;" border="0" cellpadding="2" cellspacing="0">
    <tr>
        <td align="center" width="25%" valign="top">
            <?php echo "Editing Contact :: $name"; ?>
        </td>
        <td width="70%">
            <form name="project-contacts" action="execute.cgi" method="POST">
                <input type='hidden' name='a' value='t1xcontacts-edit-action'>
                <input type="hidden" name="sx" <?php echo 'value="' . $sessionid . '"'; ?>>
                <input type="hidden" name="transname_id" <?php echo 'value="' . $transname_id . '"'; ?>>
                <!-- RoleId Legacy compatibility -->
                <input type="hidden" name="rid" value="240">

                <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0">
                    <tbody>
                        <tr>
                            <td align='right' width='45%'>Company:</td>
                            <td>
                                <select name="cid" style="width:100%">
                                    <?php
                                    echo "<option value='$tid'>$company";
                                    $sql = "select id, company from transadd where project_id=$projectid and active = 1";

                                    if ($result = $mysqli->query($sql)) {
                                        while ($row = $result->fetch_row()) {
                                            if ($transadd_id !== $row[0]) echo "<option value='$row[0]'>$row[1]";
                                        }
                                        $result->close();
                                    } else echo "Error: $mysqli->error";
                                    ?>
                                </select>
                            </td>
                            <td>
                                <!--<input name='submit' type="submit" value="New">-->
                            </td>
                        </tr>
                        <tr>
                            <td align='right' width='30%'>Contact Name:</td>
                            <td align="left" width="50%">
                                <input name="cnm" value="<?php echo $name; ?>" style="width:100%;">
                            </td>
                            <td width="20%"> </td>
                        </tr>
                        <tr>
                            <td align='right' width='45%'> Phone No:</td>
                            <td>
                                <input name="pno" type="text" style="width:100%" value="<?php echo $phoneno; ?>">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align='right' width='45%'> Email:</td>
                            <td>
                                <input name="eml" type="text" style="width:100%" value="<?php echo $email; ?>">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">
                                <input name="go" type="submit" value="Edit" style="width:80px;">
                                <input name="go" type="submit" value="Delete" style="width:80px;">
                                <!-- <input name="go" type="submit" value="Cancel" style="width:80px;"> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </td>
        <td width="5%"></td>
    </tr>
</table>
<?php
$mysqli->close();
?>

<!-- Back to Contacts page button -->
<br>
<form action="project.cgi" method="GET">
    <input type="hidden" name="a" value="t1xcontacts">
    <input type="submit" name="go" value="<< Back to Contacts">
</form>