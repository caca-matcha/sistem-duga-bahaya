<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Hazard;

$hazards = Hazard::all(['id', 'status', 'risk_score', 'kategori_resiko']);
foreach ($hazards as $h) {
    echo sprintf("ID: %d | Status: %s | Risk: %d | Kategori: %s\n", 
        $h->id, 
        $h->status, 
        $h->risk_score, 
        $h->kategori_resiko ?: 'NULL'
    );
}
