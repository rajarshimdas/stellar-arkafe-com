<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 											|
| Updated On: 17-Apr-2008								|
+-------------------------------------------------------+
| Drawing List :: Blocks - Delete						|
+-------------------------------------------------------+
*/ 

$blockno 	= $_POST['blockno'];
$blockname 	= $_POST['blockname'];
$submit 	= $_POST['submit'];

/* 
+-------------------------------------------------------+
| Cancel Button Action									|
+-------------------------------------------------------+
*/ 
if ($submit === 'Cancel') {
	header("Location:project.cgi?a=t2xblocks");
	die;
}

/* 
+-------------------------------------------------------+
| Delete Button Action									|
+-------------------------------------------------------+
*/ 

if ($submit === 'Delete') {

	// User rights - Only TL and DM
    global $roleid;   
    if ($roleid > 36) {     	
    	$mc = "Only the Team Leader or the Design Manager is allowed to delete the block defination.";
    	header("Location:project.cgi?a=t2xblocks&mc=$mc");
    	die;  
    }

    $sql88="update blocks set active=0 where project_id = $projectid and blockno='$blockno'"; 
    
    $sql99="update dwglist set 
            active = 0, 
            remark = 'Block Deleted by $loginname',            
            dtime = CURRENT_TIMESTAMP() 
            where 
            project_id = $projectid and 
            dwgidentity = '$blockno'";    
    
    include 'foo/arnav/dblogin.cgi';
    
    $mysqli->autocommit(FALSE);
    if (!$mysqli->query($sql88)) printf("<br>Error88: %s\n", $mysqli->error);
    if (!$mysqli->query($sql99)) printf("<br>Error99: %s\n", $mysqli->error);
    $mysqli->commit();
    
    $mysqli->close();	

}
/* 
+-------------------------------------------------------+
| Redirection											|
+-------------------------------------------------------+
*/ 
/*  New block has been created or user has hit the cancel button */
if ($mc) 
header("Location:project.cgi?a=t2xblocks&mc=$mc"); 
else
header("Location:project.cgi?a=t2xblocks");

?>
