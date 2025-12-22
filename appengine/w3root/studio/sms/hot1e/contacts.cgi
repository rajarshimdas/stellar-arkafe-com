<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 2007					                    |
| Updated On: 10-Feb-2012				                |
+-------------------------------------------------------+
*/
// include('foo/arnav/angels.cgi');
?>

<table style="text-align: left; width: 100%;background:#E8E9FF;" border="0" cellpadding="2" cellspacing="0">
    <tr>
    <form action="project.cgi" method="GET">
        <td align="center" width="25%" valign="top"> Contacts :: New Contact<br>
            <input type="hidden" name="a" value="t1xcontacts-edit-company">
            <input name="submit" type="submit" value="Edit Company Address" style="width:180px;">
        </td>
    </form>    
    <td width="70%">
        <form name="project-contacts" action="execute.cgi" method="POST">
            <input type="hidden" name="a" value="t1xcontacts-add">
            <input type="hidden" name="sx" <?php echo 'value="'.$sessionid.'"'; ?>>
            <!-- RoleId Legacy compatibility -->
            <input type="hidden" name="rid" value="240">
            <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0">
                <tbody>
                    <tr>
                        <td align='right' width='30%'>Company*:</td>
                        <td width="45%">
                            <select name="cid" style="width:100%">
                                <option value="0">-- Select Company --
                                    <?php
                                    $sql = "select id, company from transadd where project_id = $projectid and active = 1";
                                    if ($result = $mysqli->query($sql)) {
                                        while ($row = $result->fetch_row()) {
                                            echo "<option value='$row[0]'>$row[1]</option>";
                                        }
                                        $result->close();
                                    } else echo "Error: $mysqli->error";
                                    ?>
                            </select>
                        </td>
                        <td width="25%">
                            <input name='submit' type="submit" value="Add New Company" >
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>Contact Person's Name*:</td>
                        <td align="left">
                            <input name="cnm" type="text" style="width:100%;">
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='right'> Phone No:</td>
                        <td>
                            <input name="pno" type="text" style="width:100%">
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='right'> Email:</td>
                        <td>
                            <input name="eml" type="text" style="width:100%">
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td align="center">
                            <input name="submit" type="submit" value="Add Contact" style="width:150px;">
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </form>
    </td>
    <td width="5%"></td>
</tr>
</table>

<?php
/* Display the contactlist
include('hot1e/contacts.php');
new contactlist($projectid); 
*/

$query = "select
            t1.id,
            t1.contact,
            t3.roles,
            t2.company,
            t1.phoneno,
            t1.email,
            t1.role_id,
            t1.transadd_id
        from
            transname as t1,
            transadd as t2,
            roles as t3
        where
            t1.project_id = $projectid and
            t1.transadd_id = t2.id and
            t1.role_id = t3.id and
            t1.active = 1
        order by
            t1.contact";

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {
        $RowX[]= array (
                "id" 		=> $row[0],
                "contact" 	=> $row[1],
                "role"		=> $row[2],
                "company" 	=> $row[3],
                "phoneno" 	=> $row[4],
                "email" 	=> $row[5],
                "role_id"       => $row[6],
                "transadd_id"   => $row[7],
        );
    }
    $result->close();

} else "Error: $mysqli->error";

$mysqli->close();


/* Table Header */
?>
<table class="tabulation" border='0' cellspacing='0' style='width:100%;'>
    <tr class='headerRow'>
        <td class="headerRowCell1" style='width:40px; height: 40px;' align="center">No</td>
        <td class="headerRowCell2" style='width:180px;'>&nbsp;Contact Name</td>
        <td class="headerRowCell2" style='width:220px'>&nbsp;Company</td>
        <td class="headerRowCell2" style='width:150px;'>&nbsp;PhoneNo</td>
        <td class="headerRowCell2" colspan='2'>Email</td>        
    </tr>
    <?php
    $no = 1;

    $co = isset($RowX) ? count($RowX) : 0;
    for ($i = 0; $i < $co; $i++) {

        echo "<tr class='tablerows'>
                <form action='project.cgi' method='GET'>
                    <input type='hidden' name='a' value='t1xcontacts-edit'>
                    <input type='hidden' name='transname_id' value='".$RowX[$i]["id"]."'>
                    <input type='hidden' name='name' 	value='".$RowX[$i]["contact"]."'>
                    <input type='hidden' name='role' 	value='".$RowX[$i]["role"]."'>
                    <input type='hidden' name='company'	value='".$RowX[$i]["company"]."'>
                    <input type='hidden' name='phoneno'	value='".$RowX[$i]["phoneno"]."'>
                    <input type='hidden' name='email' 	value='".$RowX[$i]["email"]."'>
                    <input type='hidden' name='role_id'	value='".$RowX[$i]["role_id"]."'>
                    <input type='hidden' name='transadd_id' value='".$RowX[$i]["transadd_id"]."'>

                    <td align='center' style='border-left:1px solid grey;border-right:1px solid grey;border-bottom:1px solid grey'>".$no++."</td>
                    <td style='border-right:1px solid grey;border-bottom:1px solid grey'>&nbsp;".$RowX[$i]["contact"]."</td>
                    <td style='border-right:1px solid grey;border-bottom:1px solid grey'>&nbsp;".$RowX[$i]["company"]."</td>
                    <td style='border-right:1px solid grey;border-bottom:1px solid grey'>&nbsp;".$RowX[$i]["phoneno"]."&nbsp;</td>
                    <td style='border-bottom:1px solid grey'>&nbsp;".$RowX[$i]["email"]."</td>
                    <td width='100px' style='border-right:1px solid grey;border-bottom:1px solid grey; padding: 2px;' align='center'>
                        <input type='image' name='submit' src='/da/icons/edit.png'>&nbsp;
                        <img src='/da/icons/32/print.png' alt='Print' style='cursor: pointer' onclick='javascript:printContact(".$RowX[$i]["id"].")'>
                    </td>
                
		        </form>
            </tr>";		
    }


    ?>
</table>
&nbsp;
<script type='text/javascript' src='/matchbox/mbox/rajarshi/PopUp.js'></script>
<script type="text/javascript">
    function printContact(tid){

        var transnameId     = tid;
        var url             = '<?= BASE_URL ?>studio/sms/PrintContact.cgi?id=' + tid;
        var pageWidth       = 300;
        var pageHeight      = 250;

        PopUp( url, pageWidth, pageHeight );
        
    }
</script>
