<table width="100%" style="background:#ccf255" cellspacing="2px" border="0">
    <tr>
        <td colspan="2" style="background:RGB(150,150,150);color:white;font-weight:bold;font-size:110%">
            Reset Approval Flags
        </td>
    </tr>

    <?php
    $rx     = $_GET["rx"];
    $m      = $_GET['m'];
    $fdt    = $_GET['fdt'];
    $tdt    = $_GET['tdt'];
    $uid    = $_GET['uid'];

    // Error
    $error = [
        "e1"    => "User not selected",
        "e2a"   => "Invalid From Date",
        "e2b"   => "Invalid To Date",
        "e3"    => "Could not save",
    ];

    include $w3etc . "/foo/uid2displayname.php";
    $thisUser = uid2displayname($uid, $mysqli);

    if ($rx == "s") {

        $status = "Success: ";
        $msg = "Timesheet approval flags for " . $thisUser . " are removed from date " . $fdt . " to date " . $tdt.". <br>You may need to set timsheet lock for allowing timesheet editing.";
    } else {
        $status = "Error: ";
        $msg = $error[$_GET["m"]] . ". Please try again...";
    }

    ?>
    <tr>
        <td style="width: 150px; text-align: right;">
            <?= $status ?>
        </td>
        <td>
            <?= $msg ?>
        </td>
    </tr>
</table>