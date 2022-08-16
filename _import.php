<?php include 'includes/bootstrap.php';

/* @var $settings */
/* @var $db */

$xml = simplexml_load_file($_SESSION['file']);

$numRecords = count($xml);

$table_name = $_SESSION['table'];

$numExistingRecords = $db->query("SELECT COUNT(*) as numRecords FROM `$table_name`")->fetchOne();

if (isset($_POST['frmName']) && $_POST['frmName'] == 'importSql') {

    $_SESSION['insert_count'] = 0;


    if (isset($_POST['truncate'])) {
        $db->query("TRUNCATE `$table_name`")->execute();
    }

    foreach ($xml as $row) {

        $columns = [];
        foreach($row as $k => $v) {
            $columns[$k] = (string) $v;
        }

        if ( $db->insert($table_name, $columns) ) {
            $_SESSION['insert_count']++;
        } else {
            $_SESSION['insert_errors'][] = $columns;
        }
    }

    header("location: result.php");
    exit;

}

// HTML ----------------------------------------------------------------------------------------------------------------
include 'includes/head.php'; ?>

<a href="import.php">
    <h2 class="grey">1. Select XML File</h2>
</a>

<a href="_create.php">
    <h2 class="grey">2. Create Table</h2>
</a>

<h2>3. Import Records</h2>

<fieldset>
    <legend>3</legend>
    <form action="" method="post">
        <p>Table: `<?=$_SESSION['table']?>`</p>
        <p><?=$numRecords?> records found for import / <?=$numExistingRecords?> records already exist</p>
        <label for="truncate">
            <input type="checkbox" name="truncate" id="truncate" value="1">
            Truncate table first
        </label>
        <br>
        <button>Import</button>
        <input type="hidden" name="frmName" value="importSql">
    </form>
</fieldset>

<h2 class="grey">4. Result</h2>

<?php include 'includes/foot.php';