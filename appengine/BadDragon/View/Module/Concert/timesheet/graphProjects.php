<?php

// Get Projects List
$query = "select 
            `id` as `pid`, 
            `jobcode`, 
            concat(`jobcode`,' - ',`projectname`) as `projectname`
        from 
            `projects` 
        where
            `id` > 499
        order by 
            `jobcode`";
//echo "<br>" . $query;

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    };
    $result->close();
}



// Totals
$th = 0;
$tm = 0;

// Total MH for each project
for ($i = 0; $i < count($projects); $i++) {

    $pid = $projects[$i]["pid"];
    $pnm = $projects[$i]["projectname"];
    //echo '<br>p: ' . $pid . ' | ' . $pnm;

    $h = 0;
    $m = 0;

    if (isset($t[$pid])) {
        $h = $t[$pid][0];
        $m = $t[$pid][1];
    }

    if ($h > 0 || $m > 0) {
        $chart[] = [
            "project" => $pnm,
            "manhours" => bdTimeH($h, $m),  // Rounded
            "mh" => bdAddHourMin($h, $m),    // Manhours
        ];

        $th += $h;
        $tm += $m;
    }
}

$total_mh = bdAddHourMin($th, $tm);

//echo "<pre>", var_dump($chart), "</pre>";

?>

<style>
    table#projects {
        border-collapse: collapse;
    }

    table#projects tr td {
        border: 1px solid white;
        line-height: 25px;
        padding: 0 5px;
        font-size: 0.8em;
    }
</style>
<div class="Window2" align="center">
    <div>
        <table style="width:100%;background-color:thistle;">
            <tr>
                <td style="width: 600px; padding:25px;">
                    <div style="width: 300px; margin:auto;">
                        <canvas id="timeUtilizationPie"></canvas>
                    </div>
                </td>
                <td>
                    <table id="projects" style="width:400px;">
                        <tr>
                            <td style="width: 30px;text-align:center;">No</td>
                            <td>Projects</td>
                            <td style="width: 80px; text-align:right;">Manhours</td>
                        </tr>
                        <?php
                        $no = 1;
                        foreach ($chart as $p) {
                            echo '<tr><td style="text-align:center;">' . $no++ . '</td><td>' . $p['project'] . '</td><td style="text-align:right;">' . $p['mh'] . '</td></tr>';
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td>Total</td>
                            <td style="text-align:right;"><?= $total_mh ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    
    <script>
        <?php

        echo "const dataProjects = [";

        for ($i = 0; $i < count($chart); $i++) {
            echo "{ Project:'" . $chart[$i]["project"] . "', manhours: " . round($chart[$i]["manhours"], 2) . " },";
        }

        echo "];";
        ?>
        const ctx5 = document.getElementById('timeUtilizationPie')

        new Chart(
            ctx5, {
                type: 'pie',
                plugins: [ChartDataLabels],
                data: {
                    labels: dataProjects.map(row => row.Project),
                    datasets: [{
                        label: 'Manhours',
                        data: dataProjects.map(row => row.manhours),
                    }]
                },
                options: {
                    plugins: {
                        legend: false,
                    }

                }
            }
        );
    </script>

</div>