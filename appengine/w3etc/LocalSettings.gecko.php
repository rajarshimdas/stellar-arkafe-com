<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 30-Jan-2012				                |
| Updated On: 11-Feb-2024                               |
+-------------------------------------------------------+
| LocalSettings.php                                     |
+-------------------------------------------------------+
*/
$cnfLocalSettings = 'Beta M';
/*
+-------------------------------------------------------+
| Base URL                       			            |
+-------------------------------------------------------+
*/
$base_url = "https://st.arkafe.com/";
$companyname = "Stellar dev";

/*
+-------------------------------------------------------+
| Database                       			            |
+-------------------------------------------------------+
*/
$db_name        = 's1db';
$db_host        = 'localhost';

$cn1_uname      = 'st1';
$cn1_passwd     = 'dec25';

$cn2_uname      = 'st2';
$cn2_passwd     = 'stDec25';


/*
+-------------------------------------------------------+
| Local Settings                 			            |
+-------------------------------------------------------+
*/

// OS flag - 0|1 Windows|Linux respectively
$osFlag             = 1;
// Display the Core Session Information
$DisplaySessionInfo = 0;
// rootFolder name - Keeps changing due to versioning
$rootFolderName     = 'studio';
// Administrator email id
$adminEmailId       = 'rd@rajarshi.me';
// Session Timeout
$LoginTimeOut       = 28800;    // 8 Hours
// Transmitals
$tmTotalItems       = 20;
// Project Manager and Coordinator roles_id < 14
$pm_roles_id        = 14;

$hostname           = $_SERVER['HTTP_HOST'];
$cookieName         = 'CONCERT';
// $cookiePath     = $w3path . '/writeable/session/';

$currency           = 'Rupees';

/*
+-------------------------------------------------------+
| Module Access                 			            |
+-------------------------------------------------------+
*/
$bdModuleAccess = [
    'cost' => [
        'ankit.agrawal',
        'stellar',
    ],
];

## Timesheet Manage
#
$fastApproveRights = [
    'ankit.agrawal',
    'stellar',
];

## Leave Module
#
$leaveModuleRole['4'] = [
    'ankit.agrawal',
    'stellar',
];

/*
+-------------------------------------------------------+
| Timesheet | Portal.php                                |
+-------------------------------------------------------+
*/
$tsPortalTabs = [
    # [ URL Link,   Tab Name ]
    ['tasks',       'Tasks'],
    ['timesheet',   'Timesheet'],
];

$tsPortalTabMenu  = [
    'tasks'     => [
        # [ URL Link,   Menu Name,      role_id]
        ['snapshot',    'Snapshot',     100],
        ['add',         'Add',          12],
        ['import',      'Import',       12],
        ['update',      'Update',       12],
        ['stats',       'Stats',        12],
    ],
    'timesheet'    => [
        ['my-tasks',    'My Tasks',     100],
        ['overheads',   'Overheads',    100],
        ['log',         'Day Logs',     100],
        ['graphs',      'Graphs',       100],
    ],

];

/*
+-------------------------------------------------------+
| Switch User | 01-May-2025                             |
+-------------------------------------------------------+
*/
$adminSwitchUser = [
    // 'stellar',
];
/*
+-------------------------------------------------------+
| noTabulationForUsers                                  |
+-------------------------------------------------------+
*/
$noTabulationForUsers = [
    'ankit.agrawal',
    'stellar',
];