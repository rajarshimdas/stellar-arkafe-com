<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 24-Jan-2012				|
| Updated On:                                           |
+-------------------------------------------------------+
| File Upload for reading the Fee Estimate Template	|
+-------------------------------------------------------+
*/
require_once 'bootstrap.php';
require_once 'foo/bar/timeTracker.php';
require_once 'foo/session.php';

$mysqli = cn1();

// Session Validation
$sessionX = getSessionInfo($mysqli);

?><head>
    <title>Fee Calculator Upload</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type='text/css' rel='stylesheet' href="foo/style.css">
</head>
<?php

/* Collect User Inputs */
$loginname 	= $_POST['ln'];
$passwd         = $_POST['pw'];
$projectid	= $_POST['pid'];
$orgfilename 	= $_FILES['fx']['name'];

// echo "Loginname: ".$loginname."<br>Password: ".$passwd."<br>ProjectId: ".$projectid."<br>FileName: ".$orgfilename;

/*
+-------------------------------------------------------+
| Upload File						|
+-------------------------------------------------------+
| $filedb path is set in the config.php                 |
+-------------------------------------------------------+
*/
$uploaddir 	= $filedb."/feeCalculator/";
// echo "<br>uploaddir: ".$uploaddir;

// File name for storage and absolute path and filename
$now 		= time();                               // timestamp
$newfilename	= 'fc-'.$projectid.'-'.$now.'.csv'; 	// Filename
$uploadfile 	= $uploaddir.$newfilename;              // absolute path and file name
//echo "Uploaded File: $uploadfile";

/*
+-------------------------------------------------------+
| User confirmation form                           	|
+-------------------------------------------------------+
*/ 
?>
<body>
    <div align="center">
        <?php
        
        // Header
        require_once 'foo/header.php';
        showHeader($hostname, 1, 1);
        ?>
        <div style="width:1000px;background: #c6e8ff; height: 80px; border: 1px solid black;">

            <?php

            // Data validation
            if (!$projectid) {
                die('<br>&nbsp;<br>Error: Project Not Selected. Click Home and Select a Project....');
            }
            if (!$orgfilename || $orgfilename == "") {
                die('<br>&nbsp;<br>Error: No File Selected. Click Home and try again....');
            }

            // Upload the file
            // Upload and Rename the file
            $upload	= 0; // Error checking variable initiation

            if(is_uploaded_file($tmpfile = $_FILES['fx']['tmp_name'])) {

                // File uploaded successfully
                if (move_uploaded_file($tmpfile,$uploadfile)) {

                    // File rename successful
                    $upload = 1;

                } else {

                    // File rename not successful
                    echo "<br>Error: Could not save the file $tmpfile";
                    die;
                }
            } else {

                // Error in uploading the file
                echo "Error: Error in uploading the file";
                //header("Location: rajarshi.cgi?a=t2ximport");
                die;

            }
            // Projectname
            require_once 'foo/pid2pname.php';
            $projectname = pid2pname($projectid,$mysqli);

            ?>
            <span style="font-weight: bold">
                <?php echo $projectname; ?>
            </span>
            <form action="feeCalculatorUpload.php" method="POST">
                <input type="hidden" name="pid" value="<?php echo $projectid; ?>">
                <input type="hidden" name="fx" value="<?php echo $newfilename; ?>">
                &nbsp;<br>Confirm Upload:
                <input type="submit" name="go" value="Upload">
                <input type="submit" name="go" value="Cancel">
            </form><br>

            <?php
            /*
        +-------------------------------------------------------+
        | Tabulate the Data for Preview                         |
        +-------------------------------------------------------+
        | fc-functions.php                                      |
        +-------------------------------------------------------+
            */
            $filename = $filedb."/feeCalculator/".$newfilename;
            //echo '<br>Filename: '.$filename;

            $file = fopen ($filename, "r");
            if (!$file) {
                die("Unable to open remote file...");
            }
            // Header
            echo '<table width="1000px" cellspacing="0">';
            generateHeaderRow($projectid,$mysqli);
            // Data Rows
            $rowno          = 1;    // Excel sheet row numbers
            $countvalidrows = 0;    // Count Valid rows for importing
            while (!feof ($file) && $rowno <= 500) {
                // Check if this is a data row
                $line   = fgets ($file, 4096);
                generateDataRow($projectid, $line, $rowno, $mysqli);
                $rowno++;
            }
            echo '</table><br>&nbsp;';
            ?>
        </div>
    </div>
</body>