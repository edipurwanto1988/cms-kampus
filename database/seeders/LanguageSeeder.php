<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'id', 'name' => 'Indonesian', 'is_default' => true],
            ['code' => 'en', 'name' => 'English', 'is_default' => false],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                [
                    'name' => $language['name'],
                    'is_default' => $language['is_default'],
                ]
            );
        }
    }
}