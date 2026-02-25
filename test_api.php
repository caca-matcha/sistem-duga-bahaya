<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$url = 'https://msa-be.dharmagroup.co.id/api/data/company';
$key = 'eyJjb21wYW55IjoiQUJDIiwidGltZSI6MT';

try {
    $response = Http::withoutVerifying()->withToken($key)->get($url);
    echo 'Status: '.$response->status()."\n";
    echo 'Body: '.$response->body()."\n";
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
