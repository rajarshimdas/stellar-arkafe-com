<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 10-Dec-2007				|
| Updated On: 12-Feb-2008 				|
+-------------------------------------------------------+
| Drawing List Editing	- iframe contents		|
+-------------------------------------------------------+
*/

// Get Session Variable
$sx = $_GET['sx'];

/* MySQL connection - select only */
include('../foo/arnav/config.php');
include('../foo/arnav/angels.cgi');
//echo "MySQL: $mysqli->host_info<br>";

/* Validate the session */
if (!$sx) {

    die('<h3>Error: Session invalid...</h3>');

} else {

    include('../foo/StartSession.php');
    $ValidUser 	= 0;

    /* Validate and return critical information about this login session */
    $a 		= StartSession($sx,$mysqli);
    $ValidUser 	= $a["ValidUser"];
    $project_id = $a["projectid"];
    $roleid	= $a["roleid"];

}

/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");
/* +-------------------------------------------------------+ */

// Get dwglist.id of the selected drawing
$dwglist_id = $_GET['id'];

/* Load DWGid class */
include '../foo/t2DWGid.php';
$dx = new DWGid($project_id,$roleid);
$a = $dx->GetDWGDetails($dwglist_id,$mysqli);

?>

<table width="100%" border="0">
    <?php /*
+-------------------------------------------------------+
|	Drawing Details					|
+-------------------------------------------------------+
    */
    ?>

    <tr>
    <form action="t2DWGid-edit-x1a.php" method="GET">
        <td valign="top" align="left">

            <input name="id" type="hidden" value="<?php echo $dwglist_id; ?>">
            <input name="sx" type="hidden" value="<?php echo $sx; ?>">
            <input name="title" type="text" value="<?php echo $a["title"]; ?>" style="width:65%;">&nbsp;: Title
            <br><textarea name="remark" rows="2" style="width:65%; height:80px;"><?php echo $a["remark"]; ?></textarea>&nbsp;: Remark
            <br>
            <!--
            <select name="actionby" style="width:65%">

            <?php /* Display the Action By Drop down list

                // Default option set to current data
                if ($a["actionby"] === "-") echo "<option>-- None/Select --"; else echo "<option>".$a["actionby"]."<option>-- None/Select --";

                // Get list of team members
                $sql = "select loginname,roles_id from roleinproject where project_id = '$project_id' and active = 1 order by loginname";

                if ($result = $mysqli->query($sql)) {

                    // fetch object array
                    while ($row = $result->fetch_row()) {
                        if ($a["actionby"] !== $row[0]) echo "<option>$row[0]";
                    }

                    // free result set 
                    $result->close();
                } else {
                    echo "Error: $mysqli->error";
                }
            */
            ?>
            </select>&nbsp;: Action By
            -->
            <br><input name="go" type="submit" value="Update" onclick="SetDivPosition()">
            <br>&nbsp;
        </td>
    </form>
</tr>

</table>

<?php $mysqli->close(); ?>