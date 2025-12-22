<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 25-May-10									|
| Updated On: 											|
+-------------------------------------------------------+
*/


// Active menu
$activemenu = (isset($_GET["a"])) ? $_GET["a"] : $_POST["a"];

// Hard coded value when everything else failed
if (!$activemenu) $activemenu = 'Project New';

// Menubar function
function menubar($menuname, $activemenu, $menucontent)
{

	global $content;

	// check if this is the active menu
	if ($menuname === $activemenu) {

		$color = 'RGB(243,234,157)';
		$content = "$menucontent.cgi";
		
	} else {

		$color = 'RGB(225,225,225)';
	}

	// echo '<tr style="background:'.$color.';height:25px;"><td class="dataRowCell2a">&nbsp;<a href="r24in.php?a='.$menuname.'" style="text-decoration:none;color:RGB(75,75,75)">'.$menuname.'</a></td></tr>';

	echo '<tr><td class="dataRowCell2a" style="background: ' . $color . '; padding: 5px"><a href="sysadmin.cgi?a=' . $menuname . '">' . $menuname . '</a></td></tr>';
}
