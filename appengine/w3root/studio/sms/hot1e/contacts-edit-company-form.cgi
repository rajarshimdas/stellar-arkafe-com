<?php
/* contacts-edit-company-form */

$id = $_GET['id'];
$company = $_GET['company'];
$dooradd = $_GET['dooradd'];
$streetadd = $_GET['streetadd'];
$locality = $_GET['locality'];
$city = $_GET['city'];
$statecountry = $_GET['statecountry'];
$pincode = $_GET['pincode'];
$website = $_GET['website'];

?>
<table style="text-align:left;width:100%;background:#E8E9FF;" border="0" cellpadding="2" cellspacing="0">
    <tr>
        <td align="center" width="25%" valign="top">
            <?php echo "Editing Company Address<br>$company"; ?>
        </td>

        <td width="70%">

            <form name="project-contacts" action="execute.cgi" method="POST">

                <input type='hidden' name='a' value='t1xcontacts-edit-company-action'>
                <input type='hidden' name='id' value='<?php echo $id; ?>'>
                <input type="hidden" name="sx" <?php echo 'value="' . $sessionid . '"'; ?>>

                <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0">

                    <tr>
                        <td align='right' width='45%'>Company/Organization Name*:</td>
                        <td align="left" width="55%">
                            <input id="company" name="company" type="text" style="width:100%;" value="<?php echo $company; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td align='right'>Door Address:</td>
                        <td align="left">
                            <input name="door" type="text" style="width:100%;" value="<?php echo $dooradd; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td align='right'>Street Address:</td>
                        <td align="left">
                            <input name="street" type="text" style="width:100%;" value="<?php echo $streetadd; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td align='right'>Locality:</td>
                        <td align="left">
                            <input name="locality" type="text" style="width:100%;" value="<?php echo $locality; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td align='right'>City:</td>
                        <td align="left">
                            <input name="city" type="text" style="width:100%;" value="<?php echo $city; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td align='right'>State, Country:</td>
                        <td align="left">
                            <input name="statecountry" type="text" style="width:100%;" value="<?php echo $statecountry; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td align='right'>Pin Code:</td>
                        <td align="left">
                            <input name="pincode" type="text" style="width:100%;" value="<?php echo $pincode; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td align='right'>Website:</td>
                        <td align="left">
                            <input name="website" type="text" style="width:100%;" value="<?php echo $website; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td align="center">
                            <input name="submit" type="submit" value="Edit" style="width:80px;" tabindex="6">
                            <input name="submit" type="submit" value="Delete" style="width:80px;" tabindex="7">
                            <!-- <input name="submit" type="submit" value="Cancel" style="width:80px;" tabindex="8"> -->
                        </td>
                    </tr>

                </table>
            </form>

        </td>
    </tr>
</table>

<!-- Back to Contacts page button -->
<br>
<form action="project.cgi" method="GET">
    <input type="hidden" name="a" value="t1xcontacts">
    <input type="submit" name="go" value="<< Back to Contacts">
</form>
