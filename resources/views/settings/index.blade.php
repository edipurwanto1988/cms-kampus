@extends('layouts.app')

@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Setting</h1>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Umum</h2>
        <form method="POST" action="{{ route('settings.general') }}" class="space-y-2">
            @csrf
            <label class="block">Site Name</label>
            <input name="site_name" type="text" value="{{ optional(optional($settings['general'] ?? collect())->firstWhere('key_name','site_name'))->values->first())->value_text ?? 'CMS Kampus' }}" class="border p-2 w-full mb-2" />
            
            <label class="block">Ukuran Thumbnail (mis. 300x300)</label>
            <input name="thumbnail_size" type="text" value="{{ optional(optional($settings['general'] ?? collect())->firstWhere('key_name','thumbnail_size'))->values->first())->value_text ?? '' }}" class="border p-2 w-full" />
            <button class="bg-blue-600 text-white px-4 py-2">Simpan Umum</button>
        </form>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">SEO</h2>
        <form method="POST" action="{{ route('settings.seo') }}" class="space-y-2">
            @csrf
            <label class="block">Site Meta Title</label>
            <input name="meta_title" type="text" value="{{ optional(optional($settings['seo'] ?? collect())->firstWhere('key_name','site_meta_title'))->values->first())->value_text ?? '' }}" class="border p-2 w-full" />
            <label class="block">Site Meta Description</label>
            <textarea name="meta_description" class="border p-2 w-full">{{ optional(optional($settings['seo'] ?? collect())->firstWhere('key_name','site_meta_description'))->values->first())->value_text ?? '' }}</textarea>
            <button class="bg-blue-600 text-white px-4 py-2">Simpan SEO</button>
        </form>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Sosial Media</h2>
        <form method="POST" action="{{ route('settings.social') }}" class="space-y-2">
            @csrf
            <label class="block">Facebook URL</label>
            <input name="facebook_url" type="url" value="{{ optional(optional($settings['social'] ?? collect())->firstWhere('key_name','facebook_url'))->values->first())->value_text ?? '' }}" class="border p-2 w-full" />
            <label class="block">Twitter URL</label>
            <input name="twitter_url" type="url" value="{{ optional(optional($settings['social'] ?? collect())->firstWhere('key_name','twitter_url'))->values->first())->value_text ?? '' }}" class="border p-2 w-full" />
            <label class="block">Instagram URL</label>
            <input name="instagram_url" type="url" value="{{ optional(optional($settings['social'] ?? collect())->firstWhere('key_name','instagram_url'))->values->first())->value_text ?? '' }}" class="border p-2 w-full" />
            <label class="block">YouTube URL</label>
            <input name="youtube_url" type="url" value="{{ optional(optional($settings['social'] ?? collect())->firstWhere('key_name','youtube_url'))->values->first())->value_text ?? '' }}" class="border p-2 w-full" />
            <button class="bg-blue-600 text-white px-4 py-2">Simpan Sosial</button>
        </form>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Landing Page</h2>
        <form method="POST" action="{{ route('settings.landing') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-medium mb-2">Hero Section</h3>
                    
                    <label class="block mb-1">Hero Title</label>
                    <input name="hero_title" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_title')->values->first()->value_text ?? 'Welcome to the Forefront of Information Systems' }}" class="border p-2 w-full mb-2" />
                    
                    <label class="block mb-1">Hero Subtitle</label>
                    <textarea name="hero_subtitle" class="border p-2 w-full mb-2" rows="2">{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_subtitle')->values->first()->value_text ?? 'Shaping the future of technology, business, and innovation. Discover your potential with us.' }}</textarea>
                    
                    <label class="block mb-1">Hero Image URL</label>
                    <input name="hero_image_url" type="url" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_image_url')->values->first()->value_text ?? '' }}" class="border p-2 w-full mb-2" />
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block mb-1">Button 1 Text</label>
                            <input name="hero_button1_text" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_button1_text')->values->first()->value_text ?? 'Explore Our Programs' }}" class="border p-2 w-full mb-2" />
                        </div>
                        <div>
                            <label class="block mb-1">Button 1 URL</label>
                            <input name="hero_button1_url" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_button1_url')->values->first()->value_text ?? route('guest.articles') }}" class="border p-2 w-full mb-2" />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block mb-1">Button 2 Text</label>
                            <input name="hero_button2_text" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_button2_text')->values->first()->value_text ?? 'Contact Us' }}" class="border p-2 w-full mb-2" />
                        </div>
                        <div>
                            <label class="block mb-1">Button 2 URL</label>
                            <input name="hero_button2_url" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_button2_url')->values->first()->value_text ?? route('guest.contact') }}" class="border p-2 w-full mb-2" />
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium mb-2">Head of Department</h3>
                    
                    <label class="block mb-1">HOD Name</label>
                    <input name="hod_name" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hod_name')->values->first()->value_text ?? 'Dr. Eleanor Vance' }}" class="border p-2 w-full mb-2" />
                    
                    <label class="block mb-1">HOD Message</label>
                    <textarea name="hod_message" class="border p-2 w-full mb-2" rows="3">{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hod_message')->values->first()->value_text ?? 'Our mission is to cultivate the next generation of leaders in the digital world. We provide a dynamic learning environment where theory meets practice, empowering our students to solve complex problems and drive innovation.' }}</textarea>
                    
                    <label class="block mb-1">HOD Photo URL</label>
                    <input name="hod_photo_url" type="url" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hod_photo_url')->values->first()->value_text ?? '' }}" class="border p-2 w-full mb-2" />
                    
                    <label class="block mb-1">Read More URL</label>
                    <input name="hod_read_more_url" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','hod_read_more_url')->values->first()->value_text ?? route('guest.lecturers') }}" class="border p-2 w-full mb-2" />
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium mb-2">Section Titles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Announcements Title</label>
                        <input name="announcements_title" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','announcements_title')->values->first()->value_text ?? 'Pengumuman' }}" class="border p-2 w-full mb-2" />
                    </div>
                    <div>
                        <label class="block mb-1">Articles Title</label>
                        <input name="articles_title" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','articles_title')->values->first()->value_text ?? 'Artikel' }}" class="border p-2 w-full mb-2" />
                    </div>
                    <div>
                        <label class="block mb-1">Faculty Title</label>
                        <input name="faculty_title" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','faculty_title')->values->first()->value_text ?? 'Meet Our Faculty' }}" class="border p-2 w-full mb-2" />
                    </div>
                    <div>
                        <label class="block mb-1">Faculty Subtitle</label>
                        <input name="faculty_subtitle" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','faculty_subtitle')->values->first()->value_text ?? 'Learn from experienced educators and industry experts dedicated to your success.' }}" class="border p-2 w-full mb-2" />
                    </div>
                    <div>
                        <label class="block mb-1">Partners Title</label>
                        <input name="partners_title" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','partners_title')->values->first()->value_text ?? 'Our Industry Partners' }}" class="border p-2 w-full mb-2" />
                    </div>
                    <div>
                        <label class="block mb-1">Partners Subtitle</label>
                        <input name="partners_subtitle" type="text" value="{{ optional($settings['landing'] ?? collect())->firstWhere('key_name','partners_subtitle')->values->first()->value_text ?? 'We collaborate with leading organizations to provide our students with real-world experience and career opportunities.' }}" class="border p-2 w-full mb-2" />
                    </div>
                </div>
            </div>
            
            <button class="bg-blue-600 text-white px-4 py-2">Save Landing Page Settings</button>
        </form>
    </div>
</div>
@endsection


