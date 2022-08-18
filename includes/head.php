<?php

/* @var $settings */
/* @var $queryString */

?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="UTF-8" />
    <title><?= $settings['title'] ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>

<body>

<h1><?= $settings['title'] ?></h1>

<?php if ($_SESSION['message'] ?? false) { ?>
<p><?= $_SESSION['message'] ?></p>
<?php unset($_SESSION['message']); ?>
<?php } ?>


<p>
    <a href="/index.php">Home</a> |
    <a href="/import.php">Upload Data</a> |
    <a href="/data.php<?=$queryString?>">Data</a> |
    <a href="/graph-speed-efficiency.php<?=$queryString?>">Speed & Efficiency</a> |
    <a href="/graph-trips.php<?=$queryString?>">Trips</a>
</p>
