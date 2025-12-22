<?php
/* 
-------------------------------------------------------------------------------- 
cgi-error-warnings
1.  mc -> Message Caution
2.  me -> Message Error
-------------------------------------------------------------------------------- 
*/
if ($me) { ?>
<table 
style="width: 100%; font-size:12px; background-color:#FAE7C8;" 
border="0" cellpadding="0" cellspacing="0">
  <tbody>  
  <tr height=35px;>
  <td  style="text-align:left;vertical-align: top; width:60px;margin-left:3px;">
  &nbsp;Caution:
  <!--<img style="width: 34px; height: 34px;" alt="warning"
 src="foo/images/warning.png">--></td>
 <td style="text-align:left; vertical-align: top;">
<?php echo "$me<br>"; ?></td>
  </tr>
  </tbody>
  </table>
<?php
}
/* 
--------------------------------------------------------------------------------
cgi-error-caution 
--------------------------------------------------------------------------------
*/
if ($mc) { ?>
<table 
style="width:100%;font-size:12px; background-color:#FDC2C5;" 
border="0" cellpadding="0" cellspacing="0">
  <tbody>  
  <tr height=35px;>
  <td  style="text-align:left;vertical-align: top; width:60px;margin-left:3px;">
  &nbsp;Error:
  <!--<img style="width: 34px; height: 34px;" alt="caution"
 src="foo/images/caution.png">--></td>
 <td style="text-align:left; vertical-align: top;">
<?php echo "$mc<br>"; ?></td>
  </tr>
  </tbody>
  </table>
<?php
}


?>
