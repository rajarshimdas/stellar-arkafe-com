<?php /* dates-form.cgi */ ?>
<table style="text-align: left; width: 100%;" border="0"  cellpadding="2" cellspacing="0">
    <tr>

    <form action="rajarshi.cgi" method="get">
        <td align="center" valign="undefined" width="30%" style="background:#E8E9FF;">
        		Commited and Target Dates			  
        </td>

        <td align="center" valign="undefined" width="70%" style="background:#E8E9FF;">
            <input type="hidden" name="a" value="t2xdates">
          		Disciplinecode*:
            <select name="dx" style="width:150px;">
                <option>-- Select --
                <option>All Disciplines
                    <?php
                    $query = "select disciplinecode from discipline order by id";
                    if ($result = $mysqli->query($query)) {

                        while ($row = $result->fetch_row()) {
                            echo "<option>$row[0]";
                        }

                        $result->close();
                    }

                    ?>
            </select>
            <input name="submit" type="submit" value="go">
        </td>
    </form>

</tr>
</table>