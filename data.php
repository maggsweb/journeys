<?php

/* @var $db */
/* @var $settings */

use Carbon\Carbon;

include 'includes/bootstrap.php';

// Get records
$records = $db->query("SELECT * FROM `{$settings['dbtable']}` ORDER BY start_date")->fetchAll();
//dump($records);

// -------------------------------------------------   HTML   ----------------------------------------------------------
include 'includes/head.php';

?>

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

<?php include 'includes/foot.php';