<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Language;

// Create a test category if it doesn't exist
$category = Category::firstOrCreate(['is_active' => 1]);
$category->save();

// Create category translation for Indonesian
$categoryTranslation = CategoryTranslation::firstOrCreate([
    'category_id' => $category->id,
    'locale' => 'id'
]);
$categoryTranslation->name = 'Aktivitas';
$categoryTranslation->save();

// Get Indonesian language
$indonesian = Language::where('code', 'id')->first();

// Create a test post
$post = Post::create([
    'category_id' => $category->id,
    'author' => 'Admin',
    'featured_image' => null,
    'is_active' => 1,
    'created_at' => now(),
    'updated_at' => now()
]);

// Create Indonesian translation with the slug we want to test
$postTranslation = PostTranslation::create([
    'post_id' => $post->id,
    'locale' => 'id',
    'title' => 'Aktivitas Unilak',
    'slug' => 'aktifitas-unilak',
    'content' => '<p>Ini adalah konten artikel tentang aktivitas di Universitas Lancang Kuning. Artikel ini berisi informasi mengenai berbagai kegiatan akademik dan non-akademik yang dilaksanakan di kampus.</p><p>Universitas Lancang Kuning memiliki berbagai macam aktivitas yang melibatkan mahasiswa, dosen, dan staf. Kegiatan ini bertujuan untuk meningkatkan kualitas pendidikan dan pengembangan soft skills mahasiswa.</p>',
    'excerpt' => 'Artikel tentang aktivitas di Universitas Lancang Kuning',
    'meta_description' => 'Informasi lengkap mengenai aktivitas kampus Universitas Lancang Kuning',
    'meta_keywords' => 'aktivitas, unilak, kampus, kegiatan mahasiswa',
    'created_at' => now(),
    'updated_at' => now()
]);

// Create English translation as well
$postTranslationEn = PostTranslation::create([
    'post_id' => $post->id,
    'locale' => 'en',
    'title' => 'Unilak Activities',
    'slug' => 'unilak-activities',
    'content' => '<p>This is an article about activities at Lancang Kuning University. This article contains information about various academic and non-academic activities carried out on campus.</p><p>Lancang Kuning University has various types of activities involving students, lecturers, and staff. These activities aim to improve the quality of education and develop students\' soft skills.</p>',
    'excerpt' => 'Article about activities at Lancang Kuning University',
    'meta_description' => 'Complete information about campus activities at Lancang Kuning University',
    'meta_keywords' => 'activities, unilak, campus, student activities',
    'created_at' => now(),
    'updated_at' => now()
]);

echo "Test article created successfully!\n";
echo "Post ID: " . $post->id . "\n";
echo "Indonesian Slug: aktifitas-unilak\n";
echo "English Slug: unilak-activities\n";
echo "\nYou can now test the article at:\n";
echo "http://127.0.0.1:8001/id/articles/aktifitas-unilak\n";
echo "http://127.0.0.1:8001/en/articles/unilak-activities\n";