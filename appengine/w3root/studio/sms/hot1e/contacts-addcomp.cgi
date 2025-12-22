<?php  /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 2007					                    |
| Updated On:   					                    |
+-------------------------------------------------------+
*/
?>
<script type="text/javascript">
    window.onload = function () {
        document.getElementById('cn').focus();
    };
</script>
<table style="text-align: left; width: 100%;background:#E8E9FF;" border="0" cellpadding="2" cellspacing="0">
    <tr>
        <td align="center" width="25%" valign="top">           

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" height="50px" valign="top">
                        &nbsp;Contacts :: Add a New Address
                    </td>
                </tr>
                <tr class="notes">
                    <td valign="top" align="center" width="60px">
                        <img src="/da/icons/32/lightbulb.png" alt="!">
                    </td>
                    <td valign="top" align="justify">
                        In case you do not have company address available, simply create the company for now.
                        <br>&nbsp;<br>
                        You can fill the address later by clicking on the Edit Company Button.
                    </td>
                </tr>
            </table>

        </td>
        <td width="70%"> 
            <form name="project-contacts" action="execute.cgi" method="POST">
                <input type="hidden" name="a" value="t1xcontacts-addcomp-action">
                <input type="hidden" name="sx" <?php echo 'value="'.$sessionid.'"'; ?>>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0">                   
                    <tr>
                        <td align='right' width='45%'>Company/Organization Name*:</td>
                        <td align="left" width="55%">
                            <input id="cn" name="company" type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>Door Address:</td>
                        <td align="left">
                            <input name="door" type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>Street Address:</td>
                        <td align="left">
                            <input name="street" type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>Locality:</td>
                        <td align="left">
                            <input name="locality" type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>City:</td>
                        <td align="left">
                            <input name="city" type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>State, Country:</td>
                        <td align="left">
                            <input name="state" type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>Pin Code:</td>
                        <td align="left">
                            <input name="pincode" type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>Website:</td>
                        <td align="left">
                            <input name="website" type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center">
                            <input name="submit" type="submit" value="Add" style="width:120px;">
                            <!-- <input name="submit" type="submit" value="Cancel" style="width:120px;"> -->
                        </td>
                    </tr>
                </table>
            </form> 
        </td>
        <td width="5%"></td>
    </tr>
</table>

<!-- Back to Contacts page button -->
<br>
<form action="project.cgi" method="GET">
    <input type="hidden" name="a" value="t1xcontacts">
    <input type="submit" name="go" value="<< Back to Contacts">
</form>

<?php /* Display result of last query */
$query = $_GET['q'];
$company = $_GET['nm'];
if ($query === "ok") {
    echo "Company Added: $company";

}
?>

