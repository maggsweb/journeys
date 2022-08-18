<?php include 'includes/bootstrap.php';

/* @var $db */
/* @var $settings */

// HTML ----------------------------------------------------------------------------------------------------------------
include 'includes/head.php';

if ($_SESSION['message'] ?? false) {
    echo '<p>'.$_SESSION['message'].'</p>';
    unset($_SESSION['message']);
}

?>

<form action="import.php" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Upload CSV Journey data file</legend>
        <input type="file" name="upload" required>
        <button>Upload</button>
    </fieldset>
</form>



<?php

// Get records
$records = $db->query('SELECT * FROM `journeys` ORDER BY start_date')->fetchAll();
//dump($records);

?>

<br>



<?php
    $data = [];
    $data[] = ['Date', 'Average Speed', 'Efficiency'];
    foreach($records as $record) {
        $data[] = [$record->start_date, (float)$record->speed, (float)$record->efficiency];
    }
    if (count($data)) {
        $data = json_encode($data);
    }
//    dump($data);
?>

<div id="current_usage"></div>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.load("visualization", "1", {packages:["CoreChart"], callback: drawCharts});
    function drawCharts() {
        drawChart_1();
    }

    function drawChart_1() {
        let data = google.visualization.arrayToDataTable(<?=$data?>);
        let options = {
            //title:'Average Speed & Effiency',
            legend: {
                // position: "none"
            },
            height: 600,
            colors: ['#326295', 'red'],
            chartArea: {
                top:30,
                bottom: 80,
            },
            vAxis: {
                //title: 'qwertgyhjuk'
            },
            hAxis: {
                slantedText: true,
                textStyle : {
                    fontSize: 12
                }
            }
        };
        // var chart = new google.visualization.BarChart(document.getElementById('current_usage'));
        let chart = new google.visualization.ColumnChart(document.getElementById('current_usage'));
            chart.draw(data, options);
    }

    // $(document).ready(function(){

        // $("a[href='#tab_1']").on('shown.bs.tab', function (e) {
        //     drawChart_1();
        // });
    // });

</script>


<p>
    <a href="?view=data">Data</a> |
    <a href="?view=calendar">Calendar</a>
</p>

<br>

<?php if(($_GET['view'] ?? 'data') == 'data') { ?>

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

<?php } ?>


<?php if(($_GET['view'] ?? 'data') == 'calendar') { ?>

    Calendar

<?php } ?>

<?php include 'includes/foot.php';