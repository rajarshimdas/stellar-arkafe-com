<div align="center" style="font:normal 10pt Verdana, Arial, sans-serif, Helvetica, Frutiger, Futura, Trebuchet MS;">

    <?php
    //echo '1: bk: '.$bk.' ProjId: '.$ProjID;

    // Get Selected Block name
    $sql = "select blockname from blocks where blockno = '$bk' and project_id = $ProjID and active = 1";
    //echo 'q: '.$sql;

    if ($result = $mysqli->query($sql)) {

        $row = $result->fetch_row();
        $blockno 	= $bk;
        $blockname 	= $row[0];
        $result->close();

    } else {
        echo "Error: $mysqli->error";
        die;
    }


    // Page Header
    if ($hideHeader !== 'T') {
        echo "<span style='font-weight:bold;font-size:18px;'>$ProjNM<br>$bk - $blockname</span><br>Date: ".date('d-M-Y');
    } else {
        echo $bk.' - '.$blockname;
    }
    if ($fx === "b" && $from && $to) echo "<br>From: $from To: $to";


    // Get list of all disciplines
    // $sql = "select disciplinecode, discipline from discipline order by id"; //echo "<br>sql: $sql;";
    
    $sql = "SELECT 
                disciplinecode, 
                discipline 
            FROM 
                discipline 
            where active > 0
            ORDER BY displayorder";

    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_row()) {

            // Blocks array
            $dcX[] = array ("dcode" => $row[0], "dname" => $row[1]);

        }
        $result->close();
    } else {
        echo "Error: $mysqli->error";
    }


    // Generate Tables for specified block(s)
    if ($dc !== "-- All/Select --") {

        // Single Discipline
        for ($i = 0; $i < sizeof($dcX); $i++) {

            if ($dcX[$i]["dcode"] === $dc) {

                // Found match for $dc
                $dcode = $dcX[$i]["dcode"];
                $dname = $dcX[$i]["dname"];

                // Display the drawing List for this Block
                xtable($ProjID,$blockno,'-',$dcode,$dname,'block',$fx,$from2,$to2,$c1,$stage,$gfc,$c2,$c3,$c4,$mysqli);

            }

        }


    } else {

        // All Discipline
        for ($i = 0; $i < sizeof($dcX); $i++) {

            $dcode = $dcX[$i]["dcode"];
            $dname = $dcX[$i]["dname"];

            // Display the drawing List for this discipline
            xtable($ProjID,$blockno,'-',$dcode,$dname,'block',$fx,$from2,$to2,$c1,$stage,$gfc,$c2,$c3,"off",$mysqli);

        }

    }

    ?>
    <br>
</div>
