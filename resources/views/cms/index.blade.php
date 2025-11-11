@extends('layouts.app')


@section('breadcrumb', 'CMS Content Management')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">CMS Content Management</h1>
        <div class="flex items-center space-x-2">
            <a href="{{ route('guest.home') }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i>Preview Site
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Language Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                @foreach($languages as $index => $language)
                    <button type="button"
                            class="language-tab py-2 px-4 border-b-2 font-medium text-sm {{ $index === 0 ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            data-language="{{ $language->code }}"
                            onclick="switchLanguageTab('{{ $language->code }}')">
                        <i class="fas fa-globe mr-2"></i>{{ $language->name }}
                        @if($language->is_default)
                            <span class="ml-1 text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded">Default</span>
                        @endif
                    </button>
                @endforeach
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ route('cms.update') }}" id="cms-form">
        @csrf
    <!-- General Settings -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">General Settings</h2>
        
        @foreach($languages as $language)
            <div class="language-content" data-language="{{ $language->code }}" style="{{ $loop->first ? '' : 'display:none;' }}">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-globe mr-2"></i>{{ $language->name }} Content
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                            <input name="site_name_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','site_name'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- Non-multilingual settings (only show once) -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-3">
                <i class="fas fa-cog mr-2"></i>Global Settings (Same for all languages)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Size</label>
                    <input name="thumbnail_size" type="text" value="{{ optional(optional($settings['general'] ?? collect())->firstWhere('key_name','thumbnail_size'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Settings -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">SEO Settings</h2>
        
        @foreach($languages as $language)
            <div class="language-content" data-language="{{ $language->code }}" style="{{ $loop->first ? '' : 'display:none;' }}">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-globe mr-2"></i>{{ $language->name }} SEO
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Meta Title</label>
                            <input name="site_meta_title_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','site_meta_title'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Meta Description</label>
                            <textarea name="site_meta_description_{{ $language->code }}" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','site_meta_description'))->values->first()->value_text ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Social Media Settings -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Social Media Settings</h2>
        <p class="text-sm text-gray-600 mb-4">These settings are global and apply to all languages.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                <input name="facebook_url" type="url" value="{{ optional(optional($settings['social'] ?? collect())->firstWhere('key_name','facebook_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Twitter URL</label>
                <input name="twitter_url" type="url" value="{{ optional(optional($settings['social'] ?? collect())->firstWhere('key_name','twitter_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                <input name="instagram_url" type="url" value="{{ optional(optional($settings['social'] ?? collect())->firstWhere('key_name','instagram_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">YouTube URL</label>
                <input name="youtube_url" type="url" value="{{ optional(optional($settings['social'] ?? collect())->firstWhere('key_name','youtube_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
        </div>
    </div>

    <!-- Landing Page Settings -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Landing Page Settings</h2>
        
        @foreach($languages as $language)
            <div class="language-content" data-language="{{ $language->code }}" style="{{ $loop->first ? '' : 'display:none;' }}">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-globe mr-2"></i>{{ $language->name }} Content
                    </h3>
                    
                    <!-- Hero Section -->
                    <h4 class="text-md font-medium mb-3 text-gray-700">Hero Section</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                            <input name="hero_title_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','hero_title'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                            <textarea name="hero_subtitle_{{ $language->code }}" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','hero_subtitle'))->values->first()->value_text ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Button 1 Text</label>
                            <input name="hero_button1_text_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','hero_button1_text'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Button 2 Text</label>
                            <input name="hero_button2_text_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','hero_button2_text'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                    </div>

                    <!-- Head of Department Section -->
                    <h4 class="text-md font-medium mb-3 text-gray-700">Head of Department Section</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">HOD Name</label>
                            <input name="hod_name_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','hod_name'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">HOD Message</label>
                            <textarea name="hod_message_{{ $language->code }}" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','hod_message'))->values->first()->value_text ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Section Titles -->
                    <h4 class="text-md font-medium mb-3 text-gray-700">Section Titles</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Announcements Title</label>
                            <input name="announcements_title_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','announcements_title'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Articles Title</label>
                            <input name="articles_title_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','articles_title'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faculty Title</label>
                            <input name="faculty_title_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','faculty_title'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faculty Subtitle</label>
                            <textarea name="faculty_subtitle_{{ $language->code }}" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','faculty_subtitle'))->values->first()->value_text ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partners Title</label>
                            <input name="partners_title_{{ $language->code }}" type="text" value="{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','partners_title'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partners Subtitle</label>
                            <textarea name="partners_subtitle_{{ $language->code }}" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ optional(optional($settingsByLanguage[$language->code] ?? collect())->firstWhere('key_name','partners_subtitle'))->values->first()->value_text ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- Global settings (non-multilingual) -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-3">
                <i class="fas fa-globe mr-2"></i>Global Settings (Same for all languages)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Image URL</label>
                    <input name="hero_image_url" type="url" value="{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_image_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Button 1 URL</label>
                    <input name="hero_button1_url" type="text" value="{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_button1_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Button 2 URL</label>
                    <input name="hero_button2_url" type="text" value="{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_button2_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">HOD Photo URL</label>
                    <input name="hod_photo_url" type="url" value="{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hod_photo_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">HOD Read More URL</label>
                    <input name="hod_read_more_url" type="text" value="{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hod_read_more_url'))->values->first()->value_text ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="flex justify-end mb-6">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
            <i class="fas fa-save mr-2"></i>Save All Changes
        </button>
    </div>
    </form>

    <!-- Live Preview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Live Preview</h2>
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
            <p class="text-center text-gray-500 mb-4">This is a preview of how your landing page will look with the current settings.</p>
            
            <!-- Preview Hero Section -->
            <div class="bg-gray-100 rounded-lg p-6 mb-4 text-center">
                <h3 class="text-2xl font-bold mb-2">{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_title'))->values->first()->value_text ?? 'Hero Title' }}</h3>
                <p class="text-gray-600 mb-4">{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_subtitle'))->values->first()->value_text ?? 'Hero Subtitle' }}</p>
                <div class="flex justify-center space-x-4">
                    <span class="bg-blue-600 text-white px-4 py-2 rounded">{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_button1_text'))->values->first()->value_text ?? 'Button 1' }}</span>
                    <span class="bg-gray-600 text-white px-4 py-2 rounded">{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','hero_button2_text'))->values->first()->value_text ?? 'Button 2' }}</span>
                </div>
            </div>
            
            <!-- Preview Section Titles -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded p-4 text-center">
                    <h4 class="font-bold">{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','announcements_title'))->values->first()->value_text ?? 'Announcements' }}</h4>
                </div>
                <div class="bg-green-50 rounded p-4 text-center">
                    <h4 class="font-bold">{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','articles_title'))->values->first()->value_text ?? 'Articles' }}</h4>
                </div>
                <div class="bg-purple-50 rounded p-4 text-center">
                    <h4 class="font-bold">{{ optional(optional($settings['landing'] ?? collect())->firstWhere('key_name','faculty_title'))->values->first()->value_text ?? 'Faculty' }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchLanguageTab(languageCode) {
    // Update tab styles
    document.querySelectorAll('.language-tab').forEach(tab => {
        tab.classList.remove('border-indigo-500', 'text-indigo-600');
        tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
    
    const activeTab = document.querySelector(`[data-language="${languageCode}"]`);
    activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    activeTab.classList.add('border-indigo-500', 'text-indigo-600');
    
    // Show/hide content
    document.querySelectorAll('.language-content').forEach(content => {
        content.style.display = 'none';
    });
    
    document.querySelectorAll(`[data-language="${languageCode}"]`).forEach(content => {
        if (content.classList.contains('language-content')) {
            content.style.display = 'block';
        }
    });
}
</script>
@endsection