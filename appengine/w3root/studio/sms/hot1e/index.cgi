<?php /* projects submenu */

/* Will be needed for the first visit of this tab */
$defaultpage = 'snapshot';
if (!$activemenu) {
	$activemenu = $defaultpage;
	$activemenucontent = $defaultpage;
}
?>

<a href="project.cgi?a=t1xsnapshot" <?php activeid($activemenu, 'snapshot') ?>>Snapshot</a>

<?php
if ($role_id < $pm_roles_id) {
	echo '<a href="project.cgi?a=t1xmilestone"';
	activeid($activemenu, 'milestone');
	echo '>Milestone</a>';
}
?>

<?php
//if ($role_id < $pm_roles_id) {
if ($loginname == 'ashok.patel') {
	echo '<a href="project.cgi?a=t1xhistorical"';
	activeid($activemenu, 'historical');
	echo '>Historical</a>';
}
?>

<a href="project.cgi?a=t1xteam" <?php activeid($activemenu, 'team') ?>>Team</a>

<a href="project.cgi?a=t1xcontacts" <?php activeid($activemenu, 'contacts') ?>>Contacts</a>