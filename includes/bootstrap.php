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

$queryString = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';

/**
 * @return string
 */
function generateFilters(): string
{
    $filters = [];
    // Default month to today
    $filters['ym'] = "start_date LIKE '".date('Y-m')."%'";
    // Overwrite selected month
    if (isset($_GET['month']) and preg_match('/^[0-9]{4}\-[0,1][0-9]$/', $_GET['month'])) {
        $month = $_GET['month'];
        $filters['ym'] = "start_date LIKE '$month%'";
    }
    return count($filters)
        ? ' WHERE ' . implode(' AND ', $filters)
        : '';
}

$filter = generateFilters();