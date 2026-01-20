<?php
use App\Models\Hazard;
use App\Models\Cell;

$hazard = Hazard::find(141);
if (!$hazard) {
    echo "Hazard 141 not found\n";
    return;
}

echo "--- Hazard 141 ---\n";
echo "ID: " . $hazard->id . "\n";
echo "Risk Score: " . $hazard->risk_score . "\n";
echo "Status: " . $hazard->status . "\n";
echo "Cell ID: " . $hazard->cell_id . "\n";

if ($hazard->cell_id) {
    $cell = Cell::find($hazard->cell_id);
    echo "\n--- Cell {$cell->id} ---\n";
    echo "Risk Score: " . $cell->risk_score . "\n";
    echo "Hazard Count (from cell field): " . $cell->hazard_count . "\n";
    
    $allHazards = Hazard::where('cell_id', $cell->id)->get();
    echo "\n--- All Hazards for Cell {$cell->id} ---\n";
    foreach ($allHazards as $h) {
        echo "ID: {$h->id}, Status: {$h->status}, Risk: {$h->risk_score}\n";
    }
}
