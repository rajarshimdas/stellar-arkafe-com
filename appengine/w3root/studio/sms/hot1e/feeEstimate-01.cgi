<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 24-Jan-2012				|
| Updated On: 03-Jan-2013 (fgetcsv)			|
+-------------------------------------------------------+
| Manhour Budgetting                                    |
+-------------------------------------------------------+
*/
// Upload dir with the trailing '/' forward slash.
$uploaddir 	= $filedb.'/'.$virtualName.'/';

require_once 'foo/timesheets/feeEstimate.php';

$pid            = $projectid;
$now            = time();
$dtime          = date("Y-m-d H:i:s", $now);
$mysqli         = cn2();

if (!$mysqli) {
    die('Fatal: No mysqli connection...');
}
//die('PID: '.$pid);
/* 
+-------------------------------------------------------+
| Upload File						|
+-------------------------------------------------------+
| $filedb path is set in the config.php                 |
+-------------------------------------------------------+
*/

// File name for storage and absolute path and filename
$orgfilename    = $_FILES['fx']['name'];                // Original Filename
$newfilename	= 'fc-'.$projectid.'-'.$now.'.csv'; 	// New Filename
$uploadfile 	= $uploaddir.$newfilename;              // absolute path and file name

// Error Flags
$uploadFlag = 0;

if(is_uploaded_file($tmpfile = $_FILES['fx']['tmp_name'])) {

    // File uploaded successfully
    if (move_uploaded_file($tmpfile, $uploadfile)) {

        // File rename successfull
        $uploadFlag = 1;

    } else {

        // File rename not successful
        // header("Location: rajarshi.cgi?a=t1xmanhours-show&e=1");
        die("<br>Error: Could not save the file $tmpfile to ".$uploaddir.$newfilename);

    }

} else {

    // Error in uploading the file
    // header("Location: rajarshi.cgi?a=t1xmanhours-show&e=2");
    die("<br>Error: File not uploaded...");

}

/*
+-------------------------------------------------------+
| File Validation					|
+-------------------------------------------------------+
*/
$filename = $uploadfile;

// Open file for validation
$file = fopen ($filename, "r");
if (!$file) {
    die("FATAL[01]: Unable to open remote file (".$filename.")...");
}
// First Line
$line = fgets ($file, 4096);
$x = explode(",", $line);
// First cell should read concert and second cell has a hash
//die ('Row1: cell1: '.$x[0].' ; cell2: '.$x[1]);
if ($x[0] !== "concert" || $x[1] === md5("rajarshi")) {
    die('Error: CSV File Header Validation...');
} 
fclose($file);
/*
+-------------------------------------------------------+
| Get hrgroup array     				|
+-------------------------------------------------------+
*/
$rowid = 0;
$query = "select `id`,`name` from userhrgroup order by displayorder";

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {

        $hrgroupX[$rowid] = array("id" => $row[0], "nm" => $row[1]);
        $rowid++;

    }
    $result->close();
} else {
    die ('Error[1]: Possible DB connection error!');
}

/*
+-------------------------------------------------------+
| Archive old version					|
+-------------------------------------------------------+
*/
$query = 'update timeestimate set active = 0 where project_id = '.$pid;
if (!$mysqli->query($query)) die ("Error[2]: $query");

/*
+-------------------------------------------------------+
| Register the current version				|
+-------------------------------------------------------+
*/
$fx = $newfilename;
$query = "insert into timeestimateversion (project_id, version, dtime) values ($pid, '$fx', '$dtime')";
if (!$mysqli->query($query)) die ("Error[3]: $query");

/*
+-------------------------------------------------------+
| Manhour Rates         				|
+-------------------------------------------------------+
*/
$rowno      = 0;
$foundFlag  = 0;

// Open File
$f1 = fopen ($filename, "r");
if (!$f1) {
    die("FATAL[02]: Unable to open csv file (".$filename.")...");
}

// Loop Lines on File
while ((($data = fgetcsv($f1, 1000, ",")) !== FALSE) && $foundFlag < 1 ) {

    if ($data[1] === '@'){
        // echo '<h1>Found row</h1>';
        if(saveManhourRate($pid, $fx, $data, $dtime, $hrgroupX, $mysqli) !== true) {
            die ('Error[saveManhourRate]: Failed to save data....');
        }
        $foundFlag = 1;
    }

}

fclose($f1);

/*
+-------------------------------------------------------+
| Save the Data Rows					|
+-------------------------------------------------------+
*/
// Open the file
$f2 = fopen ($filename, "r");
if (!$f2) {
    die("FATAL[03]: Unable to open csv file (".$filename.")...");
}

// Data Rows
$rowno          = 1;    // Excel sheet row numbers
$countvalidrows = 0;    // Count Valid rows for importing

// Loop Lines on File
while ((($line = fgetcsv($f2, 1000, ",")) !== FALSE) && $rowno < 1000) {

    if ($line[1] === '#'){

        if (saveDataRow($pid, $fx, $line, $dtime, $hrgroupX, $mysqli) !== true) {
            die ('Error[saveDataRow]: Error could not save data...');
        }

    }

    $rowno++;

}

fclose($file);

$mysqli->close();
/*
+-------------------------------------------------------+
| Upload File						|
+-------------------------------------------------------+
*/
header("Location: rajarshi.cgi?a=t1xfeeEstimate-show&ver=$newfilename&e=0");

?>
