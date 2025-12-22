<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 29-Feb-2008				|
| Updated On: 						|
+-------------------------------------------------------+
| Drawing List > View > Printable-Blockwise Form	|
+-------------------------------------------------------+
*/
?>

Select Drawing List display options<br>

<table>
    <form action="PrintDWGList-DW.cgi" method="POST" target="windowName" onsubmit="window.open('',this.target,'dialog,modal,scrollbars=yes,resizable=no,width=900,height=700,left=300,top=100');">

        <input type="hidden" name="ProjID" value="<?php echo $projectid; ?>">
        <input type="hidden" name="ProjNM" value="<?php echo $projectname; ?>">

        <tr>
            <td>
                <table align="left" style="border:solid cadetblue;border-width:1px;width:400px;">


                    <tr>
                        <td width="80px">Disciplinecode*: </td>
                        <td width="170px" align="undefined" valign="bottom">
                            <select name="dc" style="width:100%">
                                <option>-- Select --
                                    <?php

                                    /* Discipline code dropdown combo box */
                                    $sql = "SELECT 
                                                disciplinecode, 
                                                discipline 
                                            FROM 
                                                discipline 
                                            where active > 0
                                            ORDER BY displayorder";

                                    if ($result = $mysqli->query($sql)) {
                                        while ($row = $result->fetch_row()) {
                                            //echo "<option>$row[0]";
                                            echo '<option value="' . $row[0] . '">' . $row[0] . ' - ' . $row[1] . '</option>';
                                        };
                                        $result->close();
                                    } else {
                                        printf("Error: %s\n", $mysqli->error);
                                    }

                                    ?>
                            </select>
                        </td>
                    </tr>


                    <tr>

                        <td width="80px">Package: </td>
                        <td width="170px" align="undefined" valign="bottom">
                            <select name="bk" style="width:100%">
                                <option> -- All/Select --
                                <option value="MP">MP - Masterplan</option>

                                <?php

                                /* Discipline code dropdown combo box */
                                $sql = "SELECT blockno, blockname FROM blocks where project_id='$projectid' and active=1 ORDER BY blockno";

                                if ($result = $mysqli->query($sql)) {
                                    while ($row = $result->fetch_row()) {
                                        if ($row[0] !== 'MP') {
                                            echo '<option value="' . $row[0] . '">' . $row[0] . ' - ' . $row[1] . '</option>';
                                        }
                                    };
                                    $result->close();
                                } else {
                                    printf("Error: %s\n", $mysqli->error);
                                }


                                ?>
                            </select>
                        </td>
                    </tr>


                </table>
            </td>
        </tr>

        <?php
        /* Common to both Printable-Blockwise and Printable-Disciplinecode */
        include('hot2e/view-printable-common.cgi');
        ?>

    </form>
</table>

<?php $mysqli->close(); ?>