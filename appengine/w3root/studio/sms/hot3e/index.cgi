<?php /* transmittal submenu */

$defaultpage = 'create';
if (!$activemenu) {
$activemenu=$defaultpage;
$activemenucontent=$defaultpage;
}

?>
<a href="project.cgi?a=t3xcreate" <?php activeid($activemenu, 'create') ?>>Create</a>

<a href="project.cgi?a=t3xview" <?php activeid($activemenu, 'view') ?>>View</a>

