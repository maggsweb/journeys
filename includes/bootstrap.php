<?php

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