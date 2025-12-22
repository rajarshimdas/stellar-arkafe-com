<?php /*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 05-Jul-2012				|
| Updated On:                                           |
+-------------------------------------------------------+
*/


/*
+-------------------------------------------------------+
| Fn :: displayManhoursWizard                           |
+-------------------------------------------------------+
*/
function displayManhoursWizard($sessionid) {

    echo '<style type="text/css">
            .tbl { background-color:white; padding: 2px; font-size: 90%; }
            .tbl td { border-bottom: 1px solid #aaaaaa; height: 55px; vertical-align: center; padding-left: 5px}
        </style>
        <table class="tbl" style="width: 100%; background: #E8E9FF;" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td width="80px" align="right">
                    Step 1:
                </td>
                <td>
                    Download the Fee Estimate Template in MS Excel Format&nbsp;<a class="button" href="download.php?f=FeeCalculator-Ver1.1.xls">Download</a>
                </td>
            </tr>
            <tr>
                <td align="right">
                    Step 2:
                </td>
                <td>
                    Fill-up your project\'s Manhour/Fee Estimate.
                </td>
            </tr>
            <tr>
                <td align="right">
                    Step 3:
                </td>
                <td>
                    Save the Fee Estimate Template in CSV (Comma delimited) (*.csv). Click File > Save As and select this format in the `Save as type` drop down.
                </td>
            </tr>
            <form action="execute.cgi" method="POST" ENCTYPE="multipart/form-data">
                <tr>
                    <td align="right">
                        Step 4:
                    </td>
                    <td>
                        <input type="hidden" name="a" value="t1xfeeEstimate-01">
                        <input type="hidden" name="sx" value="'.$sessionid.'">
                        Upload Manhour/Fee Estimate Template File (saved in CSV Format):
                        <!-- MAX_FILE_SIZE = 100kb -->
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                        <input type="file" name="fx" />
                        <input type="submit" name="go" value="Save" style="width:80px">
                    </td>
                </tr>
            </form>
        </table>
        <br>';

}


/*
+-------------------------------------------------------+
| Fn :: signOffStatusFlag                               |
+-------------------------------------------------------+
*/
function signOffStatusFlag($pid, $mysqli) {

    $flag = 0;

    $query = "select projectstatus_id from projects where id = ".$pid;
    // echo 'Q: '.$query;

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $flag = $row[0] + 0;

        $result->close();
    }

    return $flag;
}


?>