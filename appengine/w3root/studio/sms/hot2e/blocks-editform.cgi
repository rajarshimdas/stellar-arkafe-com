<?php /* block-editform.cgi */

/* Form for editing individual block details
--------------------------------------------------------------------------------
Inputs
a=t2xblock-edit
blockno=MP
submit=edit
--------------------------------------------------------------------------------
*/

$blockno = $_GET['blockno'];

$sql33 = "select
            blockname,
            phase
        from
            blocks
        where 
            project_id = '$projectid' and
            blockno = '$blockno' and
            active = 1";
//echo "sql33: $sql33";

include 'foo/arnav/angels.cgi';

if ($re33=$mysqli->query($sql33)) {
    $r=$re33->fetch_row();

    $blockname 	= $r[0];
    $phase 	= $r[1];

    $re33->close();
}else {
    printf("<br>Error33: %s\n", $mysqli->error);
}   

//echo "<br>Blockname: $blockname s1: $s1 s2: $s2 s3: $s3 s4: $s4";  
$mysqli->close();
?>
<script type="text/javascript">
    window.onload = function () {
        document.getElementById('blockname').select();
    };
</script>
<table style="text-align: left; width: 100%; background:#E8E9FF;" border="0"  cellpadding="2" cellspacing="0">
    <tbody>
    <form name="editblock" action="execute.cgi" method="POST">
        <input name="a" type="hidden" value="t2xblocks-editaction">
        <input type="hidden" name="sx" <?php echo 'value="'.$sessionid.'"'; ?>>
        <input type="hidden" name="blockno" value="<?php echo $blockno; ?>">
        <input type="hidden" name="phase" value="1">

        <tr>
            <td align="center" style="width:25%;">
                <?php echo "Editing Package: $blockno"; ?>
            </td>
            <td align="right">
                Package Name:                
            </td>
            <td>
                <input type="text" name="blockname" value="<?php echo $blockname; ?>" style="width:90%">
            </td>
        </tr>
        <!-- Transmittals have static drawing numbers. this can create mismatch if someone suddenly changes this.
        <tr>
            <td>&nbsp;</td>            
            <td align="right" style="width:25%;">
                Package ID:                
            </td>
            <td>
                <input type="text" name="newblockno" value="<?php echo $blockno; ?>" style="width:90%">
            </td>
        </tr>
        -->
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="left">
                <input type="submit" name="submit" value="Edit" style="width:100px;"
                       tabindex="1">
                <!-- -->
                <input type="submit" name="submit" value="Delete" style="width:100px;"
                       tabindex="2">

                <input type="submit" name="submit" value="Cancel" style="width:100px;"
                       tabindex="3">
            </td>
        </tr>
    </form>
</tbody>
</table>
