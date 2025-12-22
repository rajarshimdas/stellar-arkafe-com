<?php /*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created: 25-Apr-2011                                  |
| Updated: 10-Feb-2012                                  |
+-------------------------------------------------------+
*/

function getHoldidayBackground(
    $dayname,       // Sun, Mon etc
    $date,          // MySQL date format
    $holidayList,   // Holiday List Array
    $satmode        // Satmode for the user's branch
) {

    // echo "<br>Day: $this_dayname Date: $this_date holidayList: $holidayList satmode: $satmode";
    // Holidays
    $isHoliday = 0;    // 0 - No holiday | 1 - Holiday
    $holiday = "X";     // Name of Holiday

    // Sundays    
    if ($dayname == 'Sun') {
        $isHoliday = 1;
    } else {
        /*
        // Mon to Friday
        if (isset($holidayList)) {
            for ($e = 0; $e < count($holidayList); $e++) {
                // echo "<br>holidayList:".$holidayList[$e]["dt"]." this_date: ".$this_date;
                if ($holidayList[$e]["dt"] === $date) {
                    $isHoliday  = 1;
                    $holiday    = $holidayList[$e]["hday"];
                }
            }
        }
        */
        if (isset($holidayList[$date])) {
            $isHoliday = 1;
            $holiday = ($holidayList[$date][1] < 1) ? $holidayList[$date][0] : '<!-- Sat -->';
        }
    }

    // Formulate the return Array
    $formatX = array(
        "isHoliday" => $isHoliday,
        "holiday"   => $holiday
    );

    return $formatX;
}
