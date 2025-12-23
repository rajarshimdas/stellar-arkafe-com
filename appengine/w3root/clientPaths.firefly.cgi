<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 15-May-2008				                |
| Updated On: 17-Dec-2012				                |
+-------------------------------------------------------+
| Client Configurations: clientPaths.cgi                |
+-------------------------------------------------------+
*/

// App
$virtualName        = 'appengine';

// UNIX Path
$w3path             = "/var/www/stellar/stellar-arkafe-com/";
$virtualFolderPath  = $w3path.$virtualName;
$new_include_path   = ".:/usr/share/php:/usr/share/pear:$virtualFolderPath/w3etc";

/*
+-------------------------------------------------------+
| Paths                           			            |
+-------------------------------------------------------+
*/
$w3root             = $w3path.$virtualName.'/w3root';
$w3etc              = $w3path.$virtualName.'/w3etc';
$systemOS           = 'UNIX/Linux';
$pathStudio         = $w3path.$virtualName.'/w3root/studio';
$pathInclude        = $w3path.$virtualName.'/w3etc';
$filedb             = $w3path.'w3filedb';
$pathCron           = $w3path.$virtualName.'/w3etc/cronjobs';    

$holidayListFile = $w3path.$virtualName.'/w3etc/config/daysOff/Mumbai.txt';

