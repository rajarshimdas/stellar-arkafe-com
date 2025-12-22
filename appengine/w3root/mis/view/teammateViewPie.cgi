<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Feb-2012				                |
| Updated On: 02-Dec-2023 (for Abhikalpan)              |
+-------------------------------------------------------+
*/
require_once 'foo/bar/teammateView.php';

// Variables
$tid = $_GET['tid'];        // Teammate UID
$fdt = $_GET['fdt2b'];      // From date
$tdt = $_GET['tdt2b'];      // To date

// Header
require_once 'foo/uid2displayname.php';
require_once 'foo/dateMysql2Cal.php';

echo '<br>&nbsp;<span style="font-size:125%;font-weight:bold;">' . uid2displayname($tid, $mysqli) . '</span>';
echo '<br>From: ' . dateMysql2Cal($fdt) . ' To: ' . dateMysql2Cal($tdt) . '<br>&nbsp;';

// Get Projects Array - All projects (including deleted ones)
$query = 'select 
            id, 
            projectname,
            active
        from 
            projects 
        where
            id > 15
        order by 
            projectname';

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $projX[] = $row;
}
// var_dump($projX);

$query = "select
                *
            from
                view_timesheets
            where
                user_id     = '$tid' and
                dtmysql     >= '$fdt' and
                dtmysql     <= '$tdt' and
                quality     < 1";

// echo '<br>Query: '.$query;

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $timeX[] = $row;
}
?>

<div style="width: 100%; text-align: left;">
    <pre>
        <?php
        // var_dump($timeX); 

        function getData($projX, $timeX)
        {
            for ($i = 0; $i < count($projX); $i++) {

                $thisPid    = $projX[$i]["id"];
                $thisPname  = $projX[$i]["projectname"];

                // Reset timers
                $ts_hours   = 0;
                $ts_min = 0;

                for ($n = 0; $n < count($timeX); $n++) {
                    if ($timeX[$n]["project_id"] == $thisPid) {
                        $ts_hours = $ts_hours + $timeX[$n]["no_of_hours"];
                        $ts_min = $ts_min + $timeX[$n]["no_of_min"];
                    }
                }

                $ts_total_min = ($ts_hours * 60) + $ts_min;
                //echo "<br>Pid: $thisPid | Pname: $thisPname | min: $ts_total_min";
                if ($ts_total_min > 0) {
                    $h = floor($ts_total_min / 60);
                    $m = $ts_total_min - ($h * 60);
                    echo "{ project: '$thisPname', hours: $h , manhour: '$h : $m'},";
                }
            }

            return true;
        }
        ?>
    </pre>
</div>

<!--  -->
<div style="width: 400px; margin: auto;">
    <canvas id="timesheetPie" aria-label="Worked on Projects - Graph" role="img"></canvas>
</div>
<script type="text/javascript" src="/matchbox/chartjs/chart.min.js"></script>

<script>
    const data = [<?php getData($projX, $timeX); ?>];

    const footer = (tooltipItems) => {  
        // return 'Manhours: ';
    };

    new Chart(
        document.getElementById('timesheetPie'), {
            type: 'doughnut',
            data: {
                labels: data.map(row => row.project),
                datasets: [{
                    label: 'Hours',
                    data: data.map(row => row.hours),
                }],
            },
            options: {
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            footer: footer,
                        }
                    }
                }
            }
        }
    );

</script>