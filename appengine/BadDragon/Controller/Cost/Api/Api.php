<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 25-Dec-2024                               |
| Updated On:                                           |
+-------------------------------------------------------+
*/

if (!bdCostAuth($loginname, $bdModuleAccess)) {
    rdReturnJsonHttpResponse(
        '200',
        ['F', 'Access denied']
    );
}

