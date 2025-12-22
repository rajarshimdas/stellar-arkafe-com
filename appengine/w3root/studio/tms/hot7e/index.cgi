<?php /* Drawing Lists submenu */

/* Unauthorized access */
if (!$projectname) {
echo "Access Denied";
die;
}

/* Will be needed for the first visit of this tab */
$defaultpage = 'summary';
if (!$activemenu) {
$activemenu=$defaultpage;
$activemenucontent=$defaultpage;
}

/* HACK TO RESTRICT USERS FROM UNAUTHORIZED ACCESS 
if ($roleid > 45 && $defaultpage === 'summary') {
	$defaultpage = 'timesheet'; 
	$activemenucontent = 'timesheet';
}
*/
?>


<?php /* DM and TLs only */ if ($roleid < 45){ ?>
<a href="rajarshi.cgi?a=t7xsummary" <?php activeid($activemenu, 'summary') ?>>Summary</a>
<?php } ?>

<!--
<?php /* DM and TLs only */ if ($roleid < 45){ ?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<a href="rajarshi.cgi?a=t7xweek" <?php activeid($activemenu, 'week') ?>>Week</a>
<?php } ?>
-->
<!--
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<a href="rajarshi.cgi?a=t7xtimesheet" <?php activeid($activemenu, 'timesheet') ?>>Time</a>

&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<a href="rajarshi.cgi?a=t7xedit" <?php activeid($activemenu, 'edit') ?>>Delete</a>


<?php /* DM and TLs only */ if ($roleid < 45){ ?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<a href="rajarshi.cgi?a=t7xapproval" <?php activeid($activemenu, 'approval') ?>>Approval</a>
<?php } ?>
-->

<?php /* DM and TLs only */ if ($roleid < 45){ ?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<a href="rajarshi.cgi?a=t7xproject" <?php activeid($activemenu, 'project') ?>>Project</a>
<?php } ?>

<?php /* DM and TLs only */ if ($roleid < 45){ ?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<a href="rajarshi.cgi?a=t7xteammate" <?php activeid($activemenu, 'teammate') ?>>Teammate</a>
<?php } ?>