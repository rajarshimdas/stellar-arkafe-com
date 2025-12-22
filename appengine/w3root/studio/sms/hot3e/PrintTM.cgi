<?php /*  
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: NA					|
| Updated On: 15-Jan-09					|
+-------------------------------------------------------+
| Show Transmittal					|
+-------------------------------------------------------+
| Update 15-Jan-2009					|
| Use transbody.php to generate preview. A single 	|
| template to display preview and final transmittals	|
| ensures consistency in display of preview and final	|
| outputs.						|
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

$mysqli = cn1();

if (!$mysqli) echo "MySQLi connection error";

/* Comming from the Tranmittal > View */
$tmid = $_GET['tmid'];

/* Hit from the Drawing List Summary > Transmittals */
if (!$tmid) {

    $projectid 	= $_GET['a'];
    $TransNo	= $_GET['b'];

    $sql = "select
                id
            from
                transmittals
            where
                project_id = $projectid and
                transno = $TransNo";
    //echo "SQL: $sql";

    if ($result = $mysqli->query($sql)) {

        $row = $result->fetch_row();
        $tmid = $row[0];
        $result->close();

    }

}

/* Get To and address */									
$sql = "select 
            project_id,
            transno,
            contact,
            company,
            address,
            sentmode,
            purpose,
            DATE_FORMAT(dtime,'%d-%b-%Y'),
            remark,
            loginname,
            startingsrno
        from
            transmittals
        where
            id = $tmid";									

if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_row()) {

        $projectid 	= $row[0];
        $transno	= $row[1];
        $contact 	= $row[2];
        $messers 	= $row[3];
        $address 	= $row[4];
        $sentmode 	= $row[5];
        $purpose 	= $row[6];
        $date 		= $row[7];
        $remark 	= $row[8];
        $created_by 	= $row[9];
        $startingSrNo   = $row[10];

    }
    $result->close();
} else {
    global $mc;
    $mc = "Error: $mysqli->error";
    $mysqli->close();
    return false;
}

/* Project info */
$sql = "select 
            t1.projectname,
            t1.jobcode,
            t2.corporatename,
            t2.description as address,
            t2.domainname
        from
            projects as t1,
            domain as t2
        where
            t1.id = $projectid and
            t1.domain_id = t2.id";

if ($result = $mysqli->query($sql)) {

    $row = $result->fetch_row();

    $projectname        = $row[0];
    $jobcode            = $row[1];
    $corporatename      = $row[2];
    $corporateaddress   = $row[3];
    $domainname         = $row[4];

    /* Rajarshi
    // Hack for demo site
    $corporatename = 'Company Name';
    $corporateaddress = '12 Orchid Street, IP Extn, Delhi - 110052';
    */
    
    $result->close();

} else {
    global $mc;
    $mc = "Error: $mysqli->error";
    $mysqli->close();
    return false;
}							

$sql =	"select company, address from transmittals where id = $tmid";

if ($result = $mysqli->query($sql)) {

    $row = $result->fetch_row();

    $messers = $row[0];
    $address = $row[1];

    $result->close();

} else {
    global $mc;
    $mc = "Error: $mysqli->error";
    $mysqli->close();
    return false;
}

/* Display the rows of the transmittal */
$sql = "select
            itemcode,
            item,
            nos,
            description,
            srno
        from
            translist
        where
            transmittal_id = $tmid
        order by
            srno";

if ($result = $mysqli->query($sql)) {

    while($row = $result->fetch_row()) {

        $itemsX[] = array(
                "item" 	=> $row[1],
                "nosX" 	=> $row[2],
                "desc" 	=> $row[3],
                "srno"	=> $row[4]
        );
    }

    $result->close();
} else {
    global $mc;
    $mc = "Error: $mysqli->error";
    return false;
}
$mysqli->close();

/*
+-------------------------------------------------------+
| Display the Transmittal				|
+-------------------------------------------------------+
*/
$imagepath = '../foo/images';
include 'transbody.php'; 

?>