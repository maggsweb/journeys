<?php

use Carbon\Carbon;

include 'includes/bootstrap.php';

/* @var $settings */
/* @var $db */

// Create table if it does not exist
$table_exists = $db->query("SELECT 1 FROM `{$settings['dbtable']}` LIMIT 1")->execute();
if (!$table_exists) {

    $sql = "CREATE TABLE `{$settings['dbtable']}` (
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

$insert_count = 0;

// Process Generate SQL form
if (isset($_FILES['upload'])) {

    $upload = $_FILES['upload'];


    // Check Upload errors
    if ($upload['error']) {
        dump('File upload error, Code: '.$upload['error']);
        exit;
    }

    // Check upload file type
    $types = [
        'application/vnd.ms-excel'
    ];
    if (!in_array($upload['type'], $types)) {
        dump('File must be CSV');
        exit;
    }

    // Store upload file
    $uploadedFile = $settings['upload_dir'] . '/' . time() . '.csv';
    if (!move_uploaded_file($upload['tmp_name'], $uploadedFile)) {
        dump('Error moving uploaded file');
        exit;
    }

    // Get file contents
    $CSV = file_get_contents($uploadedFile);
    $lines = explode("\n", $CSV);

    // Remove heading row
    array_shift($lines);

    foreach ($lines as $line) {

        if ($line == '') continue;

        // Cleanup each line
        $data = explode('","', $line);
        $data = clean($data);

        // Start Date & Time
        [$day, $month, $year] = explode('/', $data[0]);
        [$hour, $min] = explode(':', $data[1]);
        $start_date = Carbon::create($year,$month,$day,$hour,$min)->toDateTimeString();

        // End Date & Time
        [$day, $month, $year] = explode('/', $data[2]);
        [$hour, $min] = explode(':', $data[3]);
        $end_date = Carbon::create($year,$month,$day,$hour,$min)->toDateTimeString();

        // Check if row exists
        $exists = (bool) $db->query("SELECT COUNT(*) as num_rows FROM `{$settings['dbtable']}` WHERE start_date = :start_date LIMIT 1")
            ->bind(':start_date', $start_date)
            ->fetchOne();

        if (! $exists) {

            $insert = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_location' => $data[4],
                'start_coords' => $data[5],
                'end_location' => $data[6],
                'end_coords' => $data[7],
                'duration' => $data[8],
                'distance' => $data[9],
                'efficiency' => $data[10],
                'speed' => $data[11],
            ];
            if ($db->insert($settings['dbtable'], $insert)) {
                $insert_count++;
            }
        }
    }

    $_SESSION['message'] = "Inserted $insert_count records";

    header("Location: /");
    exit;
}

// -------------------------------------------------   HTML   ----------------------------------------------------------
include 'includes/head.php';
?>

<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Upload CSV Journey data file</legend>
        <input type="file" name="upload" required>
        <button>Upload</button>
    </fieldset>
</form>
