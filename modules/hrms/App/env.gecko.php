<?php
# Beta Server
#
require_once $APP.'/appengine/w3etc/LocalSettings.php';

define("BASE_URL", "https://st.arkafe.com/");
define("ENV", "beta");  // ENV: alpha | beta | prod
define("VERSION", "Ver 1.0s");
define("sessionSavePath", FILEDB . '/session/');

define("db_aec2db", "$db_host|$cn1_uname|$cn1_passwd|$db_name");
define("db_aec25r", "$db_host|$cn1_uname|$cn1_passwd|$db_name");
define("db_aec25w", "$db_host|$cn2_uname|$cn2_passwd|$db_name");

$domain_id = 2;
$branch_id = 17;

