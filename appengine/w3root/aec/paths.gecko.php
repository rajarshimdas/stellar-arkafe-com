<?php
$BD = "/var/www/arkafe/BadDragon";
$APP = "/var/www/beta/stellar-arkafe-com";


define("BD", $BD);
define("W3ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("W3APP", realpath($APP . "/modules/hrms/App"));
define("FILEDB", realpath($APP . "/w3filedb"));

//echo $APP . "/modules/hrms/App";