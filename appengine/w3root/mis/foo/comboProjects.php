<?php /* comboProjects.php */ ?>
<select name="pid" style="width:100%">
    <option value="0">-- Select Project --</option>
    <?php
    $query = "select id, projectname, jobcode from projects where id >= 500 and active = 1 order by jobcode";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            echo '<option value="' . $row[0] . '">'. $row[2].' - ' . $row[1] . '</option>';
        }
        $result->close();
    }
    ?>
</select>