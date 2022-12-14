<?php

use Carbon\Carbon;

include 'includes/bootstrap.php';

/* @var $db */
/* @var $settings */
/* @var $filter */


// Get records
$sql = "SELECT * 
        FROM `{$settings['dbtable']}` 
        $filter
        ORDER BY start_date";

$records = $db->query($sql)->fetchAll();
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

        let color1 = '#3366cc'; // blue
        let color2 = '#CC334A'; // red

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
                colors: [color1, color2],
                chartArea: {
                    top:50,
                    bottom: 80,
                },
                vAxes: {
                    0: {
                        // title: 'Average Speed (mph)',
                        textStyle: {
                            color: color1
                        },
                        minorGridlines: 'none',
                        // minorGridlines: {count: 8, color: '#ccc'},
                        titleTextStyle: {
                            color: color1
                        },
                    },
                    1: {
                        // title: 'Average Fuel Consumption (mpg)',
                        textStyle: {
                            color: color2
                        },
                        minorGridlines: 'none',
                        // minorGridlines: {count: 2, color: '#ccc'},
                        titleTextStyle: {
                            color: color2
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