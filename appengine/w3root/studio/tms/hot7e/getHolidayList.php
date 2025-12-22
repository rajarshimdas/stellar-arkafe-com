<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created: 25-Apr-2011                                  |
| Updated:                                              |
+-------------------------------------------------------+
*/

function getHolidayList(

    int $branch_id,     // 0 for all branch | not implemented
    object $mysqli      // DB 

): array {

    $h['YYYY-MM-DD'] = [
        'Holiday Name',
        'Saturday Flag'
    ];

    $query = 'select * from holidays';

    $result = $mysqli->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $h[$row['dt']] = [
            $row['holiday'],
            $row['saturday']
        ];
    }

    return $h;
}
