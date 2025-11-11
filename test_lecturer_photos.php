<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lecturer;

echo "Testing lecturer photos...\n\n";

$lecturers = Lecturer::with(['photo', 'translations'])->get();

foreach ($lecturers as $lecturer) {
    echo "Lecturer ID: {$lecturer->id}\n";
    echo "Name: " . ($lecturer->full_name ?? 'N/A') . "\n";
    echo "Photo Media ID: " . ($lecturer->photo_media_id ?? 'NULL') . "\n";
    
    if ($lecturer->photo) {
        echo "Photo Path: " . $lecturer->photo->path . "\n";
        echo "Photo URL: " . $lecturer->photo_url . "\n";
    } else {
        echo "No photo record found\n";
    }
    
    echo "----------------------------------------\n";
}