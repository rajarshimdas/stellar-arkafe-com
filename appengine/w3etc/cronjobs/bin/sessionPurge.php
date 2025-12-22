#!/usr/bin/php
<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 03-Oct-2012                               |
| Updated On:                           			    |
+-------------------------------------------------------+
*/
define("W3X", "/var/www/abhikalpan/abhikalpan-arkafe-com/");

require_once W3X . 'appengine/w3root/clientPaths.cgi';
require_once $w3etc . '/LocalSettings.php';
require_once $w3etc . '/env.php';
require_once $w3etc . '/foo/session/dbConnect.php';

/*
+-------------------------------------------------------+
| Purge the sessi0ns table					            |
+-------------------------------------------------------+
*/
$now = date('Y-m-h H:i:s');
$query = 'delete from `sessi0ns`';

$mysqli = cn2();

if (!$mysqli->query($query)) {
    $log = "F | " . $now . " sessionPurge error: " . $mysqli->error;
} else {
    $log = "T | " . $now . " sessionPurge done. Deleted ". $mysqli->affected_rows . " rows.";
}

$mysqli->close();

$logfile = W3PATH . "w3filedb/log/cron.log";

$f = fopen($logfile, "a");
fwrite($f, $log . "\n");
fclose($f);
