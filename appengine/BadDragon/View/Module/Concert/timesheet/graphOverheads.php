<?php


// var_dump($tx);

// Leave
$h1 = 0;
$m1 = 0;

// Overheads
$h2 = 0;
$m2 = 0;

// Projects
$h3 = 0;
$m3 = 0;

foreach ($tx as $o) {

    if ($o[0] < 10) {
        // Leave
        $h1 = $h1 + $o[1];
        $m1 = $m1 + $o[2];
    } elseif ($o[0] < 500) {
        // Overheads
        $h2 = $h2 + $o[1];
        $m2 = $m2 + $o[2];
    } else {
        // Projects
        $h3 = $h3 + $o[1];
        $m3 = $m3 + $o[2];
    }
}

?>
<div style="margin: auto; text-align:center; padding: 50px 0;">
    <div style="width: 350px; margin: auto;">
        <canvas id="billablePie"></canvas>
    </div>
    Manhours Overheads and Projects
</div>
<script>
    <?php

    echo "const dataBillable = [";

    // echo "{ Project: 'Leave', manhours: '" . bdTimeH($h1, $m1) . "' },";
    echo "{ Project: 'Overheads', manhours: '" . bdTimeH($h2, $m2) . "' },";
    echo "{ Project: 'Projects', manhours: '" . bdTimeH($h3, $m3) . "' }";

    echo "];";
    ?>
    const ctx6 = document.getElementById('billablePie')

    new Chart(
        ctx6, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: dataBillable.map(row => row.Project),
                datasets: [{
                    label: 'Manhours',
                    data: dataBillable.map(row => row.manhours),
                }]
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