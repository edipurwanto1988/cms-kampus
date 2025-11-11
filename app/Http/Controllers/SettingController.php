<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SettingValue;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $groups = ['general', 'seo', 'social', 'landing'];
        $settings = Setting::with(['values' => function ($q) {
            $q->whereNull('locale')->latest();
        }])->whereIn('group_name', $groups)->get()->groupBy('group_name');

        return view('settings.index', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'thumbnail_size' => ['required', 'regex:/^\d+\s*[xX]\s*\d+$/'],
        ]);

        $this->upsertSetting('site_name', 'general', 'text', $data['site_name'] ?? 'CMS Kampus');
        $this->upsertSetting('thumbnail_size', 'general', 'text', $data['thumbnail_size']);
        return back()->with('success', 'General settings saved successfully.');
    }

    public function updateSeo(Request $request)
    {
        $data = $request->validate([
            'meta_title' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
        ]);
        $this->upsertSetting('site_meta_title', 'seo', 'text', $data['meta_title'] ?? '');
        $this->upsertSetting('site_meta_description', 'seo', 'textarea', $data['meta_description'] ?? '');
        return back()->with('success', 'SEO disimpan');
    }

    public function updateSocial(Request $request)
    {
        $data = $request->validate([
            'facebook_url' => ['nullable', 'url'],
            'twitter_url' => ['nullable', 'url'],
            'instagram_url' => ['nullable', 'url'],
            'youtube_url' => ['nullable', 'url'],
        ]);
        foreach ($data as $key => $value) {
            $this->upsertSetting($key, 'social', 'text', $value ?? '');
        }
        return back()->with('success', 'Sosial Media disimpan');
    }

    public function updateLandingPage(Request $request)
    {
        $data = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'hero_image_url' => ['nullable', 'url'],
            'hero_button1_text' => ['nullable', 'string', 'max:50'],
            'hero_button1_url' => ['nullable', 'string', 'max:255'],
            'hero_button2_text' => ['nullable', 'string', 'max:50'],
            'hero_button2_url' => ['nullable', 'string', 'max:255'],
            'hod_name' => ['nullable', 'string', 'max:255'],
            'hod_message' => ['nullable', 'string'],
            'hod_photo_url' => ['nullable', 'url'],
            'hod_read_more_url' => ['nullable', 'string', 'max:255'],
            'announcements_title' => ['nullable', 'string', 'max:255'],
            'articles_title' => ['nullable', 'string', 'max:255'],
            'faculty_title' => ['nullable', 'string', 'max:255'],
            'faculty_subtitle' => ['nullable', 'string', 'max:500'],
            'partners_title' => ['nullable', 'string', 'max:255'],
            'partners_subtitle' => ['nullable', 'string', 'max:500'],
        ]);

        // Hero section settings
        $this->upsertSetting('hero_title', 'landing', 'text', $data['hero_title'] ?? '');
        $this->upsertSetting('hero_subtitle', 'landing', 'text', $data['hero_subtitle'] ?? '');
        $this->upsertSetting('hero_image_url', 'landing', 'text', $data['hero_image_url'] ?? '');
        $this->upsertSetting('hero_button1_text', 'landing', 'text', $data['hero_button1_text'] ?? '');
        $this->upsertSetting('hero_button1_url', 'landing', 'text', $data['hero_button1_url'] ?? '');
        $this->upsertSetting('hero_button2_text', 'landing', 'text', $data['hero_button2_text'] ?? '');
        $this->upsertSetting('hero_button2_url', 'landing', 'text', $data['hero_button2_url'] ?? '');

        // Head of Department section settings
        $this->upsertSetting('hod_name', 'landing', 'text', $data['hod_name'] ?? '');
        $this->upsertSetting('hod_message', 'landing', 'textarea', $data['hod_message'] ?? '');
        $this->upsertSetting('hod_photo_url', 'landing', 'text', $data['hod_photo_url'] ?? '');
        $this->upsertSetting('hod_read_more_url', 'landing', 'text', $data['hod_read_more_url'] ?? '');

        // Section titles
        $this->upsertSetting('announcements_title', 'landing', 'text', $data['announcements_title'] ?? '');
        $this->upsertSetting('articles_title', 'landing', 'text', $data['articles_title'] ?? '');
        $this->upsertSetting('faculty_title', 'landing', 'text', $data['faculty_title'] ?? '');
        $this->upsertSetting('faculty_subtitle', 'landing', 'text', $data['faculty_subtitle'] ?? '');
        $this->upsertSetting('partners_title', 'landing', 'text', $data['partners_title'] ?? '');
        $this->upsertSetting('partners_subtitle', 'landing', 'text', $data['partners_subtitle'] ?? '');

        return back()->with('success', 'Landing page settings saved successfully.');
    }

    private function upsertSetting(string $key, string $group, string $inputType, ?string $value): void
    {
        $setting = Setting::firstOrCreate(
            ['key_name' => $key],
            ['group_name' => $group, 'input_type' => $inputType, 'is_multilang' => false]
        );
        SettingValue::create([
            'setting_id' => $setting->id,
            'locale' => null,
            'value_text' => $value,
        ]);
    }
}


