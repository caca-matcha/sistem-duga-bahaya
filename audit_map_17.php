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
use App\Models\Map;

$mapId = 17;
$map = Map::find($mapId);
if (! $map) {
    echo "MAP $mapId NOT FOUND\n";

    return;
}

echo "MAP $mapId: ".$map->name.' ('.$map->type.")\n";

$cellsWithRisk = Cell::where('map_id', $mapId)->where('risk_score', '>', 0)->get();
echo 'CELLS WITH RISK: '.$cellsWithRisk->count()."\n";

foreach ($cellsWithRisk as $cell) {
    echo '  CELL ID: '.$cell->id.' [R:'.$cell->row_index.', C:'.$cell->col_index.'] RISK: '.$cell->risk_score."\n";

    $hazards = Hazard::where('cell_id', $cell->id)->get();
    echo '    HAZARDS FOR THIS CELL: '.$hazards->count()."\n";
    foreach ($hazards as $h) {
        echo '      ID: '.$h->id.', STATUS: '.$h->status.', DB_RISK: '.$h->risk_score.', CALC_FINAL: '.($h->final_tingkat_keparahan * $h->final_kemungkinan_terjadi)."\n";
    }
}

// Also search for any hazard that mentions Map 17 but has NULL cell_id
$strayHazards = Hazard::where('map_id', $mapId)->whereNull('cell_id')->get();
echo "\nSTRAY HAZARDS FOR MAP $mapId (NULL cell_id): ".$strayHazards->count()."\n";
foreach ($strayHazards as $h) {
    echo '  ID: '.$h->id.', STATUS: '.$h->status.', DB_RISK: '.$h->risk_score.', CALC_FINAL: '.($h->final_tingkat_keparahan * $h->final_kemungkinan_terjadi)."\n";
}
