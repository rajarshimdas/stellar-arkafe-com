<?php
$query = "select id, fullname from users where active = 1 order by fullname";
// echo "Q1: $query";
?>
<select name="tid" style="width:100%">
    <option value="0">-- Select Teammate --</option>
    <?php
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            echo '<option value="'.$row[0].'">'.$row[1].'</option>';
        }
        $result->close();
    }
    ?>
</select>