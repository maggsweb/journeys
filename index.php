<?php

/* @var $db */
/* @var $settings */

include 'includes/bootstrap.php';

// Stats..
$num_records   = $db->query("SELECT count(*) as x FROM `{$settings['dbtable']}` ORDER BY start_date")->fetchOne();
$earliest_date = $db->query("SELECT DATE_FORMAT(start_date, '%D %M %Y') as x FROM `{$settings['dbtable']}` ORDER BY start_date ASC LIMIT 1")->fetchOne();
$latest_date   = $db->query("SELECT DATE_FORMAT(start_date, '%D %M %Y') as x FROM `{$settings['dbtable']}` ORDER BY start_date DESC LIMIT 1")->fetchOne();

// -------------------------------------------------   HTML   ----------------------------------------------------------
include 'includes/head.php';

?>

<h2>Welcome..</h2>

<p>Currently holding <strong><?= $num_records ?></strong> records in DB
    <?php if ($num_records) {?>
    , from <strong><?= $earliest_date ?></strong> - <strong><?= $latest_date ?></strong>
    <?php } else { ?>
    .
    <?php } ?>
</p>

<?php include 'includes/foot.php';