<?php

//session_start();

use Maggsweb\MyPDO;

require __DIR__.'/../vendor/autoload.php';

$settings = parse_ini_file( 'configuration.ini');
//dump($settings);



$db = new MyPDO($settings['host'], $settings['user'], $settings['pass'], $settings['dbname']);
if($db->getError()) {
    dump($db->getError());
    exit;
}

if (!is_dir($settings['upload_dir'])) {
    mkdir($settings['upload_dir'], 0777);
}

// Create Journeys table if it does not exist
$journeys_table_exists = $db->query('SELECT 1 FROM `journeys` LIMIT 1')->execute();
if (!$journeys_table_exists) {

    $sql = "CREATE TABLE `journeys` (
        start_date      datetime NOT NULL,
        end_date        datetime NOT NULL,
        start_location  varchar(255)  NOT NULL,
        start_coords    varchar(100) NOT NULL,
        end_location    varchar(255) NOT NULL,
        end_coords      varchar(100) NOT NULL,
        duration        varchar(20) NOT NULL,
        distance        varchar(20) NOT NULL,
        efficiency      varchar(20) NOT NULL,
        speed           varchar(20) NOT NULL,
        PRIMARY KEY (start_date) 
    )";

    $db->query($sql)->execute();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;

}

function clean($data): array
{
    $return = [];
    foreach($data as $key => $value) {
        $value = str_replace('"', '', $value);
        $value = str_replace("\r", '', $value);
        $value = str_replace("\n", '', $value);
        $return[$key] = $value;
    }
    return $return;
}