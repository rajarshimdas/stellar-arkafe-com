<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 09-Feb-12					|
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

$mysqli = cn1();

$stageid        = $_GET["stageid"];
$stage          = $_GET["stage"];
$workgroupid    = $_GET["workgroupid"]; // department_id in database
// echo "stageid: ".$stageid;

// Get the stage array
$query = "select
            t1.id as stageid,
            t1.name as stage
        from
            projectstage as t1,
            projectstagetasks as t2
        where
            t1.id = t2.projectstage_id and
            t2.department_id = $workgroupid and
            t2.active = 1
        order by
            t1.stageno";
// echo "Q: ".$query;

$stack_stageid = 0; // to filter out duplicate values

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {

        // Filter out duplicate values
        if ($row[0] !== $stack_stageid) {

            $stack_stageid = $row[0];
            $stageX[] = array ("stageid" => $row[0], "stage" => $row[1]);
            
        }

    }
    $result->close();

}
$mysqli->close();
/*
 * Passing the stageid to the onChangeStage function
 * -------------------------------------------------
 * this.options[this.selectedIndex].value
 * or
 * $(this).val()
*/
?>
<select name="stageid" style="width: 100%" onchange="javascript:onChangeStage($(this).val());">
    <option value="<?php echo $stageid; ?>"><?php echo $stage; ?></option>
    <?php
    for ($i = 0; $i < count($stageX); $i++) {
        $sid = $stageX[$i]["stageid"];
        if ($sid !== $stageid) {
            echo '<option value="'.$sid.'">'.$stageX[$i]["stage"].'</option>';
        }
    }
    ?>
</select>
