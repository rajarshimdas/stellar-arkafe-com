<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 17-Aug-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
| Timesheet :: Delete                                	|
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| Updated On: 14-Aug-09                                 |
+-------------------------------------------------------+
| Workaround: To make timesheet module to work using   	|
| using this same code                                  |
+-------------------------------------------------------+
| Debugging Tip                                         |
| Try to get the code working in SMS and then put the   |
| workaround to make it work thourgh TimeSheets         |
+-------------------------------------------------------+
*/

$tsid                     = $_GET["tsid"];
$display_no_of_days     = $_GET["no"];
$mysqli                 = cn1();

// Get all data regarding this timesheet
include 'hot7e/tsid2data.cgi';

?>
<table width="100%" style="border:0;background:#FFF6F4;border-spacing:4px;">

    <tr>
        <td style="text-align:center;" colspan="2">
            <?php echo "<span style='font-weight: bold;'>" . $timesheetX["projectname"];
            //."</span><br>".$timesheetX["projectstage_name"].' | '.$timesheetX["task"]; 
            ?>
        </td>
    </tr>
    <tr>
        <td style="width: 40%;text-align:right;">Worked from:</td>
        <td>
            <?php
            $a = [
                '10' => 'Office',
                '20' => 'Remote Location',
            ];
            echo $a[$timesheetX["worked_from"]];
            ?>
        </td>
    </tr>
    <tr>
        <td style="width: 40%;text-align:right;">Date:</td>
        <td><?= $timesheetX["date"] ?></td>
    </tr>
    <tr>
        <td style="width: 40%;text-align:right;">Time:</td>
        <td><?= $timesheetX["no_of_hours"] . ':' . $timesheetX['no_of_min'] ?></td>
    </tr>
    <tr>
        <td style="width: 40%;text-align:right;">Milestone:</td>
        <td><?= $timesheetX["projectstage_name"] ?></td>
    </tr>
    <tr>
        <td style="width: 40%;text-align:right; vertical-align:top;">Work:</td>
        <td>
            <textarea style="width:404px;height:100px;border:0px" readonly><?php echo $timesheetX["work"]; ?></textarea>
        </td>
    </tr>


    <form action="execute.cgi" method="POST">
        <input type="hidden" name="a" value="timesheet-del-action">
        <input type="hidden" name="sx" value="<?php echo $sessionid; ?>">
        <input type="hidden" name="tsid" value="<?php echo $tsid; ?>">
        <?php if ($display_no_of_days) echo "<input type='hidden' name='no' value='$display_no_of_days'>"; ?>

        <tr>
            <td></td>
            <td>
                <input type="submit" name="go" value="Delete" style="width:60px">
                <input type="submit" name="go" value="Cancel" style="width:60px">
            </td>
        </tr>
    </form>


</table>
<?php // var_dump($timesheetX);
