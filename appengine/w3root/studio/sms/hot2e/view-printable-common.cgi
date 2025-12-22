<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 03-Mar-2008				|
| Updated On: 19-Mar-2012 				|
+-------------------------------------------------------+
| Drawing List > View Form				|
+-------------------------------------------------------+
| Common form:						|
|	1. Printable-Disciplinecode			|
|	2. Printable-Blockwise				|
+-------------------------------------------------------+
*/ 
?>

<tr>
    <td>
        <table align="left" style="border:solid cadetblue;border-width:1px;width:400px;">
            <tr>
                <td colspan="2">Select data to be displayed:</td>
            </tr>
            <tr>
                <td width="70%" align="right">Complete List:<br>GFC Releases only:<br>Pending Drawings only:</td>
                <td width="30%">
                    <input type="radio" name="fx" value="a" checked="checked"><br>
                    <input type="radio" name="fx" value="b"><br>
                    <input type="radio" name="fx" value="c">
                </td>
            </tr>
        </table>
    </td>
</tr>
<!--
<tr>
    <td>
        <table align="left" style="border:solid cadetblue;border-width:1px;width:400px;">
            <tr>
                <td colspan="2"> Date range (GFC Release only):</td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <link rel="stylesheet" type="text/css" href="/matchbox/mbox/calendar/styles.css" />
                        <script type="text/javascript" src="/matchbox/mbox/calendar/classes.js"></script>
                        <script language="JavaScript">
                            var a1; var a2;
                            window.onload = function () {
                                a1 = new Epoch('epoch_popup','popup',document.getElementById('pop1'));
                                a2 = new Epoch('epoch_popup','popup',document.getElementById('pop2'));
                            };
                        </script>
                        <tr>
                            <td width="50%">From: <input id="pop1" type="text" name="dt1"></td>
                            <td width="50%">To: <input id="pop2" type="text" name="dt2"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
-->
<tr>
    <td>
        <table align="left" style="border:solid cadetblue;border-width:1px;width:400px;" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="2">Show selected columns in Drawing List:</td>
            </tr>
            <tr>
                <td style="border-bottom:solid 1px cadetblue;">
                    <input type="checkbox" name="c1" checked>
                </td>
                <td style="border-bottom:solid 1px cadetblue;">Current Revision No</td>
            </tr>
            <!--
            <tr>
                <td style="background:white;"><input type="radio" name="stage" value="a"></td>
                <td style="background:white;">Schematic Stage Number</td>
            </tr>
            <tr>
                <td style="border-bottom:solid 1px cadetblue;background:white;"><input type="radio" name="stage" value="b"></td>
                <td style="border-bottom:solid 1px cadetblue;background:white;">Schematic Target & Revised/Completed Date</td>
            </tr>
            -->
            <tr>
                <td style="background:white;"><input type="radio" name="gfc" value="0"></td>
                <td style="background:white;">R0 None</td>
            </tr>
            <tr>
                <td style="background:white;"><input type="radio" name="gfc" value="a"></td>
                <td style="background:white;">R0 Released Date</td>
            </tr>
            <tr>
                <td style="border-bottom:solid 1px cadetblue;background:white;">
                    <input type="radio" name="gfc" value="b" checked="checked">
                </td>
                <td style="border-bottom:solid 1px cadetblue;background:white;">
                    R0 Target & Revised/Completed Date
                </td>
            </tr>

            <?php
            /* Committed Date
            if ($roleid < 45) {
                echo '<tr>
                        <td><input type="checkbox" name="c2"></td>
                        <td>Committed Date</td>
                    </tr>';
            }
            */
            ?>

            <tr>
                <td><input type="checkbox" name="c3" checked></td>
                <td>Last Issued Revision and Date</td>
            </tr>
            <!-- Removed temporarily
            <tr>
                <td><input type="checkbox" name="c4"></td>
                <td>Remark</td>
            </tr>
            -->
            <input name="c4" type="hidden" value="-">

        </table>
    </td>
</tr>


<tr>
    <td align="center">
        <input type="submit" name="submit" value="Show Drawing List">
    </td>
</tr>