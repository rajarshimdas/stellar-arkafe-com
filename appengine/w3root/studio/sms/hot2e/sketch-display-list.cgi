<?php

// From filter form
if (!$bno && !$dc) {
    $bno = $_GET['bno'];    // block number
    $dc = $_GET['dc'];      //discipline code
}	

if ($bno === "-- All / Select Block --") {
    $bno = "all blocks";
    $blockX = "";
} else {
    $blockX = " and blockno = '$bno' ";
}

if ($dc === "-- All / Select Discipline --") {
    $dc = "all Discipline";
    $disciplineX = "";
} else {
    $disciplineX = " and disciplinecode = '$dc' ";
}

include 'foo/arnav/angels.cgi';

if ($bno !== "all blocks") {

    /* Resolve the block name */
    $sql = "select blockno, blockname from blocks where project_id = $projectid and active = 1";

    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_row()) {
            if ($row[0] === $bno) $blockname = "for block $bno - $row[1]";
        }
        $result->close();
    } else {
        $mc = "Error: $mysqli->error";
    }
}

if ($dc !== "all Discipline") {

    /* Resolve the discipline */
    $sql = "select disciplinecode,discipline from discipline";

    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_row()) {
            if ($row[0] === $dc) $discipline = "and discipline $row[1]";
        }
        $result->close();
    } else {
        $mc = "Error: $mysqli->error";
    }
}	

// echo "&nbsp;<br>Sketch Register $blockname $discipline";

?>
<table class="tabulation" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr class="headerRow" align="center" style="height:25px;">
        <td class="headerRowCell1" width="30px">No</td>
        <td class="headerRowCell2" width="80px">SketchNo</td>
        <td class="headerRowCell2" width="100px">To</td>
        <td class="headerRowCell2" width="90px">Date</td>
        <td class="headerRowCell2" width="80px">Block<br>Discipline</td>
        <td class="headerRowCell2" width="200px">Title</td>
        <td class="headerRowCell2" width="295px">Remark</td>
        <!-- <td width="100px">By</td> -->
    </tr>


    <?php

    $sql = "select sketchno,title,remark,contact,company,loginname,dt,blockno,disciplinecode
		from sketches 
		where 
		project_id = $projectid 
            $blockX
            $disciplineX
		order by sketchno DESC";

    if ($result = $mysqli->query($sql)) {
        $i = 1;
        while ($row = $result->fetch_row()) {
            echo "<tr class='dataRow'>
                    <td class='dataRowCell1' align='center'>".$i++."</td>
                    <td class='dataRowCell2'>&nbsp;SK-$row[0]</td>
                    <td class='dataRowCell2'>&nbsp;$row[3]</td>
                    <td class='dataRowCell2' align='center'>".dateformat($row[6])."</td>
                    <td class='dataRowCell2' align='center'>$row[7]-$row[8]</td>
                    <td class='dataRowCell2'>&nbsp;$row[1]</td>
                    <td class='dataRowCell2'>&nbsp;$row[2]</td>
                    <!-- <td align='left'>&nbsp;$row[5]</td> -->
                </tr>";	

        }
        $result->close();
    } else {
        $mc = "Error: $mysqli->error";
    }

    $mysqli->close();
    ?>
</table>
<br>