<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hazard;

$h = Hazard::where('status', 'selesai')->orderBy('updated_at', 'desc')->first();
if ($h) {
    $h->report_selesai = $h->updated_at->subMinutes(30);
    $h->save();
    echo "Updated ID: {$h->id} | New report_selesai: {$h->report_selesai}\n";
}
