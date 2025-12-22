<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007				                |
| Updated On: 14-Feb-2012 / 21-Oct-2023                 |
+-------------------------------------------------------+
*/

class transmittal
{

    public $ProjID;                // Project Id
    public $TransmittalID;         // Current transmittal.id
    public $sessionid;             // SessionID
    public $mysqli;                // DB connection
    public $fullname;              // Name of person creating the transmittal
    public $a;                     // Redirect URL
    public $tmid;                  // Transmittal id
    /*
    +-------------------------------------------------------+
    | Constructor: create a new transmittal object          |
    +-------------------------------------------------------+
    */
    public function __construct($projectid)
    {
        if ($projectid < 1 || !$projectid) {
            $this->go2("System failed to identify the project.");
        }

        $this->ProjID       = $projectid;

        return true;
    }

    /*
    +-------------------------------------------------------+
    | Method :: go2                                         |
    +-------------------------------------------------------+
    */
    public function go2($mc)
    {
        die("Error: " . $mc);
    }
}
/*
+-------------------------------------------------------+
| Sub-class :: CreateTMList                             |
+-------------------------------------------------------+
*/
class CreateTransmittal extends transmittal
{

    function __construct($projectid, $sessionid)
    {
        parent::__construct($projectid);
        $this->sessionid    = $sessionid;
    }

    public function SaveHeaderInfo($sessionid)
    {

        $to = $_POST['to'];         //echo "<br>To: $to";
        $by = $_POST['by'];         //echo "<br>by: $by";
        $rm = $_POST['remark'];     //echo "<br>rm: $rm";

        /* Purpose check box */
        $ck1 = $_POST['ck1'];       // For Approval/Comments
        $ck2 = 0;                   // $_POST['ck2']; Per your request - not in use
        $ck3 = $_POST['ck3'];       // For Information
        $ck4 = 0;                   // $_POST['ck4']; For Co-ordination - not in use
        $ck5 = 0;                   // $_POST['ck5']; Advance Copy - not in use
        $ck6 = $_POST['ck6'];       // Good For construction
        $ck7 = $_POST['ck7'];       // Originals
        $ck8 = $_POST['ck8'];       // Prints
        $ck9 = $_POST['ck9'];       // Soft Copy
        $ck10 = $_POST['ck10'];     // Tenders
        $ck11 = $_POST['ck11'];     // Sanction Drawings
        $ck12 = $_POST['ck12'];     // Shop Drawings

        // Starting Item Serial No. Updated 03-Jan-2013
        $srno = trim($_POST['srno']) + 0;

        // Data Validation
        if ($to === "-- Select Name --" || $by === "-- Sent by --") {
            $mc = "Incomplete or Invalid data input";
            header("location:project.cgi?a=t3xcreate");
            die;
        }

        global $roleid, $projectname;
        if ($roleid > 60) {
            $mc = "You do not have Transmittal Creation/Editing rights for $projectname";
            header("location:project.cgi?a=t3xcreate&mc=$mc");
            die;
        }

        // Generate the purpose data in publicchar fromat
        $purpose = "A";
        for ($i = 1; $i <= 12; $i++) {
            if ($_POST["ck$i"] === "on")
                $purpose = "$purpose-1";
            else
                $purpose = "$purpose-0";
        }

        // Validate srno
        if (is_int($srno)) {
            $startingItemSrNo = $srno;
        } else {
            $mc = "Starting Item Serial Number was not an integer.";
            header("location:project.cgi?a=t3xcreate&mc=$mc");
            die;
        }

        // Store Data
        $sql = "insert into tmheader
                    (sessionid, wizardstepno, contact, sentmode, purpose, remark, dtime, active, startingsrno)
                values
                    ('$sessionid',2,'$to','$by','$purpose','$rm',CURRENT_TIMESTAMP(),1,$startingItemSrNo)";
        /* echo "<br>SQL: $sql";  */

        include 'foo/arnav/dblogin.cgi';
        if (!$mysqli->query($sql)) {
            echo "Error: $mysqli->error";
            $mysqli->rollback();
            $mysqli->close();
            return false;
        }

        // Stored data tmid
        $tmid = $mysqli->insert_id;

        $mysqli->close();

        return $tmid;
    }
}
/*
+-------------------------------------------------------+
| Sub-class :: CreateTMList                             |
+-------------------------------------------------------+
*/
class CreateTMList extends transmittal
{

    public $srno;
    public $itemcode;

    function __construct($projectid)
    {
        parent::__construct($projectid);
    }

    function AddItem2List($tmid, $item, $nos2, $desc)
    {

        //echo "<br>Item: $item<br>Nos: $nos2<br>Desc: $desc";

        /* Data validation */
        if (!$item || !$nos2 || !$desc) {
            global $mc;
            $mc = "Invalid data at input";
            return false;
        }
        if ($nos2 > 0 && $nos2 < 100) {
            $dosomething = 1;
        } else {
            global $mc;
            $mc = "Invalid data at input";
            return false;
        }

        /* Update tmlist */
        include('foo/arnav/dblogin.cgi');

        /* No of items in the list
        $sql = "select srno from tmlist where tmheader_id=$tmid and active=1";
        if ($result = $mysqli->query($sql)) {
            $row_cnt = $result->num_rows;
            $result->close();
        } else {
            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }
        if ($row_cnt >= 12) {
            global $mc;
            $mc = "Maximum 12 rows for a transmittal";
            $mysqli->close();
            return false;
        }
        */
        $this->Getsrnoanditemcode($tmid, $item);

        /* Insert into tmlist */
        if ($item === "others") $item = "&nbsp;";
        //$sql="insert into tmlist values($tmid,$srno,$itemid,'$item',$nos2,'$desc',1)";
        $sql = "insert into tmlist values($tmid, $this->srno, $this->itemcode,'$item',$nos2,'$desc',1)";
        if (!$mysqli->query($sql)) {
            global $mc;
            $mc = "Error: $mysqli->error<br>$sql";
            $mysqli->close();
            return false;
        }
        $mysqli->close();

        return true;
    }

    function RemoveFromList($tmid, $srno)
    {

        $sql = "update tmlist set active = 0 where tmheader_id = $tmid and srno = $srno";
        // echo 'Q: '.$sql; die;

        include('foo/arnav/dblogin.cgi');
        if (!$mysqli->query($sql)) {
            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }
        $mysqli->close();
    }

    function Getsrnoanditemcode($tmid, $item)
    {

        include('foo/arnav/dblogin.cgi');
        /* Get Itemcode */
        $sql = "select id from transitems where item = '$item'";
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_row()) {
                $itemid = $row[0];
            }
            $result->close();
        } else {
            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }
        $this->itemcode = $itemid;

        /* Get the srno */
        $srno = 1;
        $sql = "select srno from tmlist where tmheader_id = $tmid";
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_row()) {
                $srno = $row[0];
                $srno++;
            }
            $result->close();
        } else {
            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }
        $this->srno = $srno;
        $mysqli->close();
    }


    function AddDWG2List($tmid, $sheetno, $revno, $title, $nosX)
    {

        if (!$tmid || !$sheetno || !$revno || !$nosX) {
            global $mc;
            $mc = "Invalid data at input.";
            return false;
        }
        $this->Getsrnoanditemcode($tmid, "Drawings");

        /* insert row to the tmlist */
        $drawingNo = $sheetno . ' #' . $revno;
        $item = strtoupper($drawingNo);

        $sql = "insert into tmlist
                    (tmheader_id, srno, itemcode, item, nos, description, active)
                values
                    ($tmid,$this->srno, $this->itemcode, '$item', $nosX, '$title', 1)";

        include('foo/arnav/dblogin.cgi');
        if (!$mysqli->query($sql)) {
            global $mc;
            $mc = "$mysqli->error<br>$sql;";
            $mysqli->close();
            return false;
        }
        $mysqli->close();

        return true;
    }
}
/*
+-------------------------------------------------------+
| Sub-class :: ShowTransmittal                          |
+-------------------------------------------------------+
*/
class ShowTransmittal extends transmittal
{

    /* Properties */
    public $tmid;

    /* Instantiate */
    function __construct($projectid)
    {
        parent::__construct($projectid);
    }

    /* Methods */
    function CommitTransmittalForm($tmid)
    {
        //echo "Display commit transmittal form";
        $this->tmid = $tmid;
?>
        <script type="text/javascript">
            window.onload = function() {
                document.getElementById('newversion').focus();
            };
        </script>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0">
            <form action="execute.cgi" method="POST">
                <input type="hidden" name="a" value="t3xcreate-3a">
                <input type="hidden" name="sx" <?php echo 'value="' . $this->sessionid . '"'; ?>>
                <input type="hidden" name="tmid" value="<?php echo $this->tmid; ?>">
                <tr class="inputForm">

                    <td align="center" valign="top" width="30%">
                        Create :: Transmittals
                    </td>
                    <td align="center" width="5%" valign="top">
                        <br><img src="/da/icons/32/info.png" alt="Info">
                    </td>
                    <td align="left" valign="top">
                        <table width="90%" border="0">
                            <tr>
                                <td>
                                    Commit Transmittal !!
                                </td>
                            </tr>
                            <tr>
                                <td class="notes" align="justify">
                                    Transmittals cannot be edited after creation.
                                    Check transmittal displayed below before hitting the create button.
                                    If you need editing hit the Back button.
                                    Hit Cancel to exit without commiting this transmittal.
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <input type="submit" name="submit" value="<< Back" style="width:120px;">
                                    <input type="submit" name="submit" value="Create" style="width:120px;">
                                    <input type="submit" name="submit" value="Cancel" style="width:120px;">
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
            </form>
        </table>
<?php
    }


    function TabulateTransmittal($tmid)
    {

        echo "<div>Transmittal Preview</div>";

        if ($tmid < 0) die("Transmittal id not found.");

        //include('foo/arnav/angels.cgi');
        $mysqli = cn1();
        /*
        +-------------------------------------------------------+
        | Update 15-Jan-2009					                |
        | Use transbody.php to generate preview. A single 	    |
        | template to display preview and final transmittals	|
        | ensures consistency in display of preview and final	|
        | outputs.						                        |
        +-------------------------------------------------------+
        | $tmid		Transmittal ID                              |
        | $projectid 	Project ID				                |
        | $transno	Transmittal number                          |
        | $contact						                        |
        | $messers						                        |
        | $address						                        |
        | $sentmode						                        |
        | $purpose						                        |
        | $date		Transmittal created on (pre-formatted)	    |
        | $remark						                        |
        | $loginname						                    |
        | $projectname						                    |
        | $jobcode						                        |
        | $itemsX[]	Content List Array                          |
        | $company[]	Company Info array from config.php	    |
        | $imagepath	Path to the images folder		        |
        +-------------------------------------------------------+
        */
        global
            $tmid,
            $projectid,
            $contact,
            $messers,
            $address,
            $sentmode,
            $purpose,
            $date,
            $remark,
            $projectname,
            $jobcode,
            $itemsX,
            $startingSrNo;
        

        /* Get To and address */
        $sql = "select contact,purpose,sentmode,remark,startingsrno from tmheader where id = $tmid";
        // die($sql);

        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_row()) {
                $contact        = $row[0];
                $purpose        = $row[1];
                $sentmode       = $row[2];
                $remark         = $row[3];
                $startingSrNo   = $row[4];
            }
            $result->close();
        } else {
            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }

        $sql =    "select
                    company,
                    dooradd,
                    streetadd,
                    city,
                    pincode
                    from
                    transadd
                where
                    id 
                in
                    (select 
                        transadd_id 
                    from 
                        transname 
                    where 
                        project_id = $this->ProjID and 
                        contact = '$contact')";

        // die($sql);

        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_row()) {
                $messers = $row[0];
                //$address = "$row[1], $row[2], $row[3]";
                $address = $row[3];
            }
            $result->close();
        } else {
            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }


        /* Display the rows of the transmittal */
        $sql =    "select
                    itemcode,
                    item,
                    nos,
                    description,
                    srno
                from
                    tmlist
                where
                    tmheader_id = $tmid and
                    active = 1
                order by
                    srno";

        $i = 1;
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_row()) {

                $srno = $startingSrNo + $row[4];

                $itemsX[] = array(
                    "item"     => $row[1],
                    "nosX"     => $row[2],
                    "desc"     => $row[3],
                    "srno"    => $srno
                );
            }
            $result->close();
        } else {
            global $mc;
            $mc = "Error: $mysqli->error";
            return false;
        }

        $mysqli->close();
    }
}
/*
+-------------------------------------------------------+
| Sub-class :: CommitTM                                 |
+-------------------------------------------------------+
*/
class CommitTM extends transmittal
{

    /* Instantiate */
    function __construct($projectid, $tmid, $fullname)
    {
        parent::__construct($projectid);
        $this->tmid         = $tmid;
        $this->fullname     = $fullname;
    }

    function SaveTM()
    {

        /* Connect to database with select, insert and update privileges */
        $mysqli = cn2();

        /* Check if the tmheader info is already commited */
        $sql = "select 1 from tmheader where id = $this->tmid and active = 1";
        // die($sql);

        if ($result = $mysqli->query($sql)) {

            if ($result->num_rows < 1) {
                global $mc;
                $mc = "Transmittal is already saved.";
                $mysqli->close();
                return false;
            }

            $result->close();
        } else {
            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }

        /* Collect data for transmittals table */
        $sql =    "select
                    contact,
                    sentmode,
                    purpose,
                    remark,
                    startingsrno
                from
                    tmheader
                where
                    id = $this->tmid";

        //die($sql);

        if ($result = $mysqli->query($sql)) {

            $row = $result->fetch_row();

            $contact        = $row[0];
            $sentmode       = $row[1];
            $purpose        = $row[2];
            $remark         = $row[3];
            $startingSrNo   = $row[4];

            $result->close();
        } else {

            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }

        /* Collect details of the sent to contact */
        $sql =    "select
                    company,
                    dooradd,
                    streetadd,
                    city,
                    pincode
                from
                    transadd
                where
                    id
                in
                    (select transadd_id from transname where project_id = $this->ProjID and contact = '$contact')";

        if ($result = $mysqli->query($sql)) {

            while ($row = $result->fetch_row()) {

                $company = $row[0];
                $address = $row[1] . ', ' . $row[2] . ', ' . $row[3];
            }
            $result->close();
        } else {

            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }
        // die($address."|".$company);

        /* Transmittal Number */
        $transno = 1; //First transmittal
        $sql = "select
                    transno
                from
                    transmittals
                where
                    project_id = $this->ProjID
                order by
                    transno DESC
                limit
                    1";

        //echo "<br>Transno sql: $sql";
        if ($result = $mysqli->query($sql)) {

            if ($row = $result->fetch_row()) {
                $transno = $row[0];
                $transno++; // Next number
            }
            $result->close();
        } else {

            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }
        //echo "<br>Transmittal No: $transno";

        /* Transmittal list Data */
        $sql =  "select
                    item,
                    nos,
                    description,
                    itemcode
                from
                    tmlist
                where
                    tmheader_id = $this->tmid and
                    active = 1
                order by
                    srno";

        if ($result = $mysqli->query($sql)) {

            while ($row = $result->fetch_row()) {
                $tmX[] = array("item" => $row[0], "nos" => $row[1], "des" => $row[2], "itemcode" => $row[3]);
            }
            $result->close();
        } else {

            global $mc;
            $mc = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }
        $count = sizeof($tmX); //echo "<br>Array: $count";

        if ($count < 1) {

            global $mc;
            $mc = "Transmittal List was empty. Cannot create an empty Transmittal.<br>Please Retry...";
            $mysqli->close();
            return false;
        }

        /* Insert data into the tables */
        $mysqli->autocommit(FALSE);

        $sql = "insert into
                    transmittals
                        (project_id,transno,contact,company,address,sentmode,purpose,dtime,remark,loginname,active,startingSrNo)
                    values
                        ('$this->ProjID',
                        '$transno',
                        '$contact',
                        '$company',
                        '$address',
                        '$sentmode',
                        '$purpose',
                        CURRENT_TIMESTAMP(),
                        '$remark',
                        '$this->fullname',
                        1,
                        $startingSrNo)";
        //die($sql);

        if (!$mysqli->query($sql)) {
            global $mc;
            $mc = "Error: $mysqli->error<br>$sql";
            $mysqli->rollback();
            $mysqli->close();
            return false;
        }

        $transid = $mysqli->insert_id;
        $this->TransmittalID = $transid;

        /* Loop to enter transmittal items */
        for ($i = 0; $i < count($tmX); $i++) {

            $item       = $tmX[$i]["item"];
            $nos        = $tmX[$i]["nos"];
            $des        = $tmX[$i]["des"];
            $itemcode   = $tmX[$i]["itemcode"];
            $no         = $i + 1;
            //echo "<br>$no item: $item nos: $nos des: $des";

            /* Insert transmittal item info into the translist table */
            $sql = "insert into translist
                        (transmittal_id, srno, itemcode, item, nos, description)
                    values
			($transid, $no, '$itemcode', '$item', '$nos', '$des')";

            if (!$mysqli->query($sql)) {

                global $mc;
                $mc = "Error: $mysqli->error<br>$sql";
                $mysqli->rollback();
                $mysqli->close();
                return false;
            }

            /* if the item is a drawing - Update dwglist and Register in the dwghistory tables */
            if ($itemcode == 10) {

                $x = explode("#", $item);
                $sheetno = trim($x[0]);

                /* Explode the sheetno into component parts */
                $ex = explode("-", $sheetno);

                // Get SheetNo
                $DWGid      = $ex[0];
                $DWGdc      = $ex[1];
                $DWGunit    = $ex[2];
                $DWGpart    = $ex[3];

                // Get Revision No
                $DWGrevno   = trim($x[1]);

                /*
                // Check if the specified revno is a GFC revno or a Schematic revno
                $sql = "select 1 from haystack where f4gfc = '$DWGrevno'";

                if ($result = $mysqli->query($sql)) {

                    if ($result->num_rows < 1) {
                        $revno_type = 0; // Schematic revno
                    } else {
                        $revno_type = 1; // GFC revno
                    }

                    $result->close();
                } else {

                    global $mc;
                    $mc = "Error: $mysqli->error";
                    $mysqli->close();
                    $mysqli->rollback();
                    return false;
                }
                */
                $revno_type = (is_gfc_revision($DWGrevno)) ? 1 : 0;

                /* Collect current drawing details from the dwglist table */
                $sql = "select
                            id,
                            currentrevno,
                            newr0targetdt,
                            r0issuedflag,
                            lastissuedrevno,
                            lastissueddate,
                            stageclosed,
                            r0issuedt
                        from
                            dwglist
                        where
                            project_id = $this->ProjID and
                            dwgidentity = '$DWGid' and
                            disciplinecode = '$DWGdc' and
                            unit = '$DWGunit' and
                            part = '$DWGpart'";
                // die($sql);

                if ($result = $mysqli->query($sql)) {

                    if ($row = $result->fetch_row()) {

                        $dwglist_id         = $row[0];
                        $currentrevno         = $row[1];
                        $newr0targetdt        = $row[2];
                        $r0issuedflag         = $row[3];
                        $lastissuedrevno     = $row[4];
                        $lastissueddate     = $row[5];
                        $aissuedflag        = $row[6]; // alias for stageclosed
                        $anewtargetdt        = $row[7]; // alias for r0issuedt

                    }
                    $result->close();
                } else {

                    global $mc;
                    $mc = "Error: $mysqli->error";
                    $mysqli->close();
                    $mysqli->rollback();
                    return false;
                }

                /* Update the dwglist */
                $date = date('Y-m-d');
                // Set the schematic issued flag
                if ($revno_type < 1 && $aissuedflag < 1) {
                    // Schematic drawing being sent for the first time
                    $aissuedflag = 1;
                    $anewtargetdt = $date;
                }


                // If the revision number is R0 and R0 has not been issued before
                if ($DWGrevno === "R0" && $r0issuedflag < 1) {

                    // R0 is being issued for the first time
                    $r0issuedflag = "1, r0issuedt = '" . $date . "'";
                    $newr0targetdt = date('Y-m-d');
                }

                $sql =  "update
                            dwglist
                        set
                            currentrevno 	= '$DWGrevno',
                            stageclosed		= $aissuedflag,
                            r0issuedt		= '$anewtargetdt',
                            r0issuedflag 	= $r0issuedflag,
                            lastissuedrevno     = '$DWGrevno',
                            lastissueddate 	= CURRENT_DATE()
                        where
                            id = $dwglist_id";

                if (!$mysqli->query($sql)) {

                    global $mc;
                    $mc = "Error: $mysqli->error<br>$sql";
                    $mysqli->rollback();
                    $mysqli->close();
                    return false;
                }

                /* Register transaction into the dwghistory table */

                // Set the newrevno flag
                if ($DWGrevno === $currentrevno) $newrevno = 0;
                else $newrevno = 1;

                //
                if ($DWGrevno === "R0" && $r0issuedflag === 1) {
                    $newr0dt = 1;
                    $r0reason = 'R0 sent for the first time';
                } else {
                    $newr0dt = 0;
                    $r0reason = 'R0 resending';
                }

                //
                if ($DWGrevno !== "R0") {
                    $newr0dt = 0;
                    $r0reason = '';
                }

                /* remark column of the dwghistory - Used for publicious reports */
                $rm = "ProjID:" . $this->ProjID . "+TmNO:" . $transno . "+SentTo:$contact";

                $sql = "insert into
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
                        values
                                ($dwglist_id,
                                '$newrevno',
                                '$DWGrevno',
                                0,
                                '',
                                '',
                                '$rm',
                                'Transmittal',
                                '$lastissuedrevno',
                                '$lastissueddate',
                                0,
                                0,
                                '',
                                '$newr0dt',
                                CURRENT_DATE(),
                                '$r0reason',
                                'todo',
                                CURRENT_TIMESTAMP(),
                                1)";

                if (!$mysqli->query($sql)) {
                    global $mc;
                    $mc = "Error: $mysqli->error<br>$sql";
                    $mysqli->rollback();
                    $mysqli->close();
                    return false;
                }
            }
        }

        /* Set the tmheader info as stale */
        $sql = "update tmheader set active = 0 where id = $this->tmid";
        if (!$mysqli->query($sql)) {
            global $mc;
            $mc = "Error: $mysqli->error<br>$sql";
        }

        $mysqli->commit();
        $mysqli->close();
        return true;
    }
}

?>