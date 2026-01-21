<?php

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Hazard;

echo "--- Fixing Kategori Resiko for all Hazards ---\n";
$hazards = Hazard::all();

foreach ($hazards as $h) {
    $risk = $h->risk_score;
    // Fallback calculation if risk_score is 0 but it's a finished report with severity/prob
    if ($risk == 0 && ($h->status == 'diproses' || $h->status == 'selesai')) {
        $sev = $h->final_tingkat_keparahan ?? $h->tingkat_keparahan;
        $prob = $h->final_kemungkinan_terjadi ?? $h->kemungkinan_terjadi;
        $risk = $sev * $prob;
        $h->risk_score = $risk;
    }

    $kategori = '';
    if ($risk <= 4) {
        $kategori = 'Low';
    } elseif ($risk <= 9) {
        $kategori = 'Medium';
    } elseif ($risk <= 15) {
        $kategori = 'Medium-High';
    } elseif ($risk <= 20) {
        $kategori = 'High';
    } else {
        $kategori = 'Extreme';
    }

    if ($h->kategori_resiko != $kategori || $h->risk_score != $risk) {
        echo "Updating Hazard #{$h->id}: Risk={$risk}, Kategori={$kategori}\n";
        $h->kategori_resiko = $kategori;
        $h->risk_score = $risk;
        $h->save();
    }
}

echo "\nDone!\n";
