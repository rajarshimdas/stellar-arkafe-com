<?php
if ($leaveCalendarUserId < 1) {
    $displayTeam = 'all';
} else {
    $displayTeam = 'emp';
    $fxUId = $leaveCalendarUserId;
}
require_once __DIR__ . '/calendar-fx.php';
?>
<style>
    .fc-event.fc-bg-event {
        opacity: 0.4 !important;
        background-color: lightslategray;
        color: black;
    }

    /* Today cell in month view */
    .fc-daygrid-day.fc-day-today {
        background-color: rgba(255, 223, 128, 0.8) !important;
    }
</style>
<script src='<?= BASE_URL ?>aec/public/fullcalendar/dist/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            firstDay: 1,

            slotEventOverlap: true,
            eventOverlap: true,
            eventDisplay: 'block',
            height: 'auto',

            dayMaxEventRows: true,
            views: {
                dayGridMonth: {
                    dayMaxEventRows: 100 // increase from default 3
                }
            },
            dayCellDidMount: function(info) {
                // Sunday = 0
                if (info.date.getDay() === 0) {
                    info.el.style.backgroundColor = 'lightslategray'; // light gray
                    info.el.style.opacity = '0.4';
                }
            },
            events: function(fetchInfo, successCallback /*, failureCallback */ ) {

                let formData = new FormData()
                formData.append("a", "aec-hrms-api-leaveCalendar")
                formData.append('sdt', fetchInfo.startStr)
                formData.append('edt', fetchInfo.endStr)
                formData.append('displayTeam', "<?= $displayTeam ?>")
                formData.append('fxUId', <?= $leaveCalendarUserId ?>)

                bdFetchAPI(apiUrl, formData).then((response) => {
                    // console.log(response);
                    successCallback(response)
                });
            },
            eventDidMount: function(info) {
                info.el.setAttribute('title', info.event.extendedProps.description || info.event.title);
            }
        });
        calendar.render();
    });
</script>
<div style="padding:10px;max-width:var(--rd-max-width);margin:auto;">
    <div id='calendar'></div>
</div>