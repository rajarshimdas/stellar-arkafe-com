<?php /* comboProjects.php */ ?>
<select name="pid" style="width:100%">
    <option value="0">-- Select Milestone --</option>
    <?php
    $query = "select id, name from projectstage where stageno > 0 and active = 1 order by stageno";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            echo '<option value="'.$row[0].'">'.$row[1].'</option>';
        }
        $result->close();
    }
    ?>
</select>
