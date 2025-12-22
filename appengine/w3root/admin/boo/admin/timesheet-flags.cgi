<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
*/

if (isset($_GET["rx"])) {

    // Display status page
    include "boo/admin/timesheet-flags-rx.cgi";
} else {

    // Display Form
    include "boo/admin/timesheet-flags-form.cgi";
}
