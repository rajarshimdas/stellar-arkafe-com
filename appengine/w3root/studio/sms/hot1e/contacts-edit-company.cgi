<?php 
/* List out the existing contacts */
include('hot1e/contacts.php');

new companylist($projectid);

?>
<!-- Back to Contacts page button -->
<br>
<form action="project.cgi" method="GET">
	<input type="hidden" name="a" value="t1xcontacts">
	<input type="submit" name="go" value="<< Back to Contacts">
</form>