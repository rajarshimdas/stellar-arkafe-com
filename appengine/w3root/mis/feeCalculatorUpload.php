<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 20-Jan-2012				|
| Updated On:           				|
+-------------------------------------------------------+
*/
require_once 'foo/bar/timeTracker.php';
require_once 'bootstrap.php';
$mysqli = cn1();

// Session Validation
require_once 'foo/session.php';
$sessionX = getSessionInfo($mysqli);

// Variables
$pid    = $_POST['pid'];
$fx     = $_POST['fx'];
$go     = $_POST['go'];
// echo "ProjectId: ".$pid."<br>Filename: ".$fx;

if ($go === "Cancel"){
    header("Location:home.cgi"); die;
}

// Other variables
$dtime = date("Y-m-d H:i:s");
$mysqli->close();
$mysqli = cn2();
echo 'MySQL: '.$dtime;

/*
+-------------------------------------------------------+
| Get hrgroup array     				|
+-------------------------------------------------------+
*/
$query = "select `id`,`name` from userhrgroup order by displayorder";
$rowid = 3;

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {
        $hrgroupX[$rowid] = array("id" => $row[0], "nm" => $row[1]);
        $rowid++;
    }

    $result->close();
}

/*
+-------------------------------------------------------+
| Archive old version					|
+-------------------------------------------------------+
*/
$query = 'update timeestimate set active = 0 where project_id = '.$pid;
if (!$mysqli->query($query)) die ('Error: 1');

/*
+-------------------------------------------------------+
| Register the current version				|
+-------------------------------------------------------+
*/
$query = "insert into timeestimateversion (project_id,version,dtime) values ($pid,'$fx','$dtime')";
if (!$mysqli->query($query)) die ('Error: 2');

/*
+-------------------------------------------------------+
| Manhour Rates         				|
+-------------------------------------------------------+
*/
$rowno      = 1;
$foundFlag  = 0;
$filename   = $filedb."/feeCalculator/".$fx;
$f1         = fopen ($filename, "r");
if (!$f1) die("Unable to open csv file...");

while (!feof($f1) && $rowno <= 200 && $foundFlag < 1) {
    
    $line   = fgets ($f1, 4096);
    $cell   = explode(",", $line);
    if (trim($cell[0]) == '@'){
        saveManhourRate($pid, $fx, $line, $dtime, $hrgroupX, $mysqli);
        $foundFlag = 1;
    }    
    $rowno++;
    
}

fclose($f1);

/*
+-------------------------------------------------------+
| Save the Data Rows					|
+-------------------------------------------------------+
*/
// Open the file
$file = fopen ($filename, "r");
if (!$file) die("Unable to open csv file...");

// Data Rows
$rowno          = 1;    // Excel sheet row numbers
$countvalidrows = 0;    // Count Valid rows for importing

while (!feof($file) && $rowno <= 500) {    
    
    $line   = fgets ($file, 4096);
    $cell   = explode(",", $line);

    if ($cell[0] === '#'){
        saveDataRow($pid, $fx, $line, $dtime, $hrgroupX, $mysqli);
    }
    
    $rowno++;
}

fclose($file);

/*
+-------------------------------------------------------+
| Redirect       					|
+-------------------------------------------------------+
*/
header("Location:home.cgi");

?>
