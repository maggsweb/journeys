<?php

/* @var $db */
/* @var $settings */

$sql = "SELECT 
            DATE_FORMAT(start_date, '%Y-%m') as value,
            DATE_FORMAT(start_date, '%Y %M') as label
        FROM `{$settings['dbtable']}`
        GROUP BY value
        ORDER BY value";
//dump($sql);

$months = $db->query($sql)->fetchAll();
//dump($months);

?>

<div class="form">

    <form action="" method="get">

        <select name="month">
            <option value="">Current month</option>
            <?php foreach ($months as $month) {
                $selected = ($_GET['month'] ?? '') == $month->value ? 'selected' : '';
                echo "\n <option value='$month->value' $selected>$month->label</option>";
            } ?>
        </select>

        <button>Filter</button>

    </form>

</div>

