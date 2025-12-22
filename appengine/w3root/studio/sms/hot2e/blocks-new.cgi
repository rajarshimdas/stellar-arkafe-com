<?php /*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 						                    |
| Updated On: 16-Apr-2008, 29-Dec-2022				    |
+-------------------------------------------------------+
| Drawing List :: Blocks > Package                      |
+-------------------------------------------------------+
| Create a new block defination				            |
+-------------------------------------------------------+
*/ 

$blockno 	= trim($_POST['blockno']);
$blockname 	= trim($_POST['blockname']); // Updated: 12-Jun-2012
$phase		= $_POST['phase'];

//echo "BlockNo: $blockno<br>BlockName: $blockname<br>Phase: $phase";

/* Get the database connection */
include 'foo/arnav/dblogin.cgi';
if (!$mysqli) echo "DB: Connection error"; 

/* User rights */  
if ($roleid > 69) {
    $mc = "You do not have rights to create a new block for this project";
    header("Location: project.cgi?a=t2xblocks&mc=$mc");
    die;
}  

/* 
+-------------------------------------------------------+
| Data Validation					                    |
+-------------------------------------------------------+
*/ 

// Incomplete data input
if (!$blockno || !$blockname) {
    $mc = "Require valid data input for Package Id and Package Name.";
    header("Location: project.cgi?a=t2xblocks&mc=$mc");
    die;
}


// ci3 form validation
if (!alpha_numeric_dash($blockno)) {
    $mc = "Package Id must contain Alpha Numeric characters and dash only...";
    header("Location: project.cgi?a=t2xblocks&mc=$mc");
    die;
} 


if (!alpha_numeric_text($blockname)) {
    $mc = "Package Name ($blockname) must contain Alpha Numeric characters and dash only...";
    header("Location: project.cgi?a=t2xblocks&mc=$mc");
    die;
} 


/* Validation: Does the block already exists for the current project? */
$sql96="select blockno from blocks where 
        project_id 	= '$projectid' and 
        blockno 	= '$blockno' and
        active 		= 1";
//echo "<br>SQL96: $sql96";  

if ($r96 = $mysqli->query($sql96)) {
    $co2 = $r96->num_rows;

    if ($co2 > 0) {
        // A block with the same blockno exists
        $mc = "Block ['$blockno'] already exists.";
        header("Location: project.cgi?a=t2xblocks&mc=$mc");
        die;
    }

    $r96->close();

}else {

    printf("<br>Error69: %s\n", $mysqli->error);

}      

/* 
+-------------------------------------------------------+
| Insert into Database					|
+-------------------------------------------------------+
*/  
$sql88 = "insert into blocks values ('$projectid',ucase('$blockno'),'$blockname',$phase,1)";        
//echo "<br>SQL88: $sql88";

if (!$mysqli->query($sql88)) printf("<br>Error88: %s\n", $mysqli->error);

/* Close the database connection */
$mysqli->close();

/* Redirect */
header("Location: project.cgi?a=t2xblocks");
