<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 09-Dec-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
?>

<script type="text/javascript" src="<?= $base_url ?>matchbox/chartjs/chart.min.js"></script>
<script type="text/javascript" src="<?= $base_url ?>matchbox/chartjs/chartjs-plugin-datalabels.min.js"></script>
<div class="windowBox2">
    <?php

    $displaySelectProject = 0;

    /*
+-------------------------------------------------------+
| Date range selection form                             |
+-------------------------------------------------------+
*/
    $formTitle = 'Time Utilization Graphs';
    require BD . 'View/Module/setDateRange.php';


    // Get timesheet data
    $query = "select 
            `project_id` as `pid`,
            sum(`no_of_hours`) as `h`,
            sum(`no_of_min`) as `m`
        from 
            `timesheet` 
        where 
            `user_id` = '$uid' and
            `dt` >= '$fdt' and
            `dt` <= '$tdt' and
            `active` > 0 and 
            `quality` < 1
        group by
            `project_id`";

    //echo "<br>" . $query;

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            // For Projects
            $t[$row['pid']] = [$row['h'], $row['m']];
            // For Overheads graph
            $tx[] = [$row['pid'], $row['h'], $row['m']];
        };
        $result->close();
    }

    //echo '<pre>' , var_dump($t) , '</pre>';
    if (isset($tx)) {

        // Generate Graphs
        require __DIR__ . '/graphProjects.php';
        require __DIR__ . '/graphOverheads.php';
        require __DIR__ . '/graphMilestone.php';
    } else {

        // No data to show
        echo "<div style='text-align:center;padding:30px;'>No time data available...</div>";
    }
    ?>
</div>