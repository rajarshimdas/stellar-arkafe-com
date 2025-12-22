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
$fdt = $_GET['fdt2c'];      // From date
$tdt = $_GET['tdt2c'];      // To date

// Header
require_once 'foo/uid2displayname.php';
require_once 'foo/dateMysql2Cal.php';

echo '<br>&nbsp;<span style="font-size:125%;font-weight:bold;">' . uid2displayname($tid, $mysqli) . '</span>';
echo '<br>From: ' . dateMysql2Cal($fdt) . ' To: ' . dateMysql2Cal($tdt) . '<br>&nbsp;';

// Get Scope
$query = "select * from projectscope where active > 0 order by displayorder";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $scopeX[] = $row;
}
// var_dump($scopeX);

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
// var_dump($milestoneX);

$query = 'select
            t1.projectscope_id as scope_id,
            t2.id as milestone_id,
            sum(t1.no_of_hours) as noh,
            sum(t1.no_of_min) as nom
        from
            timesheet as t1,
            projectstage as t2
        where               
            t1.user_id = ' . $tid . ' and
            t1.dt >= "' . $fdt . '" and
            t1.dt <= "' . $tdt . '" and
            t1.active = 1 and
            t1.quality < 1 and
            t1.projectstage_id = t2.id
        group by
            scope_id,
            milestone_id';
// echo '<p>' . $query . '</p>';

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $timeX[$row['scope_id']][$row['milestone_id']] = totalMH($row['noh'], $row['nom']);
}

//echo '<pre>',var_dump($timeX),'</pre>';
// var_dump($milestoneX);

function totalMH($h, $m)
{
    $mh = round((($h * 60) + $m) / 60);
    return $mh;
}

function getDataX($scopeX, $milestoneX, $timeX)
{
    $i = 0;
    /*
    $cx = [
        "41, 94, 157,",
        "41, 157, 135,",
        "70, 157, 41,",
        "157, 110, 41,"
    ];
    */
    $cx = [
        "211,23,179,",
        "23,176,211,",
        "23,211,81,",
        "211,153,23,",
    ];

    foreach ($scopeX as $s) :

        $sId = $s['id'];
        $sNm = $s['description'];

?>
        {
        label: '<?= /*$sId.'|'.*/$sNm ?>',
        data: [<?php dataGrid($milestoneX, $timeX[$sId]); ?>],
        backgroundColor: 'rgba(<?= $cx[$i] ?> 0.6)',
        borderColor: 'rgba(<?= $cx[$i] ?> 1)',
        borderWidth: 0,
        },
<?php
        $i++;
    endforeach;
}

function dataGrid($milestoneX, $tX)
{
    foreach ($milestoneX as $m) {
        $mId = $m['id'];
        echo (isset($tX[$mId])) ? $tX[$mId] . ',' : '0,';
    }
}

function setLabels($milestoneX)
{
    foreach ($milestoneX as $m) {
        echo '"' . $m['milestone'] . '",';
    }
}
?>


Worked in Milestone (Stagewise)
<!-- ChartJS -->
<div style="width: 900px; margin: auto;">
    <canvas id="timesheetPie" aria-label="Worked in milestone - Graph" role="img"></canvas>
</div>

<script type="text/javascript" src="/matchbox/chartjs/chart.min.js"></script>
<script type="text/javascript" src="/matchbox/chartjs/chartjs-plugin-datalabels.min.js"></script>

<script>
    new Chart(
        document.getElementById('timesheetPie'), {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: [<?php setLabels($milestoneX); ?>],
                datasets: [
                    /*
                    {
                        label: 'Product A',
                        data: [150, 200, 170, 220, 180],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Product B',
                        data: [100, 140, 210, 190, 230],
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                    */
                    <?php getDataX($scopeX, $milestoneX, $timeX); ?>
                ]
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