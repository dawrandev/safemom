<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\RegistrationService;
use App\Models\User;

echo "=== Testing Registration System ===\n\n";

// Test 1: Check User model
echo "1. Checking User model fillable fields...\n";
$user = new User();
$fillable = $user->getFillable();
echo "Fillable: " . implode(', ', $fillable) . "\n";

// Test 2: Check database structure
echo "\n2. Checking users table structure...\n";
try {
    $columns = DB::select("DESCRIBE users");
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) " .
             ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') .
             ($column->Key ? " [{$column->Key}]" : '') . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test 3: Test RegistrationService
echo "\n3. Testing RegistrationService...\n";
$service = new RegistrationService();

echo "- validateName('Ali'): " . ($service->validateName('Ali') ? 'OK' : 'FAIL') . "\n";
echo "- validateName('A'): " . ($service->validateName('A') ? 'FAIL' : 'OK') . "\n";
echo "- validatePhone('+998901234567'): " . ($service->validatePhone('+998901234567') ? 'OK' : 'FAIL') . "\n";
echo "- formatPhone('998901234567'): " . $service->formatPhone('998901234567') . "\n";

echo "\n=== All tests completed! ===\n";
