<?php  /* Wizard - Step 2 action
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 01-Jan-2007				|
| Updated On: 14-Feb-2012     				|
+-------------------------------------------------------+
| */ require 'hot3e/transmittal.php';   /*              |
| */ require 'foo/t2drawing.php';       /*              |
+-------------------------------------------------------+
*/
include "foo/arnav/angels.cgi";

$tmid       = $_POST['tmid'];

/* 
+---------------------------------------+
|	Buttons                         |
+---------------------------------------+
|	1.	Add Drawing to List     |
|	2.	Add Item to List        |
|	3.	Remove from List        |
|	4.	Cancel                  |
|	5.	Next >>         	|
+---------------------------------------+
*/

$submit = $_POST['submit'];
if ($submit === "Cancel") {

    $mysqli = cn2();
    //
    $query = "update tmheader set active = 0 where id = $tmid";
    if (!$mysqli->query($query)) {
        printf("Error: %s\n", $mysqli->error);
        die;
    }
    $mysqli->close();
    //
    header("location:project.cgi?a=t3xcreate");
    die;
}

/*
+-------------------------------------------------------+
| Add Drawing to List                                   |
+-------------------------------------------------------+
*/
if ($submit === "Add Drawing to List") {

    //echo "Add new Drawing";

    //$sheetno 	= strtoupper(trim($_POST['sheetno']));
    $sn         = trim($_POST['sheetno']);
    $sx         = explode("::", $sn);
    $sheetno    = $sx[0];
    $revno 	= trim($_POST['revno']);
    $nosX 	= $_POST['nos1'];

    /* Check if the sheetno is already entered in this transmittal */
    $found_flag = 0;
    $query = "select item from tmlist where itemcode = 10 and tmheader_id = $tmid and active = 1";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {

            $x      = explode("#", $row[0]);
            $sno    = trim($x[0]);
            if ($sno === $sheetno) {
                $mc = 'The Drawing: '.$sheetno.' is already entered in this transmittal...';
                header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid&mc=$mc");
                die;
            }
        }
        $result->close();

    }

    /* Check if the sheet number is listed in the drawing list */
    $test01 = 0;
    $test02 = 0;
    global $mc;

    $dx = new drawing($projectid, $sheetno);
    $test01 = $dx->checkifexists();
    //echo "<br>Checkifexists: $test01";
    if ($test01 === 0) {
        //echo "<br>The drawing does not exist";
        $mc = 'The drawing ['.$sheetno.'] does not exist. Add the drawing to the drawing list before creating the transmittal.';
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid&mc=$mc");
        die;
    }
    if ($test01 === 1) {
        global $mc;
        $mc = 'The drawing ['.$sheetno.'] has been deleted from the drawing list';
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid&mc=$mc");
        die;
    }
    $test02 = $dx->CheckRevNo($revno);
    //echo "<br>Revno: $revno CheckRevNo: $test02";
    if ($test02 < 1) {
        $CurrentRevNo = $dx->GetCurrentRevNo();
        $sheetno = strtoupper($sheetno);
        $mc = 'The Revision Number ['.$revno.'] you have entered is not Valid. The Current Revision Number for '.$sheetno.' is '.$CurrentRevNo;
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid&mc=$mc");
        die;
    }

    // Get the drawing Title
    $title = $dx->GetDWGTitle();

    // Add the drawing to the temporary tmlist table
    $tm = new CreateTMList($projectid);
    if ($tm->AddDWG2List($tmid, $sheetno, $revno, $title, $nosX)) {
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid");
        die;
    } else {
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid&mc=$mc");
        die;
    }
}


/*
+-------------------------------------------------------+
| Add Item to List                                      |
+-------------------------------------------------------+
*/
if ($submit === "Add Item to List") {

    $tmid = $_POST['tmid'];
    $item = $_POST['item'];
    $nos2 = $_POST['nos2'];
    $desc = $_POST['desc'];

    $tm = new CreateTMList($projectid);
    if ($tm->AddItem2List($tmid,$item,$nos2,$desc)) {
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid");
        die;
    } else {
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid&mc=$mc");
        die;
    }
}


/*
+-------------------------------------------------------+
| Remove from List                                      |
+-------------------------------------------------------+
*/
if ($submit === "Remove from List") {

    //echo "Remove";
    $tmid 	= $_POST['tmid'];
    $srno 	= $_POST['removeno'];
    //$srno 	= $_POST["srno$removeno"];    

    /* instantiate */
    $tm = new CreateTMList($projectid);

    /* Remove */
    if ($tm->RemoveFromList($tmid, $srno)) {
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid");
        die;
    } else {
        header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid&mc=$mc");
        die;
    }

}

/* 
+-----------------------------------+
| Display Transmittal.              |
+-----------------------------------+
| Date: 	19-Apr-2007         |
| Updated:	07-Feb-2008         |
+-----------------------------------+
*/
if ($submit === "Next >>") {

    $tmid = $_POST['tmid'];

    $mysqli = cn2();
    $query = "update tmheader set `wizardstepno` = 3 where `id` = $tmid";

    if (!$mysqli->query($query)) {
        printf("Error: %s\n", $mysqli->error);
    }

    $mysqli->close();

    header("location:project.cgi?a=t3xcreate-3&tmid=$tmid");

}

?>
