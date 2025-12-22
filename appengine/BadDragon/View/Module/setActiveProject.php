<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 08-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$projx = bdGetUsersProjects($uid, $mysqli);

?>

<table style="color: white; width: 100%;">

    <!-- Project -->
    <tr>
        <td style="padding: 0 15px;">
            <select id="pid" name="pid" onchange="javascript:setActiveProject();">

                <?php

                if ($pid > 0) {
                    $pname = bdProjectId2Name(($pid + 0), $mysqli);
                    echo '<option value="' . $pid . '">Project: ' . $pname . '</option>';
                } else {
                    echo "<option value='0'>-- Select Project --</option>";
                }
                
                for ($i = 0; $i < count($projx); $i++) {
                    if ($projx[$i]["id"] == $pid) {
                        $jobcode = $projx[$i]["jobcode"];
                    } else {
                        echo '<option value="' . $projx[$i]["id"] . '">' . $projx[$i]["pn"] . '</option>';
                    }
                }
                ?>

            </select>
        </td>
    </tr>
</table>