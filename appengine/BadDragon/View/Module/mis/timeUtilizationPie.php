<div class="Window2" align="center">

    <div style="padding: 10px 0px;">
        <?php
        $x = explode("-", $mo);
        echo "Time Utilization for " . $monthNum2Cal[$x[1]] . " " . $x[0];
        echo "<br>Total Manhours: $total_mh";
        ?>
    </div>
    <div>
        <a class="button" href="<?= $base_url ?>mis/export-timesheet-csv.cgi?mo=<?= $mo ?>">Export CSV</a>
    </div>
    <div style="width: 600px;"><canvas id="timeUtilizationPie"></canvas></div>

    <script type="text/javascript" src="<?= $base_url ?>matchbox/chartjs/chart.min.js"></script>
    <script type="text/javascript" src="<?= $base_url ?>matchbox/chartjs/chartjs-plugin-datalabels.min.js"></script>

    <script>
        <?php

        echo "const data = [";

        for ($i = 0; $i < count($chart); $i++) {
            echo "{ Project:'" . $chart[$i]["project"] . "', manhours: " . round($chart[$i]["manhours"],2) . " },";
        }

        echo "];";
        ?>
        const ctx = document.getElementById('timeUtilizationPie')

        new Chart(
            ctx, {
                type: 'pie',
                plugins: [ChartDataLabels],
                data: {
                    labels: data.map(row => row.Project),
                    datasets: [{
                        label: 'Manhours',
                        data: data.map(row => row.manhours),
                    }]
                },
                options: {
                    plugins: {
                        legend: false,
                        datalabels: {
                            anchor: 'end',
                            position: 'outside',
                        }
                    }

                }
            }
        );
    </script>

</div>