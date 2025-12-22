<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 						                    |
| Updated On: 17-Apr-2008, 29-Dec-2022                  |
+-------------------------------------------------------+
| Drawing List :: Blocks - Edit	                        |
+-------------------------------------------------------+
*/ 

$blockno 	= $_POST['blockno'];
// $newblockno  = $_POST['newblockno']; // Update: 12-Jun-2012
$newblockno = $blockno; // for now
$blockname 	= trim($_POST['blockname']); // Update: 12-Jun-2012
$phase		= $_POST['phase'];
$submit 	= $_POST['submit'];

/* 
+-------------------------------------------------------+
| Cancel Button Action					|
+-------------------------------------------------------+
*/ 
if ($submit === 'Cancel') {
    header("Location: project.cgi?a=t2xblocks");
    die;
}

/* 
+-------------------------------------------------------+
| Delete Button Action					|
+-------------------------------------------------------+
*/ 
if ($submit === 'Delete') {

    // Get user confirmation - before deleting
    header("Location: project.cgi?a=t2xblocks-delete&bno=$blockno&bn=$blockname");
    die;

} 

/* 
+-------------------------------------------------------+
| Edit Button Action					|
+-------------------------------------------------------+
*/ 

if ($submit === 'Edit') {

    // User rights
    if ($roleid > 69) {
        $mc = "You do not have rights to create a new block for this project";
        header("Location: project.cgi?a=t2xblocks&mc=$mc");
        die;
    }

    // Block number validation
    if (!alpha_numeric_text($blockname) || !$blockname) {
        $mc = "Package Name must contain Alpha Numeric characters and dash only...";
        header("Location: project.cgi?a=t2xblocks-editform&blockno=$blockno&mc=$mc");
        die;
    }
    

    // Update the Database
    $sql88="update
                blocks
            set
                blockno = '$newblockno',
                blockname = '$blockname',
                phase = $phase
            where
                project_id = $projectid and
                blockno='$blockno'";

    include 'foo/arnav/dblogin.cgi';
    if (!$mysqli->query($sql88)) printf("<br>Error88: %s\n", $mysqli->error);
    $mysqli->close();

}

/* 
+-------------------------------------------------------+
| Redirection						|
+-------------------------------------------------------+
*/ 
if ($mc) {
    header("Location: project.cgi?a=t2xblocks&mc=$mc");
} else {
    header("Location: project.cgi?a=t2xblocks");
}
