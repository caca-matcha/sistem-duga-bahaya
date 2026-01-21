<?php

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$file = 'Master_Lokasi_AutoID.xlsx';

if (!file_exists($file)) {
    echo "File not found: $file\n";
    exit(1);
}

try {
    $data = Excel::toArray(new stdClass, $file);
    
    if (empty($data)) {
        echo "File is empty or could not be read.\n";
        exit;
    }

    $sheet1 = $data[0];
    
    echo "--- HEADER (Row 1) ---\n";
    print_r($sheet1[0] ?? 'Empty Row 1');
    
    echo "\n--- ROW 2 ---\n";
    print_r($sheet1[1] ?? 'Empty Row 2');

    echo "\n--- ROW 3 ---\n";
    print_r($sheet1[2] ?? 'Empty Row 3');
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
