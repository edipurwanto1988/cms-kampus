<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SettingValue;
use App\Models\Lecturer;
use App\Models\Post;
use App\Models\Partner;
use App\Models\Language;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function index()
    {
        // Load all languages
        $languages = Language::getAllOrdered();
        
        // Load all settings for editing with all language values
        $settings = Setting::with(['values' => function ($query) {
            $query->latest();
        }])->get()->groupBy('group_name');

        // Organize settings by language for easier access in view
        $settingsByLanguage = [];
        foreach ($languages as $language) {
            $settingsByLanguage[$language->code] = Setting::with(['values' => function ($query) use ($language) {
                $query->where('locale', $language->code)->latest();
            }])->get()->keyBy('key_name');
        }

        // Load data for preview
        $featuredLecturers = Lecturer::active()->featured()->with(['translations', 'photo'])->limit(4)->get();
        $latestPosts = Post::with(['translations', 'category'])->latest()->limit(6)->get();
        $partners = Partner::active()->get();

        return view('cms.index', compact('settings', 'settingsByLanguage', 'languages', 'featuredLecturers', 'latestPosts', 'partners'));
    }

    public function update(Request $request)
    {
        $languages = Language::getAllOrdered();
        $multilingualKeys = [
            'site_name', 'site_meta_title', 'site_meta_description',
            'hero_title', 'hero_subtitle', 'hero_button1_text', 'hero_button2_text',
            'hod_name', 'hod_message', 'announcements_title', 'articles_title',
            'faculty_title', 'faculty_subtitle', 'partners_title', 'partners_subtitle'
        ];

        // Validate for each language
        $validatedData = [];
        foreach ($languages as $language) {
            $rules = [];
            
            // General settings
            $rules["site_name_{$language->code}"] = ['nullable', 'string', 'max:255'];
            $rules["thumbnail_size"] = ['nullable', 'string']; // Non-multilingual
            
            // SEO settings
            $rules["site_meta_title_{$language->code}"] = ['nullable', 'string'];
            $rules["site_meta_description_{$language->code}"] = ['nullable', 'string'];
            
            // Social settings (non-multilingual)
            $rules["facebook_url"] = ['nullable', 'string'];
            $rules["twitter_url"] = ['nullable', 'string'];
            $rules["instagram_url"] = ['nullable', 'string'];
            $rules["youtube_url"] = ['nullable', 'string'];
            
            // Landing page settings
            $rules["hero_title_{$language->code}"] = ['nullable', 'string', 'max:255'];
            $rules["hero_subtitle_{$language->code}"] = ['nullable', 'string', 'max:500'];
            $rules["hero_image_url"] = ['nullable', 'string']; // Non-multilingual
            $rules["hero_button1_text_{$language->code}"] = ['nullable', 'string', 'max:50'];
            $rules["hero_button1_url"] = ['nullable', 'string', 'max:255']; // Non-multilingual
            $rules["hero_button2_text_{$language->code}"] = ['nullable', 'string', 'max:50'];
            $rules["hero_button2_url"] = ['nullable', 'string', 'max:255']; // Non-multilingual
            $rules["hod_name_{$language->code}"] = ['nullable', 'string', 'max:255'];
            $rules["hod_message_{$language->code}"] = ['nullable', 'string'];
            $rules["hod_photo_url"] = ['nullable', 'string']; // Non-multilingual
            $rules["hod_read_more_url"] = ['nullable', 'string', 'max:255']; // Non-multilingual
            $rules["announcements_title_{$language->code}"] = ['nullable', 'string', 'max:255'];
            $rules["articles_title_{$language->code}"] = ['nullable', 'string', 'max:255'];
            $rules["faculty_title_{$language->code}"] = ['nullable', 'string', 'max:255'];
            $rules["faculty_subtitle_{$language->code}"] = ['nullable', 'string', 'max:500'];
            $rules["partners_title_{$language->code}"] = ['nullable', 'string', 'max:255'];
            $rules["partners_subtitle_{$language->code}"] = ['nullable', 'string', 'max:500'];
            
            $validatedData[$language->code] = $request->validate($rules);
        }

        // Update all settings for each language
        foreach ($languages as $language) {
            $data = $validatedData[$language->code];
            
            foreach ($data as $key => $value) {
                // Remove language suffix from key
                $originalKey = str_replace("_{$language->code}", "", $key);
                
                // Determine if this is a multilingual setting
                $isMultilingual = in_array($originalKey, $multilingualKeys);
                
                if ($isMultilingual) {
                    $this->upsertSetting($originalKey, $this->getSettingGroup($originalKey), $this->getInputType($originalKey), $value, $language->code, true);
                } else {
                    // For non-multilingual settings, only save once (for the first language)
                    if ($language === $languages->first()) {
                        $this->upsertSetting($originalKey, $this->getSettingGroup($originalKey), $this->getInputType($originalKey), $value, null, false);
                    }
                }
            }
        }

        return back()->with('success', 'CMS settings updated successfully!');
    }

    private function getSettingGroup($key)
    {
        $groups = [
            'site_name' => 'general',
            'thumbnail_size' => 'general',
            'site_meta_title' => 'seo',
            'site_meta_description' => 'seo',
            'facebook_url' => 'social',
            'twitter_url' => 'social',
            'instagram_url' => 'social',
            'youtube_url' => 'social',
            'hero_title' => 'landing',
            'hero_subtitle' => 'landing',
            'hero_image_url' => 'landing',
            'hero_button1_text' => 'landing',
            'hero_button1_url' => 'landing',
            'hero_button2_text' => 'landing',
            'hero_button2_url' => 'landing',
            'hod_name' => 'landing',
            'hod_message' => 'landing',
            'hod_photo_url' => 'landing',
            'hod_read_more_url' => 'landing',
            'announcements_title' => 'landing',
            'articles_title' => 'landing',
            'faculty_title' => 'landing',
            'faculty_subtitle' => 'landing',
            'partners_title' => 'landing',
            'partners_subtitle' => 'landing',
        ];

        return $groups[$key] ?? 'general';
    }

    private function getInputType($key)
    {
        $types = [
            'site_meta_description' => 'textarea',
            'hod_message' => 'textarea',
            'hero_subtitle' => 'textarea',
            'faculty_subtitle' => 'textarea',
            'partners_subtitle' => 'textarea',
        ];

        return $types[$key] ?? 'text';
    }

    private function upsertSetting(string $key, string $group, string $inputType, ?string $value, ?string $locale = null, bool $isMultilingual = false): void
    {
        $setting = Setting::firstOrCreate(
            ['key_name' => $key],
            ['group_name' => $group, 'input_type' => $inputType, 'is_multilang' => $isMultilingual]
        );
        
        SettingValue::updateOrCreate(
            ['setting_id' => $setting->id, 'locale' => $locale],
            ['value_text' => $value]
        );
    }
}
