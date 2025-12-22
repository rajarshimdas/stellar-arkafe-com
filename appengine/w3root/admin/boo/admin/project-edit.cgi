<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 12-Aug-10 								|
| Updated On:                                           |
+-------------------------------------------------------+
*/
?>
<style>
    .tblScope tr td {
        width: 35px;
        border: 1px solid gray;
        text-align: center;
        padding: 0;
    }
</style>
<?php
// Select which dialog to load
if (isset($_GET["rx"])) {
	
	// Project edit is done
	include "boo/admin/project-edit-status.cgi";

} else if (isset($_GET["pid"])) {

	// Project for editing is selected	
	$projectid = $_GET["pid"];
	include('boo/admin/project-edit-form2.cgi');

} else {

	// Project for editing is to be selected
	include('boo/admin/project-edit-form1.cgi');

}
