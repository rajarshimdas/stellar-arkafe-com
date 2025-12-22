<div class="rd-statusbar">
    <?php

    if (ENV == 'alpha' || ENV == 'beta') {
        rd($companyname . ' | ' . ENV /* . ' | ' . $sid */);

        // rx($_SESSION);
        // rx($route);
        // echo '<br>leaveCalendarUserId: ' . $leaveCalendarUserId;
        // rd('activeCalYear: ' . $activeCalYear);

    } else {
        rd($companyname . ' | ' . VERSION);
    }
    ?>
</div>