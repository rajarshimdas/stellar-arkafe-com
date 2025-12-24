<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 15-Jan-07					                |
| Updated On: 16-Feb-12 				                |
+-------------------------------------------------------+
| Transmittal Blank Template				            |
+-------------------------------------------------------+
| Input Variables 					                    |
+-------------------------------------------------------+
| $tmid		Transmittal ID                              |
| $projectid 	Project ID				                |
| $transno	Transmittal number                          |
| $contact						                        |
| $messers						                        |
| $address						                        |
| $sentmode						                        |
| $purpose						                        |
| $date		Transmittal created on (pre-formatted)	    |
| $remark						                        |
| $loginname						                    |
| $projectname						                    |
| $jobcode						                        |
| $itemsX[]	Content List Array                          |
| $company[]	Company Info array from config.php	    |
| $imagepath	Path to the images folder		        |
+-------------------------------------------------------+
*/
?>
<!-- CSS -->
<link type='text/css' rel='stylesheet' href='/matchbox/themes/cool/style.css'>
<!-- Transmittal -->
<div style="font-family: Helvetica,Arial,sans-serif;" align="center">
    <table border="0" width="725px" style="border:solid cadetblue;border-width:1px;">
        <tr><!-- Logo and Header row -->
            <td>
                <table border="0" width="100%">
                    <tr>
                        <td width="15%" align="center" valign="top">
                            <!-- Logo height: 60px -->
                            <img src="/da/logo-transmittal.png" style="height:60px;" alt="Logo">
                        </td>
                        <td width="45%" align="left" valign="top">

                            <span style="font-size:14px;font-weight:bold;">
                                <?php echo $corporatename; ?>
                            </span><br>
                            <span style="font-size:12px;font-weight:normal;">
                                <?php echo $corporateaddress; ?>
                            </span>                       
                            
                        </td>
                        <td width="40%" align="center" valign="top">
                            <span style="font-size:30px;border:solid cadetblue;border-width:0px;">&nbsp;TRANSMITTAL&nbsp;</span>
                            <?php
                            if ($transno)
                                echo "<br><span style='font-size:100%;'>No: $transno</span>"; else
                                echo "<br><span style='font-size:100%;'>Auto Number</span>";
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <!-- Addressed To -->
            <td align="center">
                <table width="95%" border="0" style="font-size:100%;">
                    <tr><td colspan="4" height="8px">&nbsp;</td></tr>
                    <tr>
                        <td width="8%" align="right" style="font-size:85%;">To:</td>
                        <td width="42%" style="border-bottom: solid cadetblue;border-width:1px;">&nbsp;
                            <?php echo "<span style='font-size:100%;'>$contact</span>";	?>
                        </td>
                        <td width="8%" align="right" style="font-size:85%;">Date:</td>
                        <td width="42%" style="border-bottom: solid cadetblue;border-width:1px;">&nbsp;
                            <?php echo $date; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="8%" align="right" style="font-size:85%;">M/s.:</td>
                        <td width="42%" style="border-bottom: solid cadetblue;border-width:1px;">&nbsp;
                            <?php echo $messers; ?>
                        </td>
                        <td width="8%" align="right" style="font-size:85%;">Job No:</td>
                        <td width="42%" style="border-bottom: solid cadetblue;border-width:1px;">&nbsp;
                            <?php global $jobcode;
                            echo $jobcode; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="8%" align="right">&nbsp;</td>
                        <td width="42%">&nbsp;
                            <!--<?php echo $address; ?>-->
                        </td>
                        <td width="8%" align="right" style="font-size:85%;">Project:</td>
                        <td width="42%" style="border-bottom: solid cadetblue;border-width:1px;">&nbsp;
                            <?php global $projectname;
                            echo $projectname; ?>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
        <tr><!-- Display the Purpose checkboxes -->
            <?php
            $ck = explode("-",$purpose);
            /*--------------------------------------+
                |Syntax: A-8-8-8-8-8-8-8-8-8-8-8-8	|
                +---------------------------------------+
                |	Not ticked 	= 0		|
                |	Ticked		= 1		|
                +---------------------------------------+
                | ck1 = For Approval/Comments		|
                +---------------------------------------+
                | ck2 = Per Your Request <Not in use>	|
                +---------------------------------------+
                | ck3 = For Information			|
                +---------------------------------------+
                | ck4 = For Coordination<not in use>	|
                +---------------------------------------+
                | ck5 = Advance copy <not in use>	|
                +---------------------------------------+
                | ck6 = Good For Construction		|
                +---------------------------------------+
                | ck7 = Originals			|
                +---------------------------------------+
                | ck8 = Prints				|
                +---------------------------------------+
                | ck9 = Soft Copy			|
                +---------------------------------------+
                | ck10= Tenders				|
                +---------------------------------------+
                | ck11= Sanction Drawings		|
                +---------------------------------------+
                | ck12= Shop Drawings			|
                +--------------------------------------*/
            ?>
            <td align="center">
                <table width="95%" style="font-size:85%" border="0">
                    <tr>
                        <td colspan="5" align="left">
                            <?php
                            if ($sentmode === "Others")
                                echo "We are sending you the following:"; else
                                echo "We are sending you by $sentmode, the following:";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="5%" align="right">
                            <?php if ($ck[7] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td width="28%">Originals</td>
                        <td width="5%" align="right">
                            <?php if ($ck[1] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td width="29%">For Comments/Approvals</td>
                        <td width="5%" align="right">
                            <?php if ($ck[10] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td width="28%">Tenders</td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php if ($ck[8] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td >Prints</td>
                        <td align="right">
                            <?php if ($ck[3] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td>For Information</td>
                        <td align="right">
                            <?php if ($ck[11] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td>Sanction Drawings</td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php if ($ck[9] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td>Soft Copy</td>
                        <td align="right">
                            <?php if ($ck[6] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td>Good For Construction</td>
                        <td align="right">
                            <?php if ($ck[12] == 1)
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox1.bmp'>"; else
                                echo "<img style='width: 18px; height: 18px;' src='".$imagepath."/chkbox0.bmp'>";
                            ?>
                        </td>
                        <td>Shop Drawings</td>
                    </tr>

                </table><br>
            </td>
        </tr>
        <tr><!-- Display the list of contents -->
            <td align="center">
                <table class="tmTable" border="0" width="98%" cellspacing="0" cellpadding="0">
                    <tr style="border-top: 1px solid RGB(150,150,150);" align="center" height='28px'>
                        <td class="tmRowC1" width="5%">No</td>
                        <td class="tmRowC2" width="20%">Item</td>
                        <td class="tmRowC2" width="5%">Nos</td>
                        <td class="tmRowC2">Description</td>
                    </tr>

                    <?php
                    // echo 'startingSrNo: '.$startingSrNo;

                    $x = 0;
                    $i = isset($itemsX)?count($itemsX):0;

                    for ($x; $x < $i; $x++) {

                        $no 	= $x + $startingSrNo;
                        $srno 	= $itemsX[$x]["srno"];
                        $item 	= $itemsX[$x]["item"];
                        $nosX 	= $itemsX[$x]["nosX"];
                        $desc 	= $itemsX[$x]["desc"];

                        // The next two rows are needed for removing from tmlist
                        echo "<input type='hidden' name='srno$i' value='$srno'>
			  <input type='hidden' name='item$i' value='$item'>";
                        // Display the transmittal row
                        echo "<tr>
				<td class='tmRowC1' align='center'>$no</td>
				<td class='tmRowC2'>$item</td>
				<td class='tmRowC2' align='center'>$nosX</td>
				<td class='tmRowC2'>$desc</td>
			</tr>";
                    }

                    $i = $x;
                    for ($i; $i < $tmTotalItems; $i++) {
                        echo "<tr>
				<td class='tmRowC1'>&nbsp;</td>
				<td class='tmRowC2'>&nbsp;</td>
				<td class='tmRowC2'>&nbsp;</td>
				<td class='tmRowC2'>&nbsp;</td>
			</tr>";
                    }?>

                </table><br>
            </td>
        </tr>
        <tr><!-- Remarks -->
            <td style="font-size:85%;">
					Remarks:<br>

                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:100%;border-bottom:solid cadetblue;border-bottom-width:1px;">&nbsp;
                            <?php echo $remark; ?>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr><!-- Request for acknowlegement -->
            <td style="font-size:85%;height:40px;">
                Please acknowledge receipt by signing and returning the enclosed copy:
            </td>
        </tr>
        <tr><!-- Acknowlegement fields -->
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr style="font-size:85%;height:40px;">
                        <td width="34%" valign="top">Received by:</td>
                        <td width="33%" valign="top">Date:</td>
                        <td width="33%" align="center" valign="top">
								For&nbsp;
                            <span style="font-size:100%;font-weight:bold;">
                                <?php echo $corporatename; ?>
                            </span>
                        </td>
                    </tr>
                    <tr style="font-size:85%;height:20px;">
                        <td colspan="3" valign="top">Signature:</td>
                    </tr>
                    <tr style="font-size:85%;height:50px;">
                        <td colspan="2" valign="top">Seal:</td>
                        <td align="center" valign="bottom"><?php echo $created_by; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
</div>
