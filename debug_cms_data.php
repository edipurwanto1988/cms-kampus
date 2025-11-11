<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use App\Models\SettingValue;

echo "=== DEBUGGING CMS DATA FLOW ===\n\n";

// 1. Check if settings exist in database
echo "1. Checking settings in database:\n";
$landingSettings = Setting::with(['values'])->where('group_name', 'landing')->get();
echo "Found " . $landingSettings->count() . " landing settings\n";

foreach ($landingSettings as $setting) {
    echo "- {$setting->key_name} ({$setting->values->count()} values)\n";
    foreach ($setting->values as $value) {
        echo "  - Locale: " . ($value->locale ?? 'NULL') . " | Value: " . substr($value->value_text ?? 'NULL', 0, 50) . "...\n";
    }
}

echo "\n2. Checking SEO settings:\n";
$seoSettings = Setting::with(['values'])->where('group_name', 'seo')->get();
echo "Found " . $seoSettings->count() . " SEO settings\n";

foreach ($seoSettings as $setting) {
    echo "- {$setting->key_name} ({$setting->values->count()} values)\n";
    foreach ($setting->values as $value) {
        echo "  - Locale: " . ($value->locale ?? 'NULL') . " | Value: " . substr($value->value_text ?? 'NULL', 0, 50) . "...\n";
    }
}

// 3. Test GuestController data retrieval
echo "\n3. Testing GuestController data retrieval:\n";
$locale = 'id'; // Default locale

$landingSettingsTest = Setting::with(['values' => function ($query) use ($locale) {
    $query->where(function($q) use ($locale) {
        $q->where('locale', $locale)
          ->orWhereNull('locale');
    })->latest();
}])->where('group_name', 'landing')->get()->keyBy('key_name');

echo "GuestController would retrieve " . $landingSettingsTest->count() . " landing settings\n";

// Test a few specific settings
$testKeys = ['hero_title', 'hero_subtitle', 'announcements_title'];
foreach ($testKeys as $key) {
    if ($landingSettingsTest->has($key)) {
        $setting = $landingSettingsTest[$key];
        $translatedValue = $setting->values->firstWhere('locale', $locale);
        $defaultValue = $setting->values->firstWhere('locale', null);
        
        echo "- {$key}: " . 
             ($translatedValue ? $translatedValue->value_text : 
              ($defaultValue ? $defaultValue->value_text : 'NOT FOUND')) . "\n";
    } else {
        echo "- {$key}: NOT FOUND\n";
    }
}

echo "\n=== END DEBUG ===\n";