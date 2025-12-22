<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 10-Dec-2007								|
| Updated On: 29-Jan-2008								|
+-------------------------------------------------------+
| Drawing List Editing									|
+-------------------------------------------------------+
| Note:													|
| Depends on view "view_drawing_list".					|
+-------------------------------------------------------+
*/
?>
<frameset cols="65%,*">
	<frame src="hot2e/t2DWGid-list.php?sx=<?php echo $sx; ?>" name='SelectPanel' />
	<frame src="hot2e/t2DWGid-edit.php?sx=<?php echo $sx; ?>" name='EditPanel' />
</frameset>