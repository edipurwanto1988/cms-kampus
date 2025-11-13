<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuTranslation;
use Illuminate\Database\Seeder;

class SimpleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get languages
        $languages = Language::all();
        
        // Create Header Menus
        $headerMenus = [
            [
                'name' => 'Home',
                'location' => 'header',
                'url' => '/en',
                'target' => '_self',
                'position' => 1,
                'is_active' => true,
                'translations' => [
                    'en' => 'Home',
                    'id' => 'Beranda',
                    'arab' => 'الرئيسية',
                ]
            ],
            [
                'name' => 'Articles',
                'location' => 'header',
                'url' => '/en/articles',
                'target' => '_self',
                'position' => 2,
                'is_active' => true,
                'translations' => [
                    'en' => 'Articles',
                    'id' => 'Artikel',
                    'arab' => 'المقالات',
                ]
            ],
            [
                'name' => 'Faculty',
                'location' => 'header',
                'url' => '/en/lecturers',
                'target' => '_self',
                'position' => 3,
                'is_active' => true,
                'translations' => [
                    'en' => 'Faculty',
                    'id' => 'Dosen',
                    'arab' => 'هيئة التدريس',
                ]
            ],
            [
                'name' => 'Contact',
                'location' => 'header',
                'url' => '/en/contact',
                'target' => '_self',
                'position' => 4,
                'is_active' => true,
                'translations' => [
                    'en' => 'Contact',
                    'id' => 'Kontak',
                    'arab' => 'اتصل بنا',
                ]
            ],
        ];

        foreach ($headerMenus as $menuData) {
            $menu = Menu::create([
                'name' => $menuData['name'],
                'location' => $menuData['location'],
                'url' => $menuData['url'],
                'target' => $menuData['target'],
                'position' => $menuData['position'],
                'is_active' => $menuData['is_active'],
            ]);

            // Create menu translations
            foreach ($languages as $language) {
                if (isset($menuData['translations'][$language->code])) {
                    MenuTranslation::create([
                        'menu_id' => $menu->id,
                        'locale' => $language->code,
                        'name' => $menuData['translations'][$language->code],
                    ]);
                }
            }
        }

        // Create Footer Menus
        $footerMenus = [
            [
                'name' => 'Home',
                'location' => 'footer',
                'url' => '/en',
                'target' => '_self',
                'position' => 1,
                'is_active' => true,
                'translations' => [
                    'en' => 'Home',
                    'id' => 'Beranda',
                    'arab' => 'الرئيسية',
                ]
            ],
            [
                'name' => 'Articles',
                'location' => 'footer',
                'url' => '/en/articles',
                'target' => '_self',
                'position' => 2,
                'is_active' => true,
                'translations' => [
                    'en' => 'Articles',
                    'id' => 'Artikel',
                    'arab' => 'المقالات',
                ]
            ],
            [
                'name' => 'Faculty',
                'location' => 'footer',
                'url' => '/en/lecturers',
                'target' => '_self',
                'position' => 3,
                'is_active' => true,
                'translations' => [
                    'en' => 'Faculty',
                    'id' => 'Dosen',
                    'arab' => 'هيئة التدريس',
                ]
            ],
            [
                'name' => 'Contact',
                'location' => 'footer',
                'url' => '/en/contact',
                'target' => '_self',
                'position' => 4,
                'is_active' => true,
                'translations' => [
                    'en' => 'Contact',
                    'id' => 'Kontak',
                    'arab' => 'اتصل بنا',
                ]
            ],
        ];

        foreach ($footerMenus as $menuData) {
            $menu = Menu::create([
                'name' => $menuData['name'],
                'location' => $menuData['location'],
                'url' => $menuData['url'],
                'target' => $menuData['target'],
                'position' => $menuData['position'],
                'is_active' => $menuData['is_active'],
            ]);

            // Create menu translations
            foreach ($languages as $language) {
                if (isset($menuData['translations'][$language->code])) {
                    MenuTranslation::create([
                        'menu_id' => $menu->id,
                        'locale' => $language->code,
                        'name' => $menuData['translations'][$language->code],
                    ]);
                }
            }
        }
    }
}