<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 25-May-10					                |
| Updated On: 						                    |
+-------------------------------------------------------+
*/ ?>

<table cellpadding="0" cellspacing="2" width="100%">
    <?php

    menubar('Project New',      $activemenu, 'project-new');
    menubar('Project Edit',     $activemenu, 'project-edit');
    menubar('Project Delete',   $activemenu, 'project-delete');
    menubar('User New',         $activemenu, 'user-add');
    menubar('User Edit',        $activemenu, 'user-edit');
    menubar('User Delete',      $activemenu, 'user-delete');
    menubar('Reset Password',   $activemenu, 'user-resetpw');
    menubar('Module Access',    $activemenu, 'module-tick');
    // menubar('TimeDESK',      $activemenu, 'timedesk-uid');
    menubar('Timesheet Lock',   $activemenu, 'timesheet-lock');
    menubar('Timesheet Flags',  $activemenu, 'timesheet-flags');
    menubar('Holidays',         $activemenu, 'holidays');
    menubar('Leave',            $activemenu, 'leave');
    ?>
</table>
