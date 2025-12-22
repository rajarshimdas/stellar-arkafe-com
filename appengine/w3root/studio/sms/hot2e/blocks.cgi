<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 						                    |
| Updated On: 13-Feb-2012				                |
+-------------------------------------------------------+
| Drawing List :: Blocks				                |
+-------------------------------------------------------+
*/ 
?>
<script type="text/javascript">
    $().ready(function(){
        $('#blockno').focus();
    });
</script>
<table style="text-align: left; width: 100%; background:#E8E9FF;" border="0"  cellpadding="0" cellspacing="2">
    <tbody>
    <form name="editblock" action="execute.cgi" method="POST">
        <input name="a" type="hidden" value="t2xblocks-new">
        <input type="hidden" name="sx" <?php echo 'value="'.$sessionid.'"'; ?>>
        <tr>
            <td align="center" style="width:20%;">
                Add Packages
            </td>
            <td align="left" style="width:80%;">
                <table style="text-align: left; width: 100%;" border="0"  cellpadding="0" cellspacing="2">
                    <tbody>
                        <tr>
                            <td align="right" style="width:12%">
                                Package Id:
                            </td>

                            <td align="left" style="width:10%" >                                
                                <input id="blockno" type="text" name="blockno" style="width:100%" maxlength="25">
                            </td>

                            <td align="right" style="width:15%">
                                Package Name:
                            </td>

                            <td align="left" style="width:37%">
                                <input type="text" name="blockname" style="width:100%">
                                <input type="hidden" name="phase" value="1">
                            </td>                                       

                            <td align="center" style="width:10%">
                                <input type="submit" name="submit" value="Create">
                            </td>

                        </tr>
                    </tbody>
                </table>

            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
    </form>
    <tr>
        <td colspan="2" align="center" style="background:white;">
            <!-- Existing list of MP & Blocks -->
            
            <?php

            include 'foo/t2blocks.php';
            $bx = new block ($projectid, 'mp', 'Masterplan');
            $bx->BlockList(1);

            ?>
        </td>
    </tr>
</tbody>
</table>
&nbsp;