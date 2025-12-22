<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 27-May-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
| Timesheet :: Edit	action - step 3                 |
+-------------------------------------------------------+
*/

$timesheet_id 	= $_GET["tsid"];
$go 			= $_GET["go"];
$date			= $_GET["dt"];
/*
+-------------------------------------------------------+
| Edit button					        |
+-------------------------------------------------------+

if ($go === "edit"){

}
*/


/*
+-------------------------------------------------------+
| Delete button					        |
+-------------------------------------------------------+
*/
if ($go === "delete") {
    ?>
&nbsp;<br>Confirm Delete:
<form action="execute.cgi" method="POST">
    <input type="hidden" name="a" value="t7xedit-delete">
    <input type="hidden" name="sx" value="<?php echo $sessionid; ?>">
    <input type="hidden" name="dt" value="<?php echo $date; ?>">
    <input type="hidden" name="tsid" value="<?php echo $timesheet_id; ?>">
    <input type="submit" name="go" value="Yes" style="width:50px">
    <input type="submit" name="go" value="No" style="width:50px">
</form>


    <?php
}
?>
