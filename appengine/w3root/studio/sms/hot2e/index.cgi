<?php /* Drawing Lists submenu */

/* Will be needed for the first visit of this tab */
$defaultpage = 'summary';
if (!$activemenu) {
    $activemenu = $defaultpage;
    $activemenucontent = $defaultpage;
}

?>
<a href="project.cgi?a=t2xsummary" <?php activeid($activemenu, 'summary') ?>>Summary</a>
<a href="project.cgi?a=t2xblocks" <?php activeid($activemenu, 'blocks') ?>>Packages</a>
<a href="project.cgi?a=t2xcreate" <?php activeid($activemenu, 'create') ?>>Add Items</a>
<a href="project.cgi?a=t2ximport" <?php activeid($activemenu, 'import') ?>>Import</a>
<a href="project.cgi?a=t2xedit" <?php activeid($activemenu, 'edit') ?>>Edit</a>
<!-- 
<a href="project.cgi?a=t2xsketch" <?php activeid($activemenu, 'sketch') ?>>Sketch</a>
<a href="project.cgi?a=t2xview" <?php activeid($activemenu, 'view') ?>>View</a>
-->