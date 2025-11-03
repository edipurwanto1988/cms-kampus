<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a test page if it doesn't exist
$page = \App\Models\Page::find(1);
if (!$page) {
    $page = \App\Models\Page::create([
        'key_name' => 'test_page',
        'is_active' => true,
        'created_by' => 1,
        'updated_by' => 1
    ]);
    
    $defaultLang = \App\Models\Language::getDefault();
    \App\Models\PageTranslation::create([
        'page_id' => $page->id,
        'locale' => $defaultLang->code,
        'title' => 'Test Page',
        'slug' => 'test-page',
        'excerpt' => 'This is a test page',
        'content_html' => '<p>This is test content for the page.</p>',
        'translated_at' => now()
    ]);
    
    echo "Created test page with ID: " . $page->id . "\n";
} else {
    echo "Page with ID 1 already exists\n";
}

echo "You can now access the page edit form at: http://127.0.0.1:8000/pages/1/edit\n";
echo "You can also test CKEditor at: http://127.0.0.1:8000/test-ckeditor\n";