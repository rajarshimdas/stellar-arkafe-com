<?php /* Invoice */

/* Unauthorized access */
if (!$projectname) {
echo "Access Denied";
die;
}

$defaultpage = 'initiate';
if (!$activemenu) {
$activemenu=$defaultpage;
$activemenucontent=$defaultpage;
}

?>
<a href="rajarshi.cgi?a=t4xinitiate" <?php activeid($activemenu, 'initiate') ?>>Initiate</a>
&nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; 
<a href="rajarshi.cgi?a=t4xstatus" <?php activeid($activemenu, 'status') ?>>Status</a>
