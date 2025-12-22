<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 1:04 PM 31-Jan-07				            |
| Updated On: 08-Feb-2008				                |
+-------------------------------------------------------+
| 1. (Session Validation) > Now in bootstrap.php        |
| 2. Parse the location bar information			        |
| 3. Common Functions					                |
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
|	Parse the Location bar information		            |
+-------------------------------------------------------+
| Get variables from the location bar  **OO*		    |
|	a  ->  Navigation path           __________|_	    |
|	a2 ->  Return header path    		            :-)	|
+-------------------------------------------------------+   
*/

if (isset($_GET["a"])) {
    $a = $_GET["a"];
} else {
    die("Routing information missing...");
}

$a = explode("x", $a);

/* Active Tab */
$activetab = "ho$a[0]e";

if (isset($a[1])) {
    $b = explode("-", $a[1]);
    $activemenu = $b[0];
    $activemenucontent = $a[1];
} else {
    /* Get the last active Menu from sessions */
    $activemenu         = $_SESSION["$a[0]"];
    $activemenucontent  = $_SESSION["$a[0]"];
}

/* Set this Active Menu as the hot Menu for future reference */
$_SESSION["$a[0]"] = $activemenu;

/*
+-------------------------------------------------------+
|	Common Functions				                    |
+-------------------------------------------------------+
*/

/* Error Handlers */
global $mc, $me;
$mc = NULL;
$me = NULL;


/* Active Tab highlighting */
function activeid($active1, $active2)
{
    if ($active1 === $active2) {
        echo 'class="activeTab"';
    }
}


/* Date Formatting */
function dateformat($str)
{
    if (($timestamp = strtotime($str)) === false) {
        return false;
    } else {
        return date('d-M-y', $timestamp);
    }
}

/* Compaire Two Dates */
function datecompaire($d1, $d2)
{

    /* Use checkdate to verify date [  checkdate(mm, dd, yyyy)  ] */
    $t1 = 0;
    $date1 = explode('-', $d1);
    $date2 = explode('-', $d2);
    if (checkdate($date1[1], $date1[2], $date1[0]) === false) $t1 = 1;
    if (checkdate($date2[1], $date2[2], $date2[0]) === false) $t1 = 1;

    /* Use strtotime to calculate the difference */
    if ($t1 < 1) {
        if (($dx = (int)((strtotime($d1) - strtotime($d2)) / 86400)) === false)
            echo "<br>Error in compairing dates";
    } else {
        echo "<br>Input date error";
    }

    /* result */
    if ($dx > 0) return true;
    else return false;
}
