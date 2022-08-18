<?php

/* @var $db */
/* @var $settings */

use Carbon\Carbon;

include 'includes/bootstrap.php';

$sql = "SELECT start_date, DATE_FORMAT(start_date, '%D %M %Y') as day, count(*) as num_trips, CEILING(sum(distance)) as sum_distance
        FROM `{$settings['dbtable']}`
        GROUP BY day
        ORDER BY start_date ASC";

// Get records
$records = $db->query($sql)->fetchAll();
//dump($records);

$data = [];
$data[] = ['Date', 'Total Distance (miles)'];
foreach ($records as $record) {
    $data[] = [
        $record->day,
        (float)$record->sum_distance
    ];
}
$data = json_encode($data);
// dump($data);

// -------------------------------------------------   HTML   ----------------------------------------------------------
include 'includes/head.php';

?>

    <div id="google_chart"></div>

    <table>
        <tr>
            <th>Day</th>
            <th>Trips</th>
            <th>Total distance (miles)</th>
        </tr>
        <?php foreach ($records as $record) { ?>
            <tr>
                <td><?= Carbon::createFromFormat('Y-m-d H:i:s',$record->start_date)->format('jS F Y') ?></td>
                <td><?= $record->num_trips ?></td>
                <td><?= $record->sum_distance ?></td>
            </tr>
        <?php } ?>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.load("visualization", "1", {packages:["CoreChart"], callback: drawChart});
        function drawChart() {
            let data = google.visualization.arrayToDataTable(<?=$data?>);
            let options = {
                animation:{
                    duration: 500,
                    startup: true
                },
                legend: {
                    position: "top"
                },
                height: 600,
                chartArea: {
                    top:50,
                    bottom: 80,
                },
                hAxis: {
                    slantedText: true,
                    textStyle : {
                        fontSize: 12
                    }
                }
            };
            let chart = new google.visualization.ColumnChart(document.getElementById('google_chart'));
            chart.draw(data, options);
        }
    </script>

<?php include 'includes/foot.php';