<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\SettingValue;

class UITranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            // Navigation
            'nav_home' => [
                'id' => 'Beranda',
                'en' => 'Home',
            ],
            'nav_articles' => [
                'id' => 'Artikel',
                'en' => 'Articles',
            ],
            'nav_faculty' => [
                'id' => 'Fakultas',
                'en' => 'Faculty',
            ],
            'nav_contact' => [
                'id' => 'Kontak',
                'en' => 'Contact',
            ],
            
            // Buttons
            'btn_apply_now' => [
                'id' => 'Daftar Sekarang',
                'en' => 'Apply Now',
            ],
            
            // Labels
            'label_default' => [
                'id' => 'Default',
                'en' => 'Default',
            ],
            
            // Footer
            'footer_address' => [
                'id' => 'Jalan Universitas 123<br/>Kota Inovasi, ST 12345',
                'en' => '123 University Drive<br/>Innovate City, ST 12345',
            ],
            'footer_quick_links' => [
                'id' => 'Tautan Cepat',
                'en' => 'Quick Links',
            ],
            'footer_resources' => [
                'id' => 'Sumber Daya',
                'en' => 'Resources',
            ],
            'footer_student_handbook' => [
                'id' => 'Buku Panduan Mahasiswa',
                'en' => 'Student Handbook',
            ],
            'footer_career_services' => [
                'id' => 'Layanan Karir',
                'en' => 'Career Services',
            ],
            'footer_university_policies' => [
                'id' => 'Kebijakan Universitas',
                'en' => 'University Policies',
            ],
            'footer_connect' => [
                'id' => 'Hubungi Kami',
                'en' => 'Connect With Us',
            ],
            'footer_all_rights_reserved' => [
                'id' => 'Hak Cipta Dilindungi',
                'en' => 'All Rights Reserved',
            ],
            
            // Contact Form
            'contact_title' => [
                'id' => 'Hubungi Kami',
                'en' => 'Contact Us',
            ],
            'contact_subtitle' => [
                'id' => 'Hubungi Departemen Sistem Informasi kami. Kami siap membantu dan menjawab pertanyaan yang Anda miliki.',
                'en' => 'Get in touch with our Information Systems Department. We\'re here to help and answer any questions you may have.',
            ],
            'contact_send_message' => [
                'id' => 'Kirim Pesan',
                'en' => 'Send us a Message',
            ],
            'contact_form_name' => [
                'id' => 'Nama Lengkap',
                'en' => 'Full Name',
            ],
            'contact_form_email' => [
                'id' => 'Alamat Email',
                'en' => 'Email Address',
            ],
            'contact_form_subject' => [
                'id' => 'Subjek',
                'en' => 'Subject',
            ],
            'contact_form_message' => [
                'id' => 'Pesan',
                'en' => 'Message',
            ],
            'contact_form_send' => [
                'id' => 'Kirim Pesan',
                'en' => 'Send Message',
            ],
            'contact_info_title' => [
                'id' => 'Informasi Kontak',
                'en' => 'Contact Information',
            ],
            'contact_phone' => [
                'id' => 'Telepon',
                'en' => 'Phone',
            ],
            'contact_email' => [
                'id' => 'Email',
                'en' => 'Email',
            ],
            'contact_address' => [
                'id' => 'Alamat Kantor',
                'en' => 'Office Address',
            ],
            'contact_call_now' => [
                'id' => 'Hubungi Sekarang',
                'en' => 'Call Now',
            ],
            'contact_send_email' => [
                'id' => 'Kirim Email',
                'en' => 'Send Email',
            ],
            'contact_get_directions' => [
                'id' => 'Petunjuk Arah',
                'en' => 'Get Directions',
            ],
            'contact_quick_links' => [
                'id' => 'Tautan Cepat',
                'en' => 'Quick Links',
            ],
            'contact_back_home' => [
                'id' => 'Kembali ke halaman utama',
                'en' => 'Back to main page',
            ],
            'contact_read_articles' => [
                'id' => 'Baca berita dan pembaruan terbaru kami',
                'en' => 'Read our latest news and updates',
            ],
            'contact_meet_faculty' => [
                'id' => 'Temui staf pengajar kami',
                'en' => 'Meet our teaching staff',
            ],
        ];

        foreach ($translations as $key => $values) {
            $setting = Setting::where('key_name', $key)->where('group_name', 'ui')->first();
            
            if ($setting) {
                foreach ($values as $locale => $value) {
                    // Check if translation already exists
                    $existingTranslation = SettingValue::where('setting_id', $setting->id)
                        ->where('locale', $locale)
                        ->first();
                    
                    if (!$existingTranslation) {
                        // Generate a unique ID for the setting value
                        $maxId = SettingValue::max('id') ?? 0;
                        $newId = $maxId + 1;
                        
                        SettingValue::create([
                            'id' => $newId,
                            'setting_id' => $setting->id,
                            'locale' => $locale,
                            'value_text' => $value
                        ]);
                    }
                }
            }
        }
    }
}