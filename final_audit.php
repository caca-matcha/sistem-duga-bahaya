<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Hazard;
use App\Models\Cell;

echo "--- Auditing Hazards for Map 17 ---\n";
$hazards = Hazard::where('map_id', 17)->get();
foreach ($hazards as $h) {
    echo "Hazard #{$h->id}: Status={$h->status}, RiskScore={$h->risk_score}, CellID=" . ($h->cell_id ?? 'NULL') . "\n";
}

echo "\n--- Auditing Cells for Map 17 ---\n";
$cells = Cell::where('map_id', 17)->where('risk_score', '>', 0)->get();
foreach ($cells as $c) {
    echo "Cell #{$c->id}: RiskScore={$c->risk_score}\n";
    $cellHazards = Hazard::where('cell_id', $c->id)->whereIn('status', ['diproses', 'selesai'])->get();
    foreach ($cellHazards as $ch) {
        echo "  linked Hazard #{$ch->id}: RiskScore={$ch->risk_score}\n";
    }
}
