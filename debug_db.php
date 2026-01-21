<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Columns in users table:\n";
print_r(Schema::getColumnListing('users'));

echo "\nUsers count: " . User::count() . "\n";
if (User::count() > 0) {
    echo "First user:\n";
    print_r(User::first()->toArray());
}

try {
    echo "\nIndexes:\n";
    $indexes = DB::select('SHOW INDEXES FROM users');
    foreach ($indexes as $index) {
        echo $index->Key_name . " -> " . $index->Column_name . "\n";
    }
} catch (\Exception $e) {
    echo "Could not list indexes: " . $e->getMessage();
}
