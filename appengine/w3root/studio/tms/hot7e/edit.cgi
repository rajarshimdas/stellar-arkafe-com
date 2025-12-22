<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 27-May-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
| Timesheet :: Edit - step 1                         	|
+-------------------------------------------------------+
*/ ?>

<table width="100%" border="0" style="font-size:95%;background:#E8E9FF;">

    <form action="rajarshi.cgi" method="GET">
        <input name="a" type="hidden" value="t7xedit-01">

        <tr valign="top">
            <td align="right">Timesheet Date*:</td>
            <td>
        <link rel="stylesheet" type="text/css" href="foo/calendar/styles.css" />
        <script type="text/javascript" src="foo/calendar/classes2.js"></script>
        <script type="text/javascript">
            var dp_cal;
            window.onload = function () {
                dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('popup_container'));
                document.getElementById('identity').focus();
            };
        </script>
        <input id="popup_container" name="dt" type="text" value="-- Select Date --">
        <input type="submit" name="go" value="Go">
        </td>
        </tr>




    </form>

</table>
