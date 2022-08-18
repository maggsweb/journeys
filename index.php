<?php

/* @var $db */
/* @var $settings */

use Carbon\Carbon;

include 'includes/bootstrap.php';

// Only need to get Data if we are showing a 'view'
if (isset($_GET['view'])) {

    // @todo filters

    // Get records
    $records = $db->query("SELECT * FROM `{$settings['dbtable']}` ORDER BY start_date")->fetchAll();
    //dump($records);

    $data = [];
    $data[] = ['Date', 'Average Speed (mph)', 'Average Fuel Consumption (mpg)'];
    foreach ($records as $record) {
        $data[] = [
            Carbon::createFromFormat('Y-m-d H:i:s', $record->start_date)->format('jS M y, g:ia'),
            (float)$record->speed,
            (float)$record->efficiency
        ];
    }
    //if (count($data)) {
        $data = json_encode($data);
    //}
    //    dump($data);

}

// -------------------------------------------------   HTML   ----------------------------------------------------------
include 'includes/head.php';

if(($_GET['view'] ?? '') == 'data') { ?>

<table>
    <tr>
        <th>Start</th>
        <th>End</th>
        <th>From</th>
        <th>To</th>
        <th>Duration</th>
        <th>Distance</th>
        <th>Efficiency</th>
        <th>Speed</th>
    </tr>
    <?php foreach ($records as $record) { ?>
    <tr>
        <td><?= $record->start_date ?></td>
        <td><?= $record->end_date ?></td>
        <td><?= $record->start_location ?></td>
        <td><?= $record->end_location ?></td>
        <td><?= $record->duration ?></td>
        <td><?= $record->distance ?></td>
        <td><?= $record->efficiency ?></td>
        <td><?= $record->speed ?></td>
    </tr>
    <?php } ?>
</table>

<?php } else if(($_GET['view'] ?? '') == 'speed-efficiency') { ?>

    <div id="google_chart"></div>

<?php } else { ?>

<?php
// Stats..
$num_records   = $db->query("SELECT count(*) as x FROM `{$settings['dbtable']}` ORDER BY start_date")->fetchOne();
$earliest_date = $db->query("SELECT DATE_FORMAT(start_date, '%D %M %Y') as x FROM `{$settings['dbtable']}` ORDER BY start_date ASC LIMIT 1")->fetchOne();
$latest_date   = $db->query("SELECT DATE_FORMAT(start_date, '%D %M %Y') as x FROM `{$settings['dbtable']}` ORDER BY start_date DESC LIMIT 1")->fetchOne();
?>

    <h2>Welcome..</h2>
    <p>Currently holding <strong><?= $num_records ?></strong> records in DB
        <?php if ($num_records) {?>
        , from <strong><?= $earliest_date ?></strong> - <strong><?= $latest_date ?></strong>
        <?php } else { ?>
        .
        <?php } ?>
    </p>

<?php } ?>


    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.load("visualization", "1", {packages:["CoreChart"], callback: drawChart});
        function drawChart() {
            let data = google.visualization.arrayToDataTable(<?=$data?>);
            let options = {
                series:{
                    0: {targetAxisIndex:0},
                    1: {targetAxisIndex:1},
                    2: {targetAxisIndex:1}
                },
                animation:{
                    duration: 500,
                    startup: true
                },
                //title:'Average Speed & Effiency',
                legend: {
                    // position: "none"
                },
                height: 600,
                colors: ['blue', 'red'],
                chartArea: {
                    top:50,
                    bottom: 80,
                },
                vAxes: {
                    0: {
                        // title: 'Average Speed (mph)',
                        textStyle: {color: 'blue'},
                        minorGridlines: 'none',
                        // minorGridlines: {count: 8, color: '#ccc'},
                        titleTextStyle: {
                            color: 'blue'
                        },
                    },
                    1: {
                        // title: 'Average Fuel Consumption (mpg)',
                        textStyle: {color: 'red'},
                        minorGridlines: 'none',
                        // minorGridlines: {count: 2, color: '#ccc'},
                        titleTextStyle: {
                            color: 'red'
                        }
                    },
                },
                vAxis: {
                    ticks: [0, 10, 20, 30, 40, 50, 60, 70]
                    // gridlines: {
                    //     color: 'transparent'
                    // }
                    //title: 'qwertgyhjuk'
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