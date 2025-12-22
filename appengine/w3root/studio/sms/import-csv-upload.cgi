<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 16-Mar-06					                |
| Updated On: 28-Jan-07					                |
+-------------------------------------------------------+
| File Upload for reading the drawing list		        |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

/* Collect User Inputs */
$loginname          = $_POST['loginname'];
$projectid          = $_POST['projectid'];
$maxRowsInCSVFile   = $_POST['maxRowsInCSVFile'];
$orgfilename        = $_FILES['file']['name'];

/* 	
--------------------------------------------------------------------------
Directory where the file is to be upload 
--------------------------------------------------------------------------
1. 	Create a folder named 'uploads' inside the documentroot directory
2.	Change the file ownership to www by using the following command
	# chown -R www:www /usr/local/w3root/uploads
--------------------------------------------------------------------------
*/
//$uploaddir = $_SERVER['DOCUMENT_ROOT']."/uploads/";
//$uploaddir = $filedb.'/'.$virtualName.'/';
$uploaddir = $filedb.'/import/';

/* File name for storage and absolute path and filename */
$now 		    = time(); 			            // timestamp
$newfilename	= 'dlist-'.$now.'.csv'; 		// timestamp.csv
$uploadfile 	= $uploaddir.$newfilename;	    // absolute path and file name
//echo "Uploaded File: $uploadfile"; die;

/* Error checking variable reset */
$upload	= 0;

/* Upload and Rename the file */
if(is_uploaded_file($tmpfile = $_FILES['file']['tmp_name'])) {

    /* File uploaded successfully */
    if (move_uploaded_file($tmpfile, $uploadfile)) {
        $upload = 1;
    } else {
        echo "<br>Error: Could not save the file $tmpfile to $uploaddir";
        die;
    }
    
} else {

    /* Error in uploading the file */
    header("Location: project.cgi?a=t2ximport");
    die;

}

/* Insert info into excel2db table */
if ($upload > 0) {

    $sql = "insert into
                excel2db
                (project_id, filename, originalfilename, noofdwgimported, loginname, importstamp)
            values
                (
                $projectid,
                '$newfilename',
                '$orgfilename',
                0,
                '$loginname',
                CURRENT_TIMESTAMP()
                )";

    $mysqli = cn2();
    if (!$mysqli->query($sql)) die("Error: $mysqli->error");

    $mysqli->close();

} 

/* Redirect */
header("Location: project.cgi?a=t2ximport-csv2html&e=$maxRowsInCSVFile&n=$now&o=$orgfilename");

?>
