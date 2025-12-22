<?php
$userId = $_POST["userId"];

$query = "select 
            * 
        from 
            view_timesheets 
        where
            user_id = '$userId' and
            project_id > 1 and
            project_id < 10
        order by
            dtmysql desc";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $rx[] = $row;
}

$co = isset($rx) ? count($rx) : 0;

if ($co > 0) {
    $t = "";
    for ($i = 0; $i < $co; $i++) {
        $t = $t . "<tr class='dataRow'>
            <td class='dataRowCell1'>" . ($i + 1) . "</td>
            <td class='dataRowCell2'>" . $rx[$i]['date'] . "</td>
            <td class='dataRowCell2' style='border-right:0px;'>" . $rx[$i]['projectname'] . "</td>
            <td class='dataRowCell2' style='border-left:0px;text-align:center;'>
                <img src='" . BASE_URL . "da/fa5/delete.png' onclick='deleteLeave(\"" . $rx[$i]['timesheet_id'] . "\")'
            </td>
            </tr>";
    }
} else {
    $t = '<!-- Empty -->';
}

rdReturnJsonHttpResponse(
    '200',
    ['T', $t]
);
