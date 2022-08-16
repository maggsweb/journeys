<?php include 'includes/bootstrap.php';

/* @var $settings */
/* @var $db */

if (isset($_POST['frmName']) && $_POST['frmName'] == 'createTable') {

    $sql = trim($_POST['submitSql']);
    if(!$sql) {
        dump('Sql Invalid or non existant');
    }

    // Cleanup SQL
    $sql = str_replace("\n", '', $sql);
    $sql = str_replace("\r", '', $sql);

    // Delete table if exists, and checkbox ticked
    preg_match('/create\s+table\s+`?(\w+)`/i', $sql, $match);
    $table_name = $match[1];
    $db->query("DROP TABLE IF EXISTS `$table_name`")->execute();

    // Create table
    $create = $db->query($sql)->execute();
    if (!$create) {
        dump($db->getError());
        exit;
    }

    $_SESSION['table'] = $table_name;

    header("location: import.php");
    exit;
}

// HTML ----------------------------------------------------------------------------------------------------------------
include 'includes/head.php'; ?>

<a href="import.php">
    <h2 class="grey">1. Select XML File</h2>
</a>

<h2>2. Create Table</h2>

<form action="" method="post">
    <fieldset>
        <legend>2</legend>
        <textarea name="submitSql"><?= $_SESSION['sql'] ?></textarea>
        <button>Create</button>
    </fieldset>
    <input type="hidden" name="frmName" value="createTable">
</form>

<h2 class="grey">3. Import Records</h2>

<h2 class="grey">4. Result</h2>

<?php include 'includes/foot.php';