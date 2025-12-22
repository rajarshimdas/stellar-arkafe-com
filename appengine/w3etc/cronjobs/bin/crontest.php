#!/usr/bin/php
<?php
define("W3PATH", "/var/www/abhikalpan/abhikalpan-arkafe-com/");

$now = date('Y-m-h H:i:s');
$log = $now . " test";

$logfile = W3PATH . "w3filedb/log/cron.log";

$f = fopen($logfile, "a");
fwrite($f, $log . "\n");
fclose($f);
