<?php

include 'includes/bootstrap.php';

/* @var $db */
/* @var $settings */
/* @var $filter */

// Get records
$sql = "SELECT start_date, DATE_FORMAT(start_date, '%D %M %Y') as day, count(*) as num_trips, CEILING(sum(distance)) as sum_distance
        FROM `{$settings['dbtable']}`
        $filter
        GROUP BY day
        ORDER BY start_date ASC";

$records = $db->query($sql)->fetchAll();
//dump($records);

$data = [];
$data[] = [
    ['type' => 'string', 'label' => 'Date'],
    ['type' => 'number', 'label' => 'Total Distance (miles)'],
    ['type' => 'string', 'role' => 'tooltip']//,  'p' => ['html' => true]]
];
foreach ($records as $record) {
    $data[] = [
        $record->day,
        (float) $record->sum_distance,
        "$record->day \n Trips: $record->num_trips \n Distance: $record->sum_distance miles"
    ];
}
$data = json_encode($data);
// dump($data);

// -------------------------------------------------   HTML   ----------------------------------------------------------
include 'includes/head.php';
include 'includes/filters.php';

?>

    <div id="google_chart"></div>

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