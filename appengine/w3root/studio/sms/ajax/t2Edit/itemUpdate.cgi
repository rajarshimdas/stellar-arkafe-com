<?php

$itemId         = $_GET["itemId"];
$newTitle       = $_GET["newTitle"];
$newStageNo     = $_GET["newStageNo"];
$newTdt         = empty($_GET["newTdt"]) ? '0000-00-00' : $_GET["newTdt"];
$oldTdt         = empty($_GET["oldTdt"]) ? '0000-00-00' : $_GET['oldTdt'];

// echo 'newTdt: '.$newTdt.'<br>oldTdt: '.$oldTdt; die;

if ($newTdt !== $oldTdt && $newTdt !== '0000-00-00') {
    $dtQuery = ", newr0targetdt = '$newTdt',";
} else {
    $dtQuery = ',';
}

$query = "update 
            dwglist
        set
            title = '$newTitle' $dtQuery  newstage = $newStageNo
        where
            id = '$itemId' and
            project_id = '$project_id'";
// die("Q: " . $query);

if (!$mysqli->query($query)) {

    // Error
    rdReturnJsonHttpResponse(
        '200',
        // ["F", $query],
        ["F", "Failed to edit"],
    );
}

// Return on Success:
// Set the new target date
$query = "select newr0targetdt from dwglist where id = '$itemId'";

if ($result = $mysqli->query($query)) {
    if ($row = $result->fetch_row()) {
        $itemTdt = $row[0];
    }
    $result->close();
}

// die($itemTdt);

if ($itemTdt !== '0000-00-00') {

    // Item has a target date
    $itemTdtCal = bdDateMysql2Cal($itemTdt);
} else {

    // Item does not have a target date override. Use stage target date.
    include 'foo/sms/projectSchedule.php';
    if ($tX = getStageNoTdtArray($project_id, $mysqli)) {
        $itemTdt = $tX[$newStageNo];
        $itemTdtCal = empty($itemTdt) ? "" : bdDateMysql2Cal($itemTdt);
    } else {
        $itemTdt = '<!-- Nil -->';
        $itemTdtCal = '&nbsp;';
    }
}

/* Return */
//echo $itemTdt;
rdReturnJsonHttpResponse(
    '200',
    ["T", $itemTdt, $itemTdtCal]
);
