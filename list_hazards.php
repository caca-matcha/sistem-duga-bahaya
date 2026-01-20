<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Hazard;

$hazards = Hazard::where('map_id', 17)->get();
echo "TOTAL HAZARDS FOR MAP 17: " . $hazards->count() . "\n";
foreach ($hazards as $h) {
    echo "ID: {$h->id}, STATUS: {$h->status}, RISK: {$h->risk_score}, CELL_ID: " . ($h->cell_id ?? 'NULL') . "\n";
}
