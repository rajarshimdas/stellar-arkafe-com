<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 10-Dec-2007				|
| Updated On: 30-Jan-2008 (at Home till 4 am)		|
+-------------------------------------------------------+
| Drawing List Editing	- EditPanel (Right Panel)	|
+-------------------------------------------------------+
*/

// Get Session Variable
$sx = $_GET['sx'];

/* MySQL connection - select only */
include('../foo/arnav/config.php');
include('../foo/arnav/angels.cgi');

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


/* Validate the user */
if ($ValidUser < 1) die ("Error: Session could not be validated...");

/*
+-------------------------------------------------------+
| 	Only Valid users allowed to proceed					|
+-------------------------------------------------------+
*/

// Get dwglist.id of the selected drawing
$dwglist_id = $_GET['id']; 

/* Get details for the dwglist.id */
if ($dwglist_id) {

    // Load DWGid class
    include '../foo/t2DWGid.php';
    $dx = new DWGid($project_id,$roleid);
    $a = $dx->GetDWGDetails($dwglist_id,$mysqli);

}

?>


<table 
    height="100%"
    width="100%"
    border="0"
    cellspacing="0"
    cellpadding="0"
    style="font: normal 13px  sans-serif, Verdana, Arial, Helvetica, Frutiger, Futura, Trebuchet MS;"
    >
    <!-- Top Row -->
    <tr valign="center">
        <?php
        if (!$a["sheetno"]) {

            /* Drawing not selected for editing */
            echo '<td height="10%" colspan="2" align="center"bgcolor="#E8E9FF" style="font-size:125%;">
                    <span style="font-weight:bold;">
                        Select Drawing
                    </span>
                </td>';

        } else {

            /* Drawing selected for editing */
            echo '<td height="10%" colspan="2" align="center"bgcolor="#E8E9FF" style="font-size:125%;">
                    <span style="font-weight:bold;">'
                    .$a["sheetno"].
                    '</span>
                    <br>
                    <span style="font-size:90%;">'
                    .$a["title"].
                    '</span>
                    <br>&nbsp;<br>
                </td>';

        }
        ?>
    </tr>

    <?php
    if ($dwglist_id) {
        ?>

    <!-- Menu Row -->
    <tr valign="bottom">
            <?php

            /* Include class session_x */
            include('../foo/session_x.php');
            $px = new session_x($sx,$mysqli);
            if ($value = $px->GetTagValue('tab')) $activemenu = $value; else $activemenu = 'x1';
            
            // Show the menu
            echo '<td height="5%" colspan="2" align="center"bgcolor="#E8E9FF" style="font-size:125%;">
                    <table border="0" width="100%" height="100% cellspacing="0" cellpadding="0">
                <tr>';

            // Details
            if ($activemenu === "x1") {
                echo '<td align="center" style="width:34%;font-weight:bold;color:BLACK;"><a href="t2DWGid-tabs.php?x=x1&sx='.$sx.'&id='.$dwglist_id.'" style="text-decoration:none;color:black;color:black;">Details</a></td>';
            } else {
                echo '<td align="center" style="width:34%;color:GREY;"><a href="t2DWGid-tabs.php?x=x1&sx='.$sx.'&id='.$dwglist_id.'" style="text-decoration:none;color:black;">Details</a></td>';
            }

            /* Schematic
            if ($activemenu === "x2") {
                echo '<td align="center" style="width:32%;font-weight:bold;color:BLACK;"><a href="t2DWGid-tabs.php?x=x2&sx='.$sx.'&id='.$dwglist_id.'" style="text-decoration:none;color:black;">Schematic</a></td>';
            } else {
                echo '<td align="center" style="width:32%;color:GREY;"><a href="t2DWGid-tabs.php?x=x2&sx='.$sx.'&id='.$dwglist_id.'" style="text-decoration:none;color:black;">Schematic</a></td>';
            }
            */

            // GFC
            if ($activemenu === "x4") {
                echo '<td align="center" style="width:34%;font-weight:bold;color:BLACK;"><a href="t2DWGid-tabs.php?x=x4&sx='.$sx.'&id='.$dwglist_id.'" style="text-decoration:none;color:black;">GFC</a></td>';
            } else {
                echo '<td align="center" style="width:34%;color:GREY;"><a href="t2DWGid-tabs.php?x=x4&sx='.$sx.'&id='.$dwglist_id.'" style="text-decoration:none;color:black;">GFC</a></td>';
            }

            echo '</tr>
                    </table>
                </td>';

            ?>
    </tr>


    <!-- iframe contents -->
    <tr valign="top">
        <td height="80%">
            <iframe id="usrDIV" <?php echo 'src="t2DWGid-edit-'.$activemenu.'.php?sx='.$sx.'&id='.$dwglist_id.'"'; ?> width="100%" height="100%" frameborder="0"></iframe>
        </td>
    </tr>

    <form action="t2DWGid-action.php" method="GET">

        <input name="id" type="hidden" value="<?php echo $dwglist_id; ?>">
        <input name="sx" type="hidden" value="<?php echo $sx; ?>">
        <input name="sheetno" type="hidden" value="<?php echo $a["sheetno"]; ?>">
            <?php

            // Delete and Done buttons
            if ($a["sheetno"]) {
                echo '<tr><td height="5%" colspan="2" align="center"bgcolor="#E8E9FF" style="font-size:125%;">';
                if ($a["r0issuedflag"] < 1) {
                    // No GFC drawings sent out yet - Drawing can be deleted from the dwglist
                    echo '<input type="submit" name="go" value="Delete" style="width:100px;">';
                }
                echo '<input type="submit" name="go" value="Done" style="width:100px;">';
                echo '</td><tr>';
            }

            ?>
    </form>

        <?php
    } else {
        ?>
    <tr valign="top">
        <td>
            <form action="t2DWGid-get-id.php" method="GET">
                <input name="sx" type="hidden" value="<?php echo $sx; ?>">
                <input name="ProjID" type="hidden" value="<?php echo $project_id; ?>">
                <br>
                &nbsp;Enter a Drawing Number:&nbsp;<input name="sheetno" type="text">&nbsp;<input name="go" type="submit" value="Go">
                <br><br>&nbsp;or<br><br>
                &nbsp;Click a Drawing Sheet Number from the list to start editing.
            </form>
        </td>
    </tr>
        <?php }
    $mysqli->close();
    ?>

</table>
