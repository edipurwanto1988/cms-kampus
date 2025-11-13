<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuTranslation;
use App\Models\MenuItemTranslation;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get languages
        $languages = Language::all();
        
        // Create Header Menu
        $headerMenu = Menu::create([
            'name' => 'Header Menu',
            'position' => 'header',
        ]);

        // Create header menu translations
        foreach ($languages as $language) {
            MenuTranslation::create([
                'menu_id' => $headerMenu->id,
                'locale' => $language->code,
                'name' => $this->getMenuTranslation('header', $language->code),
            ]);
        }

        // Create header menu items
        $headerItems = [
            [
                'title' => 'Home',
                'url' => '/en',
                'position' => 1,
                'translations' => [
                    'en' => ['label' => 'Home', 'url' => '/en'],
                    'id' => ['label' => 'Beranda', 'url' => '/id'],
                    'arab' => ['label' => 'الرئيسية', 'url' => '/arab'],
                ]
            ],
            [
                'title' => 'Articles',
                'url' => '/en/articles',
                'position' => 2,
                'translations' => [
                    'en' => ['label' => 'Articles', 'url' => '/en/articles'],
                    'id' => ['label' => 'Artikel', 'url' => '/id/articles'],
                    'arab' => ['label' => 'المقالات', 'url' => '/arab/articles'],
                ]
            ],
            [
                'title' => 'Faculty',
                'url' => '/en/lecturers',
                'position' => 3,
                'translations' => [
                    'en' => ['label' => 'Faculty', 'url' => '/en/lecturers'],
                    'id' => ['label' => 'Dosen', 'url' => '/id/lecturers'],
                    'arab' => ['label' => 'هيئة التدريس', 'url' => '/arab/lecturers'],
                ]
            ],
            [
                'title' => 'Contact',
                'url' => '/en/contact',
                'position' => 4,
                'translations' => [
                    'en' => ['label' => 'Contact', 'url' => '/en/contact'],
                    'id' => ['label' => 'Kontak', 'url' => '/id/contact'],
                    'arab' => ['label' => 'اتصل بنا', 'url' => '/arab/contact'],
                ]
            ],
        ];

    foreach ($headerItems as $itemData) {
    $menuItem = MenuItem::create([
        // Remove the 'id' field, let it auto-increment
        'menu_id' => $headerMenu->id,
        'parent_id' => null,
        'type' => 'internal',
        'target_ref' => null,
        'url_external' => $itemData['url'],
        'sort_order' => $itemData['position'],
        'is_active' => true,
    ]);

    // Now $menuItem->id will be the actual auto-incremented ID
    $defaultLocale = 'en';
    if (isset($itemData['translations'][$defaultLocale])) {
        MenuItemTranslation::create([
            // Remove 'id' field, let it auto-increment too
            'menu_item_id' => $menuItem->id,
            'locale' => $defaultLocale,
            'label' => $itemData['translations'][$defaultLocale]['label'],
            'slug_override' => $itemData['translations'][$defaultLocale]['url'],
            'source_lang' => 'en',
            'is_machine_translated' => false,
            'human_reviewed' => true,
            'translated_at' => now(),
        ]);
    }
}

        // Create Footer Menu
        $footerMenu = Menu::create([
            'name' => 'Footer Menu',
            'position' => 'footer',
        ]);

        // Create footer menu translations
        foreach ($languages as $language) {
            MenuTranslation::create([
                'menu_id' => $footerMenu->id,
                'locale' => $language->code,
                'name' => $this->getMenuTranslation('footer', $language->code),
            ]);
        }

        // Create footer menu items
        $footerItems = [
            [
                'title' => 'Home',
                'url' => '/en',
                'position' => 1,
                'translations' => [
                    'en' => ['label' => 'Home', 'url' => '/en'],
                    'id' => ['label' => 'Beranda', 'url' => '/id'],
                    'arab' => ['label' => 'الرئيسية', 'url' => '/arab'],
                ]
            ],
            [
                'title' => 'Articles',
                'url' => '/en/articles',
                'position' => 2,
                'translations' => [
                    'en' => ['label' => 'Articles', 'url' => '/en/articles'],
                    'id' => ['label' => 'Artikel', 'url' => '/id/articles'],
                    'arab' => ['label' => 'المقالات', 'url' => '/arab/articles'],
                ]
            ],
            [
                'title' => 'Faculty',
                'url' => '/en/lecturers',
                'position' => 3,
                'translations' => [
                    'en' => ['label' => 'Faculty', 'url' => '/en/lecturers'],
                    'id' => ['label' => 'Dosen', 'url' => '/id/lecturers'],
                    'arab' => ['label' => 'هيئة التدريس', 'url' => '/arab/lecturers'],
                ]
            ],
            [
                'title' => 'Contact',
                'url' => '/en/contact',
                'position' => 4,
                'translations' => [
                    'en' => ['label' => 'Contact', 'url' => '/en/contact'],
                    'id' => ['label' => 'Kontak', 'url' => '/id/contact'],
                    'arab' => ['label' => 'اتصل بنا', 'url' => '/arab/contact'],
                ]
            ],
        ];

        foreach ($footerItems as $itemData) {
            $menuItem = MenuItem::create([
                'id' => $itemData['position'] + 10, // Use position + 10 as ID to avoid conflicts
                'menu_id' => $footerMenu->id,
                'parent_id' => null,
                'type' => 'internal',
                'target_ref' => null,
                'url_external' => $itemData['url'],
                'sort_order' => $itemData['position'],
                'is_active' => true,
            ]);

            // Create menu item translation only for the default locale (en)
            $defaultLocale = 'en';
            if (isset($itemData['translations'][$defaultLocale])) {
                MenuItemTranslation::create([
                    'id' => $menuItem->id * 100 + 2, // Generate unique ID
                    'menu_item_id' => $menuItem->id,
                    'locale' => $defaultLocale,
                    'label' => $itemData['translations'][$defaultLocale]['label'],
                    'slug_override' => $itemData['translations'][$defaultLocale]['url'],
                    'source_lang' => 'en',
                    'is_machine_translated' => false,
                    'human_reviewed' => true,
                    'translated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Get menu translation for different languages
     */
    private function getMenuTranslation($type, $locale)
    {
        $translations = [
            'header' => [
                'en' => 'Header Menu',
                'id' => 'Menu Header',
                'arab' => 'قائمة الهيدر',
            ],
            'footer' => [
                'en' => 'Footer Menu',
                'id' => 'Menu Footer',
                'arab' => 'قائمة الفوتر',
            ],
        ];

        return $translations[$type][$locale] ?? $translations[$type]['en'];
    }
}