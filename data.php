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
//dump($sql);

$records = $db->query($sql)->fetchAll();
//dump($records);

// -------------------------------------------------   HTML   ----------------------------------------------------------
include 'includes/head.php';
include 'includes/filters.php';

?>

<table>
    <tr>
        <th>Date</th>
        <th>Start</th>
        <th>End</th>
        <th>Duration</th>
        <th>From</th>
        <th>To</th>
        <th>Distance</th>
        <th>Efficiency</th>
        <th>Speed</th>
    </tr>
    <?php foreach ($records as $record) { ?>
    <tr>
        <td><?= Carbon::createFromFormat('Y-m-d H:i:s', $record->start_date)->format('jS F Y') ?></td>
        <td><?= Carbon::createFromFormat('Y-m-d H:i:s', $record->start_date)->format('g:ia') ?></td>
        <td><?= Carbon::createFromFormat('Y-m-d H:i:s', $record->end_date)->format('g:ia') ?></td>
        <td><?= $record->duration ?></td>
        <td><?= $record->start_location ?></td>
        <td><?= $record->end_location ?></td>
        <td><?= $record->distance ?></td>
        <td><?= $record->efficiency ?></td>
        <td><?= $record->speed ?></td>
    </tr>
    <?php } ?>
</table>

<?php include 'includes/foot.php';