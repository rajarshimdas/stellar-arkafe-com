<?php
// echo 'p: '. $_POST['fdt'];
/*
if (strlen($_POST['fdt']) > 9 && strlen($_POST['tdt']) > 9) {
    $fdt = $_POST['fdt'];
    $tdt = $_POST['tdt'];
} else {
    die('<div style="text-align:center;">Error. Please try again...</div>');
}
*/

// Get Milestones
$query = 'select 
            id, 
            sname as milestone
        from 
            projectstage
        where 
            stageno > 0 and
            active = 1
        order by 
            stageno';

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $milestoneX[] = $row;
}
// var_dump($milestoneX); die;

$query = 'select
            t2.id as milestone_id,
            t1.no_of_hours,
            t1.no_of_min
        from
            timesheet as t1,
            projectstage as t2
        where               
            t1.user_id = ' . $uid . ' and
            t1.dt >= "' . $fdt . '" and
            t1.dt <= "' . $tdt . '" and
            t1.active = 1 and
            t1.quality < 1 and
            t1.projectstage_id = t2.id';
// echo '<br>Query: '.$query;

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $timeX[] = $row;
}

// var_dump($timeX); 
// var_dump($milestoneX);

function getData($milestoneX, $timeX)
{
    for ($i = 0; $i < count($milestoneX); $i++) {

        $thisMsId    = $milestoneX[$i]["id"];
        $thisMsName  = $milestoneX[$i]["milestone"];

        // echo "Milestone: $thisMsName ($thisMsId)";

        // Reset timers
        $ts_hours   = 0;
        $ts_min     = 0;

        for ($n = 0; $n < count($timeX); $n++) {
            if ($timeX[$n]["milestone_id"] == $thisMsId) {
                $ts_hours = $ts_hours + $timeX[$n]["no_of_hours"];
                $ts_min = $ts_min + $timeX[$n]["no_of_min"];
            }
        }

        $ts_total_min = ($ts_hours * 60) + $ts_min;

        $hours = $ts_total_min / 60;

        echo "{ Milestone: '$thisMsName', count: " . round($hours, 2) . " },";
    }

    return true;
}
?>

<!-- ChartJS -->
<div style="width: 600px; margin:auto;padding: 25px;">
    <canvas id="timesheetPie" aria-label="Worked in milestone - Graph" role="img"></canvas>
</div>
<div style="text-align: center;">Work done for Milestones</div>


<script>
    /* Static data - testing */
    const data = [<?php getData($milestoneX, $timeX); ?>];

    new Chart(
        document.getElementById('timesheetPie'), {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: data.map(row => row.Milestone),
                datasets: [{
                    label: 'Manhours',
                    data: data.map(row => row.count)
                }],
            },
            options: {
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        padding: 1,
                        color: 'black',
                        font: {
                            weight: 'bold',
                        }
                    }
                }
            }
        }
    );
</script>
