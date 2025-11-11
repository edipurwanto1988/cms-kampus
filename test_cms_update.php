<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\CmsController;
use App\Models\Setting;
use App\Models\SettingValue;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing CMS Update functionality...\n\n";

// Test 1: Check if tables exist
echo "1. Checking if settings tables exist:\n";
$settingsTable = \Schema::hasTable('settings');
$settingValuesTable = \Schema::hasTable('setting_values');
echo "   - Settings table exists: " . ($settingsTable ? "YES" : "NO") . "\n";
echo "   - Setting values table exists: " . ($settingValuesTable ? "YES" : "NO") . "\n\n";

if (!$settingsTable || !$settingValuesTable) {
    echo "ERROR: Required tables are missing. Please run migrations.\n";
    exit(1);
}

// Test 2: Check existing settings
echo "2. Checking existing settings:\n";
$settingsCount = Setting::count();
$settingValuesCount = SettingValue::count();
echo "   - Settings count: {$settingsCount}\n";
echo "   - Setting values count: {$settingValuesCount}\n\n";

// Test 3: Create a test setting
echo "3. Creating a test setting:\n";
$testSetting = Setting::firstOrCreate(
    ['key_name' => 'test_setting'],
    ['group_name' => 'test', 'input_type' => 'text', 'is_multilang' => false]
);
echo "   - Test setting created with ID: {$testSetting->id}\n";

// Test 4: Create a test setting value
echo "4. Creating a test setting value:\n";
$testValue = SettingValue::updateOrCreate(
    ['setting_id' => $testSetting->id, 'locale' => null],
    ['value_text' => 'Test value']
);
echo "   - Test setting value created with ID: {$testValue->id}\n";
echo "   - Value: {$testValue->value_text}\n\n";

// Test 5: Test the upsertSetting method directly
echo "5. Testing the upsertSetting method:\n";
$cmsController = new CmsController();
$reflection = new ReflectionClass($cmsController);
$method = $reflection->getMethod('upsertSetting');
$method->setAccessible(true);

$method->invoke($cmsController, 'test_direct', 'test', 'text', 'Direct test value');

// Verify it was saved
$directTest = Setting::where('key_name', 'test_direct')->first();
if ($directTest) {
    $directValue = $directTest->values()->where('locale', null)->first();
    echo "   - Direct test setting saved successfully\n";
    echo "   - Value: {$directValue->value_text}\n";
} else {
    echo "   - ERROR: Direct test setting was not saved\n";
}

echo "\nTest completed. Check the logs for more details.\n";