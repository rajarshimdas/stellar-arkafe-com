<?php /*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 12-Aug-2011                               |
| Updated On:                                           |
+-------------------------------------------------------+
*/
// Calander Date to MySQL date format
function calenderdate2MySQLdt($date) {
    
    $monthX = array(
            "Jan" => "01",
            "Feb" => "02",
            "Mar" => "03",
            "Apr" => "04",
            "May" => "05",
            "Jun" => "06",
            "Jul" => "07",
            "Aug" => "08",
            "Sep" => "09",
            "Oct" => "10",
            "Nov" => "11",
            "Dec" => "12"
    );
    
    $a = explode("-", $date);
    
    $year   = "20".$a[2];
    $month  = $monthX[$a[1]];
    $day    = $a[0];
    
    $mysqldt = $year."-".$month."-".$day;
    
    return $mysqldt;

}




?>