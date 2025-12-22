<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   09-Jan-09					            |
| Updated On:   12-Aug-10                               |
|               18-Jan-24 Project Coordinator Added     |
+-------------------------------------------------------+
*/
?>
<style>
    .tblScope tr td {
        width: 35px;
        border: 1px solid gray;
        text-align: center;
        padding: 0;
    }
</style>
<?php
if (isset($_GET["rx"])) {

    // Display status page
    include "boo/admin/project-new-rx.cgi";
} else {

    // Display Form
    include "boo/admin/project-new-form.cgi";
}
