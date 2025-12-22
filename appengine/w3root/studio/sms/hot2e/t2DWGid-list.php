<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 10-Dec-2007				|
| Updated On: 29-Jan-2008				|
+-------------------------------------------------------+
| Drawing List Editing	- SelectPanel (Left Panel)	|
+-------------------------------------------------------+
*/

// Get Session Variable
$sx = $_GET["sx"];

/* MySQL connection - select only */
include('../foo/arnav/config.php');
include('../foo/arnav/angels.cgi');

/* Validate the session */
if (!$sx) {

    die('<h3>Error: Session invalid...</h3>');

} else {

    include('../foo/StartSession.php');
    $ValidUser = 0;
    /* Validate and return critical information about this login session */
    $a = StartSession($sx,$mysqli);

    $ValidUser 	= $a["ValidUser"];
    $roleid 	= $a["roleid"];
    $project_id = $a["projectid"];

}

/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");

/*
+-------------------------------------------------------+
| Page specific functions start from here		|
+-------------------------------------------------------+
*/

// Get Variables
$blockno 	= $_GET["blockno"];
$dc		= $_GET["dc"];
//echo "Blockno: $blockno Disciplinecode: $dc";

?>
<script type="text/javascript">

    window.onload = function(){

        var strCook = document.cookie;

        if(strCook.indexOf("++")!=0){

            var intS = strCook.indexOf("++");
            var intE = strCook.indexOf("@");
            var strPos = strCook.substring(intS+2,intE);
            document.getElementById("usrDIV").scrollTop = strPos;

        }

    }

    function SetDivPosition(){

        var intY = document.getElementById("usrDIV").scrollTop;
        document.cookie = "yPos=++" + intY + "@" ;

    }

    function ResetDivPosition(){

        var intY = 0;
        document.cookie = "yPos=++" + intY + "@" ;

    }

    function windowSize() {
        var myWidth = 0, myHeight = 0;
        if( typeof( window.innerWidth ) == 'number' ) {
            //Non-IE
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
            //IE 6+ in 'standards compliant mode'
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            //IE 4 compatible
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }
        window.alert( 'Width = ' + myWidth );
        window.alert( 'Height = ' + myHeight );
    }



</script>

<body>
    <table
        height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="font: normal 13px  sans-serif, Verdana, Arial, Helvetica, Frutiger, Futura, Trebuchet MS;"
        >
        <!-- Project name -->
        <tr>
            <td height="5%" colspan="7" align="center" valign="top">
                <?php echo '<span style="font-weight:bold;font-size:120%;color:grey;">SMS :: '.$a["projectname"].'</span>'; ?>
            </td>
        </tr>

        <!-- Filter and Refresh List row -->
        <tr>
        <form <?php $x = $_SERVER["PHP_SELF"];
            echo 'action="'.$x.'"'; ?> method="GET">
            <td height="5%" colspan="7" align="center" valign="top">

                <input type="hidden" name="sx" <?php echo 'value="'.$sx.'"'; ?>>
				Filter:
                <select name="blockno" style="width:150px;">
                    <?php
                    if ($blockno && $blockno !== "- Block/All -") echo "<option>$blockno";
                    echo "<option>- Block/All -";
                    $query = "select blockno from blocks where project_id = $project_id and active = 1";
                    if ($result = $mysqli->query($query)) {
                        while ($row = $result->fetch_row()) {
                            echo "<option>$row[0]";
                        }
                        $result->close();
                    }
                    ?>
                </select>
                <select name="dc" style="width:150px;">
                    <?php
                    if ($dc && $dc !== "- Discipline/All -") echo "<option>$dc";
                    echo "<option>- Discipline/All -";
                    $query = "select disciplinecode from discipline order by id";
                    if ($result = $mysqli->query($query)) {
                        while ($row = $result->fetch_row()) {
                            echo "<option>$row[0]";
                        }
                        $result->close();
                    }
                    ?>
                </select>

                <!-- Go and Refresh buttons -->
                <input type="submit" name="go" style="width:100px;" onclick="ResetDivPosition()" value="Go">
                <input type="submit" name="go" style="width:100px;" onclick="SetDivPosition()" value="Refresh">

            </td>
        </form>
    </tr>

    <!-- Drawing List Header row -->
    <tr bgcolor="Grey" style="color:white;font-size:14px;font-weight:bold;" align="center">

        <?php /* Display Commited Date and Target Date columns for TLs & DMs only */
        if ($roleid < 45) {

            // TLs and DMs
            echo '<td width="5%" height="7%">No</td>
                    <td width="13%">Drawing<br>Number</td>
                    <td width="38%">Title</td>
                    <td width="8%">Rev<br>No</td>
                    <td width="12%">Schematic<br>Date</td>
                    <td width="12%">Commited<br>Date</td>
                    <td width="12%">Target<br>Date</td>';

        } else {

            // All other regular users
            echo '<td width="5%" height="7%">No</td>
                    <td width="13%">Drawing<br>Number</td>
                    <td width="50%">Title</td>
                    <td width="8%">Rev<br>No</td>
                    <td width="12%">Schematic<br>Date</td>
                    <td width="12%">Target<br>Date</td>';

        }
        ?>

    </tr>

    <!-- Drawing List row -->
    <tr>
        <td id="cell" colspan="7" height="83%" valign="top" align="center">

            <script type="text/javascript">
                var $x;
                $x = document.getElementById('cell').height;
            </script>

            <div id="usrDIV" style='overflow-y:auto;overflow-x:auto;width:100%;height:440px; border-bottom:1px solid grey;/* */'>
                <?php include("t2DWGid-list2.php"); ?>
            </div>

            <?php echo $company["fullname"]; ?>

        </td>
    </tr>


</table>
</body>

<?php $mysqli->close(); ?>