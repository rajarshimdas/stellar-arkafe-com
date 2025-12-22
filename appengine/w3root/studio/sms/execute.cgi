<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 04-Feb-2008				                |
| Updated On: 02-Jul-2012                               |
+-------------------------------------------------------+
| Form Action execute - Authentication & Functions	    |
+-------------------------------------------------------+
| POST method to send data from client to server.	    |
+-------------------------------------------------------+
| Instead of using rajarshi.cgi all form action to be	|
| performed by this program. The rajarshi.cgi used to	|
| send output to the browser causing the php warning:	|
| header already sent... To avoid this error, I wrote	|
| this program which does not send any output and hence	|
| after execution of desired functions, header can be	|
| sent without any warning messages...			        |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';

// Get Variables
if (isset($_GET["a"])) {
    $a = $_GET["a"];
} elseif (isset($_POST["a"])){
    $a = $_POST["a"];
} else {
    die('<div>Route not found.</div>');
}

if (isset($_GET['sx'])) {
    $sx = $_GET["sx"];
} elseif (isset($_POST["sx"])){
    $sx = $_POST["sx"];
}

// some common functions
$a                  = explode("x", $a);

// Active Tab - Folder
$activetab          = "ho$a[0]e";

// File containing the form specific action
$b                  = explode("-", $a[1]);
$activemenu         = $b[0];
$activemenucontent  = $a[1];

/*
+-------------------------------------------------------+
|	Form specific - action				                |
+-------------------------------------------------------+
|*/ include "$activetab/$activemenucontent.cgi"; /*	    |
+-------------------------------------------------------+
*/
