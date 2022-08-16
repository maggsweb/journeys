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
$records = $db->query('SELECT * FROM `journeys`')->fetchAll();
//dump($records);

?>

<br>:wq

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