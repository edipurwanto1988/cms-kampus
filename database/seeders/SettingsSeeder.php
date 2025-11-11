<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\SettingValue;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings
        $this->createSetting('site_name', 'general', 'text', 'CMS Kampus');
        $this->createSetting('thumbnail_size', 'general', 'text', '300x300');

        // SEO Settings
        $this->createSetting('site_meta_title', 'seo', 'text', 'Information Systems Department');
        $this->createSetting('site_meta_description', 'seo', 'textarea', 'Welcome to the Information Systems Department - Shaping the future of technology and innovation.');

        // Social Media Settings
        $this->createSetting('facebook_url', 'social', 'text', 'https://facebook.com/example');
        $this->createSetting('twitter_url', 'social', 'text', 'https://twitter.com/example');
        $this->createSetting('instagram_url', 'social', 'text', 'https://instagram.com/example');
        $this->createSetting('youtube_url', 'social', 'text', 'https://youtube.com/example');

        // Landing Page Settings - Hero Section
        $this->createSetting('hero_title', 'landing', 'text', 'Welcome to the Forefront of Information Systems');
        $this->createSetting('hero_subtitle', 'landing', 'text', 'Shaping the future of technology, business, and innovation. Discover your potential with us.');
        $this->createSetting('hero_image_url', 'landing', 'text', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAbd74FgEzGI6MQf5glsKr9NrXFsCY4DymJupKsYDddbRw4WOfmaw2zVvyyVu4BE4rKHFT4OiQVyAjRf76JWIsvlf_LTaSMWrGSeRgtDU5PtSjL9FpkjW6VuSo-LCOGRTsC3Yny9ZMkHKZtuqKO35BIYj-uufUUF9RtJnNr11laooMeThVtgm5rYVSQ4qWwKPuOEMcskF-M0PE0s9czr8Z3wt45WQTySpHCX-Eo5gvmrqYFScmnByq7S88iv8yq9kNfjYRLZ3STJw');
        $this->createSetting('hero_button1_text', 'landing', 'text', 'Explore Our Programs');
        $this->createSetting('hero_button1_url', 'landing', 'text', '/articles');
        $this->createSetting('hero_button2_text', 'landing', 'text', 'Contact Us');
        $this->createSetting('hero_button2_url', 'landing', 'text', '/contact');

        // Landing Page Settings - Head of Department
        $this->createSetting('hod_name', 'landing', 'text', 'Dr. Eleanor Vance');
        $this->createSetting('hod_message', 'landing', 'textarea', 'Our mission is to cultivate the next generation of leaders in the digital world. We provide a dynamic learning environment where theory meets practice, empowering our students to solve complex problems and drive innovation.');
        $this->createSetting('hod_photo_url', 'landing', 'text', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDg-CyWHDXlx436hV0Z0KwGO_v4eZDc8HvyMECL4GoKqFvC-vNr3OLOw3Ab0fSrRvcwhYs5Byzskp-NyheLSGfPd5N6Om2vgdzyenZyMFhi6VclQye7Q0HHZrpdsdJUNFdSioH8ngH-B8O42BGLSceKDZaWI4r_VdXpTWjgLYWjuOygKwAu3E-N5eYOofiee7-0cOGyDHUCEW0gBCf9SKhthNGI_jUI7IehmAKAt2BAvRZcLO-J9_PAoidQzte041T1-PdR7Jgr4A');
        $this->createSetting('hod_read_more_url', 'landing', 'text', '/faculty');

        // Landing Page Settings - Section Titles
        $this->createSetting('announcements_title', 'landing', 'text', 'Pengumuman');
        $this->createSetting('articles_title', 'landing', 'text', 'Artikel');
        $this->createSetting('faculty_title', 'landing', 'text', 'Meet Our Faculty');
        $this->createSetting('faculty_subtitle', 'landing', 'text', 'Learn from experienced educators and industry experts dedicated to your success.');
        $this->createSetting('partners_title', 'landing', 'text', 'Our Industry Partners');
        $this->createSetting('partners_subtitle', 'landing', 'text', 'We collaborate with leading organizations to provide our students with real-world experience and career opportunities.');

        // UI Translations for Guest Layout
        $this->createSetting('nav_home', 'ui', 'text', 'Home');
        $this->createSetting('nav_articles', 'ui', 'text', 'Articles');
        $this->createSetting('nav_faculty', 'ui', 'text', 'Faculty');
        $this->createSetting('nav_contact', 'ui', 'text', 'Contact');
        $this->createSetting('btn_apply_now', 'ui', 'text', 'Apply Now');
        $this->createSetting('label_default', 'ui', 'text', 'Default');
        $this->createSetting('footer_address', 'ui', 'text', '123 University Drive<br/>Innovate City, ST 12345');
        $this->createSetting('footer_quick_links', 'ui', 'text', 'Quick Links');
        $this->createSetting('footer_resources', 'ui', 'text', 'Resources');
        $this->createSetting('footer_student_handbook', 'ui', 'text', 'Student Handbook');
        $this->createSetting('footer_career_services', 'ui', 'text', 'Career Services');
        $this->createSetting('footer_university_policies', 'ui', 'text', 'University Policies');
        $this->createSetting('footer_connect', 'ui', 'text', 'Connect With Us');
        $this->createSetting('footer_all_rights_reserved', 'ui', 'text', 'All Rights Reserved');
        
        // Contact Form Translations
        $this->createSetting('contact_title', 'ui', 'text', 'Contact Us');
        $this->createSetting('contact_subtitle', 'ui', 'text', 'Get in touch with our Information Systems Department. We\'re here to help and answer any questions you may have.');
        $this->createSetting('contact_send_message', 'ui', 'text', 'Send us a Message');
        $this->createSetting('contact_form_name', 'ui', 'text', 'Full Name');
        $this->createSetting('contact_form_email', 'ui', 'text', 'Email Address');
        $this->createSetting('contact_form_subject', 'ui', 'text', 'Subject');
        $this->createSetting('contact_form_message', 'ui', 'text', 'Message');
        $this->createSetting('contact_form_send', 'ui', 'text', 'Send Message');
        $this->createSetting('contact_info_title', 'ui', 'text', 'Contact Information');
        $this->createSetting('contact_phone', 'ui', 'text', 'Phone');
        $this->createSetting('contact_email', 'ui', 'text', 'Email');
        $this->createSetting('contact_address', 'ui', 'text', 'Office Address');
        $this->createSetting('contact_call_now', 'ui', 'text', 'Call Now');
        $this->createSetting('contact_send_email', 'ui', 'text', 'Send Email');
        $this->createSetting('contact_get_directions', 'ui', 'text', 'Get Directions');
        $this->createSetting('contact_quick_links', 'ui', 'text', 'Quick Links');
        $this->createSetting('contact_back_home', 'ui', 'text', 'Back to main page');
        $this->createSetting('contact_read_articles', 'ui', 'text', 'Read our latest news and updates');
        $this->createSetting('contact_meet_faculty', 'ui', 'text', 'Meet our teaching staff');
    }

    private function createSetting(string $key, string $group, string $inputType, string $value): void
    {
        // Check if setting already exists to avoid duplicate key error
        $existingSetting = Setting::where('key_name', $key)->first();
        
        if ($existingSetting) {
            $setting = $existingSetting;
        } else {
            // Generate a unique ID for the setting
            $maxId = Setting::max('id') ?? 0;
            $newId = $maxId + 1;
            
            $setting = Setting::create([
                'id' => $newId,
                'key_name' => $key,
                'group_name' => $group,
                'input_type' => $inputType,
                'is_multilang' => false
            ]);
        }

        // Create or update the setting value
        $existingValue = SettingValue::where('setting_id', $setting->id)->where('locale', null)->first();
        
        if (!$existingValue) {
            // Generate a unique ID for the setting value
            $maxId = SettingValue::max('id') ?? 0;
            $newId = $maxId + 1;
            
            SettingValue::create([
                'id' => $newId,
                'setting_id' => $setting->id,
                'locale' => null,
                'value_text' => $value
            ]);
        } else {
            $existingValue->update(['value_text' => $value]);
        }
    }
}