<?php include 'includes/bootstrap.php';

/* @var $settings */
/* @var $db */

$table_name = $_SESSION['table'];

$columns = $db->query("DESCRIBE `$table_name`")->fetchAll();

$fields = [];
foreach($columns as $column) {
    $fields[] = $column->Field;
}

$rows = $db->query("SELECT * FROM `$table_name`")->fetchAll();

// HTML ----------------------------------------------------------------------------------------------------------------
include 'includes/head.php'; ?>

<a href="import.php">
    <h2 class="grey">1. Select XML File</h2>
</a>

<a href="_create.php">
    <h2 class="grey">2. Create Table</h2>
</a>

<a href="_import.php">
    <h2 class="grey">3. Import Records</h2>
</a>

<h2>4. Result</h2>

<table>
    <thead>
        <tr>
            <th colspan="<?=count($fields) ?>"><?=$_SESSION['table']?></th>
        </tr>
        <tr>
            <?php foreach($columns as $column) { ?>
            <th><?= $column->Field ?></th>
            <?php } ?>
        </tr>
    </thead>
    <?php foreach($rows as $row) { ?>
    <tr>
        <?php foreach($columns as $column) { ?>
            <td><?= $row->{$column->Field} ?></td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>

<?php include 'includes/foot.php';
