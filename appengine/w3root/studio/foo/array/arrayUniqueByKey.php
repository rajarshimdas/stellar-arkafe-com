<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 27-Jan-09                                 |
| Updated On: 17-Feb-11                        		|
+-------------------------------------------------------+
*/

/* Update Log
+-------------------------------------------------------+
| 17-Feb-2011   Made generic. Can be used for any       |
|               multi-dimensional array 		|
+-------------------------------------------------------+
*/

function arrayUniqueByKey ($myArray, $key) {

    $arrayCount     = count($myArray);      // Total no of rows in the array
        $newArray       = array();          // Temporary array to hold array for compairision

    for ($i = 0; $i < $arrayCount; $i++) {

        // Found in new array Flag
        $foundFlag = 0;

        // This Key
        $thisKEY = $myArray[$i][$key];

        // Find in the newArray
        for ($e = 0; $e <= count($newArray); $e++) {

            if ($thisKEY === $newArray[$e][$key]) {
                $foundFlag = 1;
            }

        }

        // Check Status and do the needful
        if ($foundFlag < 1) {
            // New row - save in new array
            $newArray[] = array($key => $thisKEY);            
        } else {
            unset($myArray[$i]);
        }
    }
    
    return $myArray;
}

?>
