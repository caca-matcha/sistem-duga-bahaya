<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Dropping columns...\n";
Schema::table('users', function (Blueprint $table) {
    $table->dropColumn(['npk', 'division', 'department', 'organization_unit', 'job_family']);
});
echo "Done.\n";
