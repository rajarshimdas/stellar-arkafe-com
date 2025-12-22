<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 29-Feb-08					|
| Updated On: 						|
+-------------------------------------------------------+
| Print Drawing List - Disciplinecode			|
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

include('hot2e/view-printable-vars.cgi');	// Get variables - common
include('hot2e/view-printable-X.cgi');		// Loop funtion to display the tables - common		
// include('foo/arnav/angels.cgi');		// Get MySQL select privilege connection
include('foo/t2DWGid.php');			// DWGid() class for extracting the drawing details

//echo "Project id: $ProjID<br>Project Name: $ProjNM<br>bk: $bk<br>dc:$dc<br>ck1: $c1 <br>ck2: $c2 <br>ck3: $c3<br>Fx: $fx<br>From: $from To: $to<br>Stage: $stage<br>GFC: $gfc<br>From2: $from2 To2: $to2";

?><!DOCTYPE html>
<head>
    <link href='/matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div align="center" style="font:normal 10pt Verdana, Arial, sans-serif, Helvetica, Frutiger, Futura, Trebuchet MS;">

        <?php

        // Get Selected Discipline name
        $sql = "select discipline from discipline where disciplinecode = '$dc'";
        if ($result = $mysqli->query($sql)) {
            $row = $result->fetch_row();
            $disciplinename = $row[0];
            $result->close();
        } else {
            echo "Error: $mysqli->error";
            die;
        }


        // Page Header
        echo "<span style='font-weight:bold;font-size:18px;'>$ProjNM<br>$disciplinename</span><br>Date: ".date('d-M-Y');
        if ($fx === "b" && $from && $to) echo "<br>From: $from To: $to";


        // Get block names
        $sql = "select blockno, blockname from blocks where project_id = $ProjID and active = 1 order by blockno"; //echo "<br>sql: $sql;";
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_row()) {

                // Blocks array
                $blockX[] = array ("blockno" => $row[0], "blockname" => $row[1]);
                //echo "<br>Blockno: $row[0] Blockname: $row[1]";

            }
            $result->close();
        }else echo "Error: $mysqli->error";


        // Generate Tables for specified block(s)
        if ($bk !== "-- All/Select --") {

            // Single Block
            for ($i = 0; $i < count($blockX); $i++) {
                if ($blockX[$i]["blockno"] === $bk) {

                    $blockno 	= $blockX[$i]["blockno"];
                    $blockname 	= $blockX[$i]["blockname"];
                    //xtable($ProjID,$blockno,$blockname,$dc,$fx,$from2,$to2,$c1,$stage,$gfc,$c2,$c3,$c4,$mysqli);
                    xtable($ProjID,$blockno,$blockname,$dc,'-','discipline',$fx,$from2,$to2,$c1,$stage,$gfc,$c2,$c3,$c4,$mysqli);

                }
            }

        } else {

            // All Blocks

            // Show masterplan first - if exists.
            for ($i = 0; $i < count($blockX); $i++) {
                if ($blockX[$i]["blockno"] === "MP") {

                    $blockno 	= $blockX[$i]["blockno"];
                    $blockname 	= $blockX[$i]["blockname"];
                    //xtable($ProjID,$blockno,$blockname,$dc,$fx,$from2,$to2,$c1,$stage,$gfc,$c2,$c3,$c4,$mysqli);
                    xtable($ProjID,$blockno,$blockname,$dc,'-','discipline',$fx,$from2,$to2,$c1,$stage,$gfc,$c2,$c3,$c4,$mysqli);

                }
            }

            // All other blocks
            $co = sizeof($blockX);
            for ($i = 0; $i < $co; $i++) {
                $blockno = trim($blockX[$i]["blockno"]);
                if ($blockno !== "MP") {

                    $blockno 	= $blockX[$i]["blockno"];
                    $blockname 	= $blockX[$i]["blockname"];
                    //xtable($ProjID,$blockno,$blockname,$dc,$fx,$from2,$to2,$c1,$stage,$gfc,$c2,$c3,$c4,$mysqli);
                    xtable($ProjID,$blockno,$blockname,$dc,'-','discipline',$fx,$from2,$to2,$c1,$stage,$gfc,$c2,$c3,$c4,$mysqli);
                }
            }

        }

        ?>
        <br>
    </div>
</body>

<?php $mysqli->close(); ?>
