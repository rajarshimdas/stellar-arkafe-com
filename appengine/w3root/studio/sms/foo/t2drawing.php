<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007				                |
| Updated On: 17-Nov-2012     				            |
+-------------------------------------------------------+
|  require_once 'bootstrap.php';                        |
| */ include 'foo/dateCal2Mysql.php';   /*              |
+-------------------------------------------------------+
*/

// This function is not used. Buggy!
function checkAlphanumeric($string)
{
    return (preg_match('/^([A-Za-z0-9 ]+.)+$/', $string));
}

class drawing
{

    /* Properties */
    var $ProjID;            // Project ID
    var $SheetNo;           // Sheet Number
    var $Title;             // Drawing Title
    var $DWGid;             // Drawing Identity
    var $DWGdc;             // Discipline Code
    var $DWGunit;           // Drawing Unit
    var $DWGpart;           // Drawing Part
    var $ListID;            // Drawing List ID
    var $CurrentListVer;    // Current list version for the project
    var $CurrentRevNo;      // Current revision number of this drawing

    /*
    +-------------------------------------------------------+
    | Constructor: create a new drawing object              |
    +-------------------------------------------------------+
    | Sheet No: B1-A-[Unit]-[Part]                          |
    +-------------------------------------------------------+
    */
    function __construct($projid, $sheetno)
    {
        if (!empty($sheetno)) {
            /* Explode the drawing number */
            $ex = explode("-", $sheetno);
            //$rx = explode(".", $ex[2]);

            if (count($ex) < 3) return false;

            /* Get the drawing number components */
            $this->DWGid      = strtoupper(trim($ex[0]));
            $this->DWGdc      = strtoupper(trim($ex[1]));
            $this->DWGunit    = addslashes(strtoupper(trim($ex[2])));
            $this->DWGpart    = empty($ex[3]) ? "" : strtoupper(trim($ex[3]));

            /* This instance Sheet Number and Projectid */
            $this->ProjID     = $projid;
            $this->SheetNo    = trim($sheetno);

            // echo "<br>Class Drawing<br>Projectid: $this->ProjID<br>Sheetno: $this->SheetNo<br>id: $this->DWGid<br>dc: $this->DWGdc<br>un: $this->DWGunit<br>pt: $this->DWGpart";
            // die;
            return TRUE;
        }

        return false;
    }

    /*
    +-------------------------------------------------------+
    | Method: GetDWGTitle                                   |
    +-------------------------------------------------------+
    */
    function GetDWGTitle()
    {

        //include 'foo/arnav/angels.cgi';
        $mysqli = cn1();

        $sql = "select
                    title
                from
                    dwglist
                where
                    project_id      = $this->ProjID and
                    dwgidentity     = '$this->DWGid' and
                    disciplinecode  = '$this->DWGdc' and
                    unit            = '$this->DWGunit' and
                    part            = '$this->DWGpart'";

        if ($result = $mysqli->query($sql)) {

            $row = $result->fetch_row();
            $this->Title = $row[0];

            $result->close();
        }

        $mysqli->close();
        return $this->Title;
    }
    /*
    +-------------------------------------------------------+
    | Method: checkifexists                                 |
    +-------------------------------------------------------+
    */
    function checkifexists()
    {
        /*
        Purpose:  Check if this drawing instance already exits in the dwglist.    
        Return:     A.  Does not exist:		0
                    B.  Exists - Inactive:      1
                    C.  Exists - Active:	2
        */
        global $loginname, $roleid, $projectid, $projectname;

        $result_flag    = 0;        // Assuming does not exists
        $row_cnt        = 0;        // Testing if a row matching the sheetno is returned

        //include 'arnav/angels.cgi';
        $mysqli = cn1();

        /* Check for drawing number in this project */
        $query = "select
                    id, 
                    active
                from
                    dwglist
                where
                    dwgidentity     = '$this->DWGid' and
                    disciplinecode  = '$this->DWGdc' and
                    unit            = '$this->DWGunit' and
                    part            = '$this->DWGpart' and
                    project_id      = $this->ProjID";
        if ($result = $mysqli->query($query)) {

            $row_cnt = $result->num_rows;

            if ($row_cnt > 0) {
                $row = $result->fetch_row();
                $this->ListID = $row[0];
                $active = $row[1];
            }

            $result->close();
        }

        if ($row_cnt > 0) {
            // SheetNo exists
            if ($active < 1) {
                // SheetNo is inactive
                $result_flag = 1;
            } else {
                // SheetNo is active
                $result_flag = 2;
            }
        }

        $mysqli->close();
        return $result_flag;
    }
    /*
    +-------------------------------------------------------+
    | Method: checktargetdt                                 |
    +-------------------------------------------------------+
    */
    function checktargetdt($dt)
    {
        /*
        Purpose:  Check date validity. Input Format [yyyy-mm-dd]
        Returns:    valid date    1
                    invalid date  0
        */
        $x = explode("-", $dt);
        $date = $x[2];
        $month = $x[1];
        $year = $x[0];

        if ($month === "Jan") $month = 1;
        if ($month === "Feb") $month = 2;
        if ($month === "Mar") $month = 3;
        if ($month === "Apr") $month = 4;
        if ($month === "May") $month = 5;
        if ($month === "Jun") $month = 6;
        if ($month === "Jul") $month = 7;
        if ($month === "Aug") $month = 8;
        if ($month === "Sep") $month = 9;
        if ($month === "Oct") $month = 10;
        if ($month === "Nov") $month = 11;
        if ($month === "Dec") $month = 12;

        if (!$date || !$month || !$year) {
            $mc = "The input date: $dt is not valid";
            return 0;
        }
        /* echo "<br>Date: $date Month: $month Year: $year"; */
        /* Check date validity */
        $validdt = checkdate($month, $date, $year);
        if ($validdt) {
            /* if valid return 1 */
            return $validdt;
        } else {
            /* if invalid return 0 */
            $mc = "The input date: $dt is not valid";
            return 0;
        }
    }
    /*
    +-------------------------------------------------------+
    | Method: checksheetno                                  |
    +-------------------------------------------------------+
    */
    function checksheetno()
    {
        /*
        Purpose:    Check validity of the Drawing Number as per our CAD Standards - R3
        Returns:    valid     1
                    invalid   0
        Sheet No:   B1-A-[Unit]-[Part]
        */

        //include 'arnav/angels.cgi';
        $mysqli = cn1();

        /* Reset the test variables */
        $testresult     = 0;            // Assume negative
        $t1             = 0;            // Block Id error flag
        $t2             = 0;            // Discipline error flag
        $t3             = 0;            // Drawing Unit error flag
        $t4             = 0;            // Part error flag
        $co             = 0;
        global $mc;

        /* Check the sheet number */
        $sheetno = $this->SheetNo;
        $ex = explode("-", $sheetno);

        if (count($ex) > 4 || count($ex) < 3) {
            $mc = "Sheet Number is not correct. Refer CAD Standards";
            //echo '<br>Error: '.$mc; die;
            return 0;
        }

        /* Check the drawing identity */
        if (empty($this->DWGid)) {
            $mc = "Drawing Identity field was empty";
            //echo '<br>Error: '.$mc; die;
            return 0;
        }

        $sql69 = "select
                    blockno
                from
                    blocks
                where
                    blockno     = '$this->DWGid' and
                    project_id  = '$this->ProjID' and
                    active      = 1";

        if ($r69 = $mysqli->query($sql69)) {
            $co = $r69->num_rows;
            if ($co > 0) {
                $t1 = 1;
            } else {
                $mc = "Package ID does not exist.";
                //echo '<br>Error: '.$mc;
                return 0;
            }
            $r69->close();
        } else {
            printf("<br>Error69: %s\n", $mysqli->error);
        }

        /* Check the discipline code */
        $dc = $this->DWGdc;
        $co = 0;
        if (empty($dc)) {
            $mc = "Disciplinecode field was empty";
            //echo '<br>Error: '.$mc;
            return 0;
        }
        //echo "<br>Disciplinecode: $dc";
        $sql88 = "select 1 from discipline where disciplinecode = '$dc'";
        //echo "<br>SQL88: $sql88";
        if ($r88 = $mysqli->query($sql88)) {
            $co = $r88->num_rows;
            if ($co > 0) {
                $t2 = 1;
            } else {
                $mc = "Disciplinecode is wrong.";
                //echo '<br>Error: '.$mc; die;
                return 0;
            }
            $r88->close();
        } else {
            printf("<br>Error88 %s\n", $mysqli->error);
        }
        $mysqli->close();

        /* Check drawing unit */
        $unit   = $this->DWGunit;
        $co     = 0;
        if (empty($unit)) {
            $mc = "Drawing Unit field was empty";
            //echo '<br>Error: '.$mc; die;
            return 0;
        }

        /* REGEX for alphanumeric and allowed chars */
        if (checkAlphanumeric($this->DWGunit)) {
            $t3 = 1;
        } else {
            $mc = "The Drawing Unit can contain alpha-numeric characters & 'dot' only...";
            return 0;
        }

        /* Check drawing part */
        $haystack = 'abcdefghijklmnopqrstuvwxyz';
        $needle   = strtolower($this->DWGpart);

        if (empty($needle)) {
            $t4 = 1;
        } else {
        
            /*
            strripos | Warning
            This function may return Boolean false, but may also return a non-Boolean value 
            which evaluates to false. Please read the section on Booleans for more information. 
            Use the === operator for testing the return value of this function.
            */
            /*
            if (strripos($haystack, $needle) !== false) { echo "T"; } else { echo "F"; }
            die("<p>Part: $haystack : $needle</p>");
            */
            if (strripos($haystack, $needle)  !== false) {
                $t4 = 1;
            } else {
                $mc = "The Part [ $needle ] in SheetNo is wrong.";
                return 0;
            }
        }

        // If no errors found, then sheetno is correct.
        if ($t1 > 0 && $t2 > 0 && $t3 > 0 && $t4 > 0) $testresult = 1;

        return $testresult;
    }
    /*
    +-------------------------------------------------------+
    | Method: CheckRevNo                                    |
    +-------------------------------------------------------+
    */
    function CheckRevNo($newRevNo)
    {
        /*
        Purpose:  Check validity of the new revision number
        Returns:    invalid   	0
                    same/valid	1
                    new       	2
        Last Updated:
        1.	17-Apr-07 	Fixed: 	System returns 2 if the revision number is new
                                        To do: 	Test whether the currentrevno is issued
                                                        before creating a new revision number
        2. 	18-Apr-07 	Fixed:  System checks for lastissuedrevno before allowing
                                                        a new revision
        3.	11-Jun-07	Fixed:	RevNo sequence not to be validated for the schematic
                                                        stages. Simple haystack search is enough.
        */

        /* Empty new revno */
        if (empty($newRevNo)) {
            $mc = "Revision Number Field was empty";
            return 0;
        }

        $ex = explode(".", $newRevNo);
        $newRN = strtoupper($ex[0]);

        /* Get RevNo details for this drawing from the dwglist table */
        $sql = "select
                    currentrevno,
                    lastissuedrevno,
                    r0issuedflag
                from
                    dwglist
                where
                    id = '$this->ListID'";

        $mysqli = cn1();
        // echo 'M: ' . $mysqli->host_info;

        if ($result = $mysqli->query($sql)) {
            $row = $result->fetch_row();

            $curRN               = $row[0];
            $lastissuedrevno     = $row[1];
            $r0issuedflag        = $row[2];

            $result->close();
        } else {
            printf("Error: %s\n", $mysqli->error);
        }
        $mysqli->close();

        /* Format Current RevNo and Last Issued RevNo */
        $ex                 = explode(".", $curRN);
        $curRN              = strtoupper($ex[0]);
        $ex                 = explode(".", $lastissuedrevno);
        $lastissuedrevno    = strtoupper($ex[0]);

        /* New drawing: Ver A */
        if ($newRN === "A" && !$curRN) return 2;

        /* Same Revision Number */
        if ($newRN === $curRN) return 1;

        /* Validation for schematic to gfc transitory revision: R0 */
        if ($newRN === "R0" && $r0issuedflag < 1) return 2;

        /* Schematic stage RevNo simple haystack search validation */
        if ($r0issuedflag < 1) {

            $haystack = 'abcdefghijklmnopqrstuvwxyz';
            $needle   = $newRN;

            /*
            strripos | Warning
            This function may return Boolean false, but may also return a non-Boolean value 
            which evaluates to false. Please read the section on Booleans for more information. 
            Use the === operator for testing the return value of this function.
            */
            
            if (strripos($haystack, $needle) !== false) {
                return 1;
            } else {
                $mc = "Error: Revision Number was wrong";
                return 0;
            }
        }

        /* Check for GFC stage revision number validity | Feb 2025 */
        if ($r0issuedflag > 0) {

            if (substr($newRN, 0, 1) != "R") {
                $mc = "Error: GFC revision starts with R, like R0, R1, R2...";
                return 0;
            }

            $newf1 = substr($newRN, 1);
            $curf1 = substr($curRN, 1);

            if (is_numeric($newf1) && is_numeric($curf1)) {
                $f1 = $newf1 - $curf1;
            } else {
                return 0;
            }

            if ($f1 >= 0 && $f1 < 2) {
                return 2;
            } else {
                $$mc = "Error: GFC revision numbers allowed are R" . $curf1 . " and R" . ($curf1 + 1);
                return 0;
            }
        }

        /* If no condition matched, its wrong */
        $mc = "Error: Revision Number was wrong";
        return 0;
    }
    /*
    +-------------------------------------------------------+
    | Method: GetCurrentRevNo                               |
    +-------------------------------------------------------+
    */
    function GetCurrentRevNo()
    {

        $t2 = $this->checkifexists();
        if ($t2 > 0) {

            //include 'arnav/angels.cgi';
            $mysqli = cn1();

            $sql35 = "select currentrevno from dwglist where id = $this->ListID";
            if ($r35 = $mysqli->query($sql35)) {
                $row35 = $r35->fetch_row();
                $currentrevno = $row35[0];
                $r35->close();
            } else {
                printf("<br>Error35: %s\n", $mysqli->error);
            }
            $mysqli->close();
        } else {
            echo "drawing::CheckRevNo Error: Specified drawing does not exist";
        }
        /* Set Property CurrentRevNo */
        $this->CurrentRevNo = $currentrevno;
        return $currentrevno;
    }
    /*
    +-------------------------------------------------------+
    | Method: GetCurrentListVer                             |
    +-------------------------------------------------------+
    */
    function GetCurrentListVer()
    {

        //include 'arnav/angels.cgi';
        $mysqli = cn1();

        $sql33 = "select currentdwglistver from projects where id = $this->ProjID";
        if ($r33 = $mysqli->query($sql33)) {
            $row33 = $r33->fetch_row();
            $listver = $row33[0];
            $r33->close();
        } else {
            printf("<br>Error33: %s\n", $mysqli->error);
        }
        $mysqli->close();
        //echo "<br>Current drawing list Version is: $listver";
        $this->CurrentListVer = $listver;
        return $listver;
    }
    /*
    +-------------------------------------------------------+
    | Method: CreateNewDWG                                  |
    +-------------------------------------------------------+
    */
    function CreateDWG($revno, $title, $remark, $stage, $commitdt, $targetdt, $mysqli)
    {

        // echo '<br>CreateNewDWG';
        global $loginname, $roleid, $projectname, $mc;
        $ok = 0; //if all transactions succeed this will trun to 1

        $title      = addslashes($title);
        $remark     = addslashes($remark);

        $projectid     = $this->ProjID;
        $identity     = $this->DWGid;
        $disciplinecode    = $this->DWGdc;
        $unit         = $this->DWGunit;
        $part         = $this->DWGpart;
        $sheetno     = $this->SheetNo;

        /* Check User Role 
        if ($roleid > 69) {
            $mc = "You do not have create right for $projectname";
            return false;
        }
        */

        /* Check SheetNo validity */
        $t2 = $this->checksheetno();
        if ($t2 < 1) {
            // echo '<br>Error returned by checksheetno';
            return false;
        }

        /* Check if Sheet already exists */
        $t3 = $this->checkifexists();
        if ($t3 > 1) {
            $mc = "A Drawing of the specified Drawing Number already exists in the Drawing List";
            return false;
        }
        
        if ($t3 < 1) {
            $SNoExists = 0;
            $creatememo = 'New Drawing';
        } else {
            $SNoExists = 1;
            $creatememo = 'Re-Activating deleted Drawing';
        }

        /* Check Target Date Validity
        Update: 28-Jan-08
        1.  Allow users to import drawing list without target date. Users can set the
            commited target date and the publicly displayed target dates later.
        2.  Syntax: For Targetdts values can be:
        a. a valid date
        b. x or X
        3.  The drawing class will handle the date checkup and errors...
        

        if ($targetdt === "X" || $targetdt === "x") {

            // No target date specified formatting (25-Jan-2008)
            $targetdt = "0000-00-00";

        } else {

            // Check target date. A valid date will return 1
            $t4 = $this->checktargetdt($targetdt);
            if ($t4 < 1) {
                return false;
            } else {
                // $targetdt = "STR_TO_DATE('$targetdt','%d-%b-%y')";
                $targetdt = dateCal2Mysql($targetdt);
            }

        }
        */
        $targetdt = "0000-00-00";
        $commitdt = $targetdt;

        $dtime = date('Y-m-d H:i:s');

        /* UPDATE DRAWING IN dwglist if sheetno already exists */
        $sql2a = "UPDATE
                    dwglist
                SET
                    currentrevno = '$revno',
                    title = '$title',
                    remark = '$remark',
                    stage = '$stage',
                    newstage = '$stage',
                    stageclosed = 0,
                    r0targetdt = '$targetdt',
                    newr0targetdt = '$targetdt',
                    r0issuedflag = 0,
                    r0issuedt = '0000-00-00',
                    lastissuedrevno = '-',
                    lastissueddate = '0000-00-00',
                    dtime = '$dtime',
                    active = 1
                WHERE
                    id = $this->ListID";
        /*  echo "<br>sql2a: $sql2a";*/

        /* INSERT DRAWING INTO dwglist if sheet no does not exist */
        $sql2b = "INSERT INTO dwglist
                    (project_id,
                    dwgidentity,
                    disciplinecode,
                    unit,
                    part,
                    currentrevno,
                    title,
                    scaleina1,
                    remark,
                    priority,
                    stage,
                    newstage,
                    stageclosed,
                    r0targetdt,
                    newr0targetdt,
                    r0issuedflag,
                    r0issuedt,
                    lastissuedrevno,
                    lastissueddate,
                    dtime)
                VALUES
                    ($projectid,
                    '$identity',
                    '$disciplinecode',
                    '$unit',
                    '$part',
                    '$revno',
                    '$title',
                    '-',
                    '$remark',
                    '-',
                    '$stage',
                    '$stage',
                    0,
                    '$targetdt',
                    '$targetdt',
                    0,
                    '0000-00-00',
                    '-',
                    '0000-00-00',
                    '$dtime')";
        /* echo "<br>sql2b: $sql2b";*/

        $mysqli->autocommit(FALSE);

        /* Insert/Update dwglist table */
        if ($SNoExists > 0) {

            /* UPDATE DRAWING IN dwglist if sheetno already exists */
            if (!$mysqli->query($sql2a)) {
                $mc = "Error: $mysqli->error";
                $mysqli->rollback();
                return false;
            }
            $dwglist_id = $this->ListID;
        } else {

            /* INSERT DRAWING INTO dwglist if sheet no does not exist */
            if (!$mysqli->query($sql2b)) {
                $mc = "Error: $mysqli->error";
                $mysqli->rollback();
                return false;
            }
            $dwglist_id = $mysqli->insert_id;
        }
        /*  echo "<br>Drawing list id: $dwglist_id";*/

        /* Register new drawing in dwghistory table */
        $sql4 = "INSERT INTO
                    dwghistory
                    (dwglist_id,
                    newrevno,
                    revno,
                    newdwglistver,
                    olddwglistver,
                    title,
                    remark,
                    scaleina1,
                    lastissuedrevno,
                    lastissueddt,
                    newstg,
                    newstage,
                    newstgreason,
                    newr0dt,
                    r0newdt,
                    r0reason,
                    loginname,
                    dtime,
                    active)
                VALUES
                    ($dwglist_id,
                    1,
                    '$revno',
                    0,
                    '-',
                    '$title',
                    '$remark',
                    '$creatememo',
                    '1',
                    '0000-00-00',
                    0,
                    88,
                    '-',
                    1,
                    '$targetdt',
                    '-',
                    '$loginname',
                    '$dtime',
                    1)";
        /* echo "<br>SQL4: $sql4";*/

        if (!$mysqli->query($sql4)) {
            $mc = "Error: $mysqli->error";
            $mysqli->rollback();
            return false;
        } else {
            /* All transactions done */
            $ok = 1;
            $mysqli->commit();
        }

        if ($ok < 1) {
            return false;
        } else {
            return true;
        }
    }
    /*
    +-------------------------------------------------------+
    | Method: UpdateDWG                                     |
    +-------------------------------------------------------+
    */
    function UpdateDWG($oldrevno, $newrevno, $oldstage, $newstage, $oldr0targetdt, $newr0targetdt, $newr0reason, $title, $remark)
    {

        global $projectname, $loginname, $roleid;
        $mc = "<!-- NA -->";

        /* Check User Role */
        if ($roleid > 69 || !$roleid) {
            $mc = "You do not have create right for $projectname";
            return $mc;
        }

        /* Data Validation */
        $t5 = $this->CheckRevNo($newrevno);
        if ($t5 < 1) return $mc;
        $t1 = $this->checktargetdt($newr0targetdt);
        if ($t1 < 1) return $mc;
        $t2 = $this->checkifexists();
        if ($t2 < 1) return $mc;

        if ($oldrevno === $newrevno) $newRN = 0;
        else $newRN = 1;
        if ($oldstage === $newstage) $newST = 0;
        else $newST = 1;
        if ($oldr0targetdt === $newr0targetdt) $newtargetdt = 0;
        else $newtargetdt = 1;

        $sql25 = "update dwglist set
          currentrevno = '$newrevno',
          title = '$title',
          remark = '$remark',
		  newstage = '$newstage',          
          newr0targetdt = '$newr0targetdt',
          dtime = CURRENT_TIMESTAMP()
          where id = $this->ListID";

        $sql35 = "insert into dwghistory values
          ($this->ListID,
            $newRN,
            '$newrevno',
            0,
            '-',
            '$title',
            '$remark',
            '-',
            '-',
            '0000-00-00',
            $newST,
            $newstage,
            '-',		  		  
            $newtargetdt,
            '$newr0targetdt',
            '$newr0reason',        
            '$loginname',
            CURRENT_TIMESTAMP(),
            1)";

        /* Display SQL Statements
        echo "S25: $sql25<br>S35: $sql35";
        */
        /* Update database */
        //include 'arnav/dblogin.cgi';
        $mysqli = cn2();

        $mysqli->autocommit(FALSE);
        if (!$mysqli->query($sql25)) {
            printf("Error[25]: %s\n", $mysqli->error);
            $mysqli->rollback();
        }
        if (!$mysqli->query($sql35)) {
            printf("Error[35]: %s\n", $mysqli->error);
            $mysqli->rollback();
        }
        $mysqli->commit();
        $mysqli->close();
        return 1;
    }
    /*
    +-------------------------------------------------------+
    | Method: DeleteDWG                                     |
    +-------------------------------------------------------+
    */
    function DeleteDWG()
    {

        global $loginname, $roleid, $projectid, $projectname;
        $dtime = date('Y-m-d H:i:s');
        $remark = 'Deleted by ' . $loginname;

        /* Validate user rights */
        if ($roleid > 69 || !$roleid) {
            $mc = "You do not have delete right for $projectname";
            return $mc;
        }

        $this->checkifexists();     // Get dwglist.id
        $this->GetDWGTitle();       // Get the Title
        $this->GetCurrentRevNo();   // Revision Number

        //include 'arnav/dblogin.cgi';
        $mysqli = cn2();

        $sql75 = "insert into dwghistory
                    (dwglist_id,
                    newrevno,
                    revno,
                    newdwglistver,
                    olddwglistver,
                    title,
                    remark,
                    scaleina1,
                    lastissuedrevno,
                    lastissueddt,
                    newstg,
                    newstage,
                    newstgreason,
                    newr0dt,
                    r0newdt,
                    r0reason,
                    loginname,
                    dtime,
                    active)
                values
                    ($this->ListID,
                    0,
                    '$this->CurrentRevNo',
                    0,
                    '-',
                    '$this->Title',
                    '$remark',
                    '-',
                    '-',
                    '0000-00-00',
                    0,
                    0,
                    '-',
                    0,
                    '0000-00-00',
                    '-',
                    '$loginname',
                    '$dtime',
                    0)";
        echo '<br>Q75: ' . $sql75;

        $sql95 =  "update dwglist set active = 0 where id = $this->ListID";
        echo '<br>Q95: ' . $sql95;

        $mysqli->autocommit(FALSE);
        $flag = 0;

        if (!$mysqli->query($sql75)) {
            //printf("<br>Error[75]: %s\n", $mysqli->error);
            $flag = 1;
        }
        if (!$mysqli->query($sql95)) {
            //printf("<br>Error[95]: %s\n", $mysqli->error);
            $flag = 1;
        }

        if ($flag < 1) {

            $mysqli->commit();
            $mysqli->close();
            return  TRUE;
        } else {

            $mysqli->rollback();
            $mysqli->close();
            return FALSE;
        }
    }
}
