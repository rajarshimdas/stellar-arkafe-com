<?php
class block
{

    /* Properties */
    var $ProjID;
    var $BlockNo;
    var $BlockName;
    var $s1orgdt;
    var $s1newdt;
    var $s1active;
    var $s2orgdt;
    var $s2newdt;
    var $s2active;
    var $s3orgdt;
    var $s3newdt;
    var $s3active;
    var $s4orgdt;
    var $s4newdt;
    var $s4active;

    /* Methods */
    function __construct($projid, $blockno, $blockname)
    {
        /* Constructor function */
        $this->ProjID         = $projid;
        $this->BlockNo         = $blockno;
        $this->BlockName     = $blockname;
        /* close function block */
    }

    function BlockList($edit = 0)
    {
?>
        <table class="tabulation" border="0" cellpadding="2" cellspacing="0" width="100%">
            <tr class="headerRow" align="center">
                <td class="headerRowCell1" width="50px">No</td>
                <td class="headerRowCell2" width="120px">Package Id</td>
                <td class="headerRowCell2" align='left'>&nbsp;Package Name</td>
                <td class="headerRowCell2" width="120px">Items</td>
                <td class="headerRowCell2" width="120px" style='border-right: 0px'>GFCs<br>Released</td>
                <?php if ($edit > 0) echo "<td class='headerRowCell2' width='120px' align='center'>&nbsp;</td>"; ?>
            </tr>
            <?php

            /* List Blocks */
            $mysqli = cn1();
            $counter = 1;

            $sql33 = "select
                            blockno,
                            blockname,
                            phase
                        from
                            blocks
                        where
                            project_id = '$this->ProjID' and
                            active = 1";

            if ($re33 = $mysqli->query($sql33)) {

                while ($r = $re33->fetch_row()) {

                    $blockno = $r[0];
                    $statusX = getBlockStatus($this->ProjID, $blockno, $mysqli);

                    if ($statusX[1] > 0) {
                        $stat = $statusX[1] . ' (' . $statusX[2] . '%)';
                    } else {
                        $stat = '&nbsp;';
                    }

                    echo "<form name='edit$counter' action='project.cgi' method='GET'>
			            <input type='hidden' name='a' value='t2xblocks-editform'>
			            <input type='hidden' name='blockno' value='$r[0]'
                            <tr class='dataRow' align='center'>";
                    echo "<td class='dataRowCell1' align='center'>$counter</td>";
                    echo "<td class='dataRowCell2a' align='center'><a href='project.cgi?a=t2xblocks-summary&bn=$r[0]'>$r[0]</a></td>";
                    echo "<td class='dataRowCell2' align='left'>&nbsp;$r[1]</td>";
                    // echo "<td align='center'>$r[2]</td>";
                    echo "<td class='dataRowCell2' align='center'>$statusX[0]</td>";
                    echo "<td class='dataRowCell2' align='center' style='border-right: 0px'>$stat</td>";
                    if ($edit > 0) echo "<td class='dataRowCell2' style='text-align:right'>
                            <input type='image' src='/da/icons/edit.png'>
                        </td>";
                    echo "</tr></form>";
                    $counter++;
                }
                $re33->close();
            } else {
                printf("<br>Error33: %s\n", $mysqli->error);
            }

            $mysqli->close();
            ?>
        </table>
<?php
        /* Close function BlockList */
    }

    /* close class */
}



function getBlockStatus($projId, $blockno, $mysqli)
{

    $totalItems     = 0;
    $totalGFCs      = 0;

    $query = "select r0issuedflag from dwglist where project_id = $projId and dwgidentity = '$blockno' and active = 1";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            // Total Items
            $totalItems++;

            // GFCs issued
            $r0issuedflag = $row[0] + 0;

            if ($r0issuedflag > 0) {
                $totalGFCs++;
            }
        }

        $result->close();
    }

    // Calculate %
    if ($totalItems > 0) $px = round((($totalGFCs * 100) / $totalItems), 0);

    $returnX = array($totalItems, $totalGFCs, $px);

    return $returnX;
}
