<?php include 'includes/bootstrap.php';

/* @var $db */
/* @var $settings */

// Delete if selected
//if(isset($_GET['delete'])) {
//    $table = $_GET['delete'];
//    $db->query("DROP TABLE IF EXISTS `$table`")->execute();
//}

// Count records
//$records = $db->query('SELECT COUNT(*) as num_records FROM `journeys`')->fetchOne();
//dump($records);

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

<!--<br>-->
<!--<br>-->
<!--<br>-->

<!--<table>-->
<!--    <tr>-->
<!--        <th>-->
<!--            Database:-->
<!--        </th>-->
<!--        <td colspan="2">-->
<!--            --><?//=$settings['dbname']?>
<!--        </td>-->
<!--    </tr>-->
<!--    --><?php //$rowspan = true; ?>
<!--    --><?php //foreach ($tables as $table) { ?>
<!--    --><?php //foreach ($table as $table_name) { ?>
<!--    <tr>-->
<!--        --><?php //if ($rowspan) { ?>
<!--        <th rowspan="--><?//=count($tables)?><!--">Tables:</th>-->
<!--        --><?php //$rowspan = false; ?>
<!--        --><?php //} ?>
<!--        <td>--><?//=$table_name?><!--</td>-->
<!--        <td><a href="?delete=--><?//=$table_name?><!--">[Delete]</a></td>-->
<!--    </tr>-->
<!--    --><?php //} ?>
<!--    --><?php //} ?>
<!--</table>-->

<?php include 'includes/foot.php';