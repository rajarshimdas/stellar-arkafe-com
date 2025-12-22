<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 10-Dec-2007				|
| Updated On: 31-Jan-2008 				|
+-------------------------------------------------------+
| Drawing List Editing	- iframe contents		|
+-------------------------------------------------------+
*/
$hostname = $_SERVER['HTTP_HOST'];
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
    $project_id     = $a["projectid"];
    $roleid		= $a["roleid"];

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
<link rel="stylesheet" type="text/css" href="<?php echo 'http://'.$hostname.'/matchbox/mbox/calendar/styles.css'; ?>" />
<script type="text/javascript" src="<?php echo 'http://'.$hostname.'/matchbox/mbox/calendar/classes.js'; ?>"></script>
<script type="text/javascript">

    window.onload = function calender(){

        var dp_cal_03;
        dp_cal_03 = new Epoch('epoch_popup','popup',document.getElementById('newr0targetdt'));

        /*
        var dp_cal_02;
        dp_cal_02 = new Epoch('epoch_popup','popup',document.getElementById('commit_dt'));
         */
    }

</script>

<table width="100%" border="0">


    <?php /*
+-------------------------------------------------------+
|	Commited Dates					|
+-------------------------------------------------------+
    
    if ($roleid < 45) {
        // The user is a TL / DM or DM - Jr.
        ?>

    <tr>
    <form action="t2DWGid-edit-x4b.php" method="GET">
        <input name="id" type="hidden" value="<?php echo $dwglist_id; ?>">
        <input name="sx" type="hidden" value="<?php echo $sx; ?>">
        <td align="left" valign="top">
            <input name="commitdt" style="width:65%;" type="text" <?php echo "value='".$a["commitdt"]."' ";
                if ($a["r0issuedflag"] > 0) echo "readonly"; else echo "id='commit_dt'"; ?>> : Committed Date
                <?php
                if ($a["r0issuedflag"] < 1) {
                    echo 	'<br><textarea name="reason" style="width:65%;height:80px;"></textarea> : Reason
							<br><input type="submit" name="go" value="Update" onclick="SetDivPosition()">'; 
                }
    ?>
            <br>&nbsp;
        </td>
    </form>
</tr>


    <?php } 
     
    */


    /*
+-------------------------------------------------------+
|	GFC Stage - Target Date Tracking		|
+-------------------------------------------------------+
    */
    ?>

    <tr>
    <form action="t2DWGid-edit-x4a.php" method="GET">
        <input name="id" type="hidden" value="<?php echo $dwglist_id; ?>">
        <input name="sx" type="hidden" value="<?php echo $sx; ?>">
        <td align="left" valign="top">
            <input name="targetdt_gfc" style="width:65%;" type="text" value="<?php echo $a["r0targetdt"]; ?>" readonly> : Target Date
            <br><input name="newtargetdt_gfc" style="width:65%;" type="text" <?php echo "value='".$a["newr0targetdt"]."'";
                       if ($a["r0issuedflag"] > 0) echo "readonly"; else echo "id='newr0targetdt'"; ?> > : Revised Date

            <?php
            if ($a["r0issuedflag"] < 1) {
                echo 	'<br><textarea name="reason_gfc" style="width:65%;height:80px;"></textarea> : Reason
                            <br><input type="submit" name="go" value="Update" onclick="SetDivPosition()">';
            }
            ?>
            <br>&nbsp;
        </td>
    </form>
</tr>
</table>

<?php $mysqli->close(); ?>