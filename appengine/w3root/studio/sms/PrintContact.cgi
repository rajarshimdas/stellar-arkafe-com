<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 1-Jan-2007				                |
| Updated On: 14-Feb-2011				                |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';
require_once 'foo/commonFunctions.cgi';


$tid = $_GET["id"];

$query = 'select 
            t1.contact as name,
            t1.phoneno,
            t1.email,
            t2.company,
            t2.dooradd,
            t2.locality,
            t2.city,
            t2.statecountry,
            t2.pincode,
            t2.website
        from
            transname as t1,
            transadd as t2
        where
            t1.id = '.$tid.' and
            t1.project_id = '.$project_id.' and
            t1.transadd_id = t2.id';

if ($result = $mysqli->query($query)) {

    $row = $result->fetch_row();

    $contactName    = $row[0];
    $phoneno        = $row[1];
    $email          = $row[2];

    // Address
    $address = '<span style="font-weight: bold">'.$row[3].'</span>';
    if ($row[4]) $address = $address.'<br>'.$row[4]; // dooraddress
    if ($row[5]) $address = $address.',<br>'.$row[5]; //locality
    if ($row[6]) $address = $address.',<br>'.$row[6]; // city
    if ($row[7]) $address = $address.',<br>'.$row[7]; // statecountry
    if ($row[8]) $address = $address.',<br>Pincode: '.$row[8].'.'; // pincode

    $website = $row[9];

    $result->close();
}

?><!DOCTYPE html">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
        <link rel="icon" href="../favicon.ico" type="image/x-icon">
        <script type='text/javascript' src='/matchbox/jquery/jquery.js'></script>
        <link type='text/css' rel='stylesheet' href='/matchbox/themes/cool/style.css'>
        <?php echo "<title>Concert :: $projectname</title>"; ?>
    </head>
    <body id="body">
        <div align="left">
            <?php
            echo '&nbsp;<br>'.$contactName.'<br>&nbsp;<br>'.$address;
            if ($phoneno) echo '<br>&nbsp;<br>Phone: '.$phoneno;
            ?>
        </div>
    </body>
</html>
