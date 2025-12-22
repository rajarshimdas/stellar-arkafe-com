<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 16-Mar-06					                |
| Updated On: 28-Jan-07					                |
+-------------------------------------------------------+
*/

$maxRowsInCSVFile   = $_POST['maxRowsInCSVFile'];
$orgfilename        = $_FILES['file']['name'];

// var_dump($_FILES);

/* 	
--------------------------------------------------------------------------
Directory where the file is to be upload 
--------------------------------------------------------------------------
1. 	Create a folder named 'uploads' inside the documentroot directory
2.	Change the file ownership to www by using the following command
	# chown -R www:www /usr/local/w3root/uploads
--------------------------------------------------------------------------
*/
$uploaddir = W3PATH . 'w3filedb/import/';

/* File name for storage and absolute path and filename */
$newfilename    = 'task_' . $sid . '.csv';
$uploadfile     = $uploaddir . $newfilename;        // absolute path and file name
// die("Uploaded File: $uploadfile");

/* Upload and Rename the file */
if (is_uploaded_file($tmpfile = $_FILES['file']['tmp_name'])) {

    /* File uploaded successfully */
    if (!move_uploaded_file($tmpfile, $uploadfile)) {

        /* File could not be stored in imports folder */
        die("Error: Could not save the file $tmpfile to $uploaddir");
    }
} else {

    /* Error in uploading the file */
    header("Location:" . BASE_URL . "concert/portal/tasks/import");
    die;
}

/*
+-------------------------------------------------------+
| Redirect                                              |
+-------------------------------------------------------+
*/
header("Location:" . BASE_URL . "concert/portal/tasks/import/preview");
