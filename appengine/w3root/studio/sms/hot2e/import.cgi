<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 16-Mar-06					                |
| Updated On: 28-Jan-07					                |
+-------------------------------------------------------+
| Excel File Upload Form for reading the drawing list	|
+-------------------------------------------------------+
*/
$maxRowsInCSVFile = 1000;
?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" style="background:#E8E9FF;">
    <form id="form1" name="form1" action="import-csv-upload.cgi" method="POST" ENCTYPE="multipart/form-data">
        <!-- MAX_FILE_SIZE = 100kb -->
        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
        <input name="loginname" type="hidden" value="<?= $loginname; ?>">
        <input name="projectid" type="hidden" value="<?= $projectid; ?>">
        <tr>
            <td colspan="4" align="center">
                Drawing/Document List Import Wizard
                <br>&nbsp;<br>
            </td>
        </tr>
        <tr>
            <td style="text-align:right;width:30%;">Excel File (*.csv):</td>
            <td colspan='2' width="60%">
                <INPUT type="hidden" name='maxRowsInCSVFile' size="50%" value="<?php echo $maxRowsInCSVFile; ?>" />
                <INPUT type="file" name='file' size="50%" />
                <INPUT type="submit" name="submit" value="Next >>" style="width:100px" />
            </td>
            <td>&nbsp;<br>&nbsp;</td>
        </tr>
    </form>
    <tr>
        <td colspan="4" style="border-top: 1px solid RGB(180,180,180)">
            <div class="notes" style="width:600px; margin:auto; text-align: left;">
               <!-- 
                <img src="/da/icons/32/lightbulb.png" alt="Tip">
                &nbsp;Download Templates and modify them to suit your project.<br>&nbsp;
                TEMPLATES -->
                <table class="tabulation" border="0">
                    <tr style="height:30px;">
                        <td width="250px">Blank CSV Template</td>
                        <td width="200px">
                            <a class="button" href="download.php?f=Template-Blank.csv" style="width:100%">
                                Template-Blank.csv
                            </a>
                        </td>
                    </tr>
                    <!--
                    <tr style="height:30px;">
                        <td>Site Layout Template</td>
                        <td>
                            <a class="button" href="download.php?f=Template-Site.csv" style="width:100%">
                                Template-Site.csv
                            </a>
                        </td>
                    </tr>
                    <tr style="height:30px;">
                        <td>Building Template</td>
                        <td>
                            <a class="button" href="download.php?f=Template-Building.csv" style="width:100%">
                                Template-Building.csv
                            </a>
                        </td>
                    </tr>
                    -->
                </table>

                <br><img src="/da/icons/32/info.png" alt="Info"><br>
                <br>Do not change the template format to XLS, it has to be CSV only.
                <br>Concert will read first <?php echo $maxRowsInCSVFile; ?> rows only.<br>&nbsp;
            </div>
        </td>
    </tr>

</table>
<?php
/* Display on sucessful import */
if (isset($_GET['co'])) {
    if ($_GET['co']) echo "Imported " . $_GET['co'] . " drawings into the drawing list.";
};
?>