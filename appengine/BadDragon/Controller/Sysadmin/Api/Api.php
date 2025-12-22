<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jul-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

function bdGetHolidayList(string $dt, object $mysqli): ?array
{
    $holidays = NULL;

    $d = date_parse_from_format("Y-m-d", $dt);
    $year = $d["year"];

    $query = "select * from `holidays` 
                where `dt` >= '$year-01-01' and `dt` <= '$year-12-31' and `active` > 0 
                order by `dt` ASC";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $holidays[] = $row;
    }

    return $holidays;
}


function bdtabulateHolidays(array $h): string
{

    $tr = "";

    for ($i = 0; $i < count($h); $i++) {
        $tr = $tr . "<tr>
                <td>" . ($i + 1) . "</td>
                <td>" . bdDateMysql2Cal($h[$i]["dt"]) . "</td>
                <td style='border-right:0px;'>" . $h[$i]["holiday"] . "</td>
                <td style='width:40px;border-left:0px;'><img class='fa5button' src='" . BASE_URL . "da/fa5/delete.png' alt='delete' onclick='deleteHoliday(" . $h[$i]["id"] . ")'></td>
            </tr>";
    }

    return $tr;
}
