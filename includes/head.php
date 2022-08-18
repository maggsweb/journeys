<?php

/* @var $settings */

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
    <a href="/index.php?view=data">Data</a> |
    <a href="/index.php?view=speed-efficiency">Speed & Efficiency</a>
</p>

<br>
