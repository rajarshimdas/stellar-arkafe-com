<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   01-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
$companyName        = "Abhikalpan";
$companyNameFull    = "Abhikalpan Architects and Planners";
*/

$companyName        = $companyname;
$companyNameFull    = $companyname;

$dateFormat         = 'd-M-y'; // eg 23-Mar-04

$currencyDefault    = "Rs";

// For Home button Routing
// user_type_id => [user_type, home page] # This crap wasted 2 hours
$bdUserType = [

    '1' => ['Modeller',                 'modular/home-modular.php'],
    '2' => ['Team Leader',              'tl/home-tl.php'],
    '3' => ['Project Manager',          'PM/home-pm.php'],
    '4' => ['HR',                       'HR/home-hr.php'],
    '5' => ['Accounts',                 '-'],
    '6' => ['Business Development',     'BD/home-bd.php'],
    '7' => ['IT',                       '-'],
    '8' => ['Admin',                    'admin/home-admin.php'],
    '9' => ['Super Admin',              '-'],

];

// Master List | Gender
$mlGender = [
    ['-',   '-- Select --'],           // Not defined
    ['M',   'Male'],
    ['F',   'Female'],
    ['O',   'Other'],
    ['NA',  'Prefer not to say'],
];
