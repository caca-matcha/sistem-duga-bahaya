<?php

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Hazard;

$h = Hazard::find(153);
if ($h) {
    echo 'ID: '.$h->id."\n";
    echo 'STATUS: '.$h->status."\n";
    echo 'RISK_SCORE (DB): '.$h->risk_score."\n";
    echo 'CELL_ID: '.$h->cell_id."\n";
    echo 'MAP_ID: '.$h->map_id."\n";
} else {
    echo "NOT FOUND\n";
}
