<table width="1000px" cellspacing="4px" cellpadding="4px">
    <form action="feeCalculatorPreview.cgi" method="POST" ENCTYPE="multipart/form-data">
        <tr>
            <td width="60%" style="background: #c6e8ff; border: 1px solid black">
                <table width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="40%" align="right">Fee Calculator Data Upload: </td>
                        <td width="60%">
                            <?php include 'foo/comboProjects.php'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">Data (CSV Format):</td>
                        <td>
                            <!-- MAX_FILE_SIZE = 100kb -->
                            <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                            <INPUT type="file" name='fx' />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" style="height:35px">
                            <input type="submit" name="go" value="Upload" style="width:100px">
                            <a href="download.php?f=FeeCalculator-Ver1.1.xls" style="color: black; padding: 2px; text-decoration: none; border: 1px solid  RGB(150,150,150); background: white">
                                Download Fee Calculator Template
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </form>
</table>