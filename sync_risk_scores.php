<?php

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Cell;
use App\Models\Hazard;

echo "--- Synchronizing Hazard Risk Scores ---\n";
$hazards = Hazard::whereNotNull('final_tingkat_keparahan')
    ->whereNotNull('final_kemungkinan_terjadi')
    ->get();

foreach ($hazards as $h) {
    $newScore = $h->final_tingkat_keparahan * $h->final_kemungkinan_terjadi;
    if ($h->risk_score != $newScore) {
        echo "Updating Hazard #{$h->id}: {$h->risk_score} -> $newScore\n";
        $h->risk_score = $newScore;
        $h->save();
    }
}

echo "\n--- Recalculating Cell Risk Scores ---\n";
$cells = Cell::all();
foreach ($cells as $cell) {
    $cell->recalculateRiskScore();
}

echo "\nDone!\n";
