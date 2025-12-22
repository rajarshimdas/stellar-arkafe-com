<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 17-Feb-2011                               |
| Updated On:                                           |
+-------------------------------------------------------+
*/
function sortArrayByKey ($myArray, $sortkey){

    foreach ($myArray as $key => $row) {
        $sortby[$key] = $row[$sortkey];
    }

    array_multisort($myArray, SORT_ASC, $sortby);

    return $myArray;

}
?>
