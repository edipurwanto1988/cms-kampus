@extends('layouts.app')

@section('title', 'Create Lecturer')
@section('breadcrumb', 'Create Lecturer')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create New Lecturer</h2>
        <p class="mt-1 text-sm text-gray-600">Create a new lecturer profile with multilingual content support</p>
    </div>

    <!-- Language Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Language Tabs">
            @foreach ($languages as $language)
                <button type="button" 
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                               @if ($loop->first)
                                   border-indigo-500 text-indigo-600
                               @else
                                   border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                               @endif"
                        data-lang="{{ $language->code }}"
                        onclick="switchLanguageTab('{{ $language->code }}')">
                    {{ $language->name }}
                    @if ($language->is_default)
                        <span class="ml-1 text-xs bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded">Default</span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('lecturers.store') }}" method="POST" enctype="multipart/form-data" id="lecturer-create-form">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information (Non-translatable) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="NUPTK" class="block text-sm font-medium text-gray-700 mb-1">NUPTK</label>
                        <input type="text"
                               id="NUPTK"
                               name="NUPTK"
                               value="{{ old('NUPTK') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('NUPTK') border-red-500 @enderror"
                               placeholder="Enter NUPTK">
                        @error('NUPTK')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                        <input type="text"
                               id="nip"
                               name="nip"
                               value="{{ old('nip') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('nip') border-red-500 @enderror"
                               placeholder="Enter NIP">
                        @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('email') border-red-500 @enderror"
                               placeholder="lecturer@university.edu">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('phone') border-red-500 @enderror"
                               placeholder="+62 812-3456-7890">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="position_title" class="block text-sm font-medium text-gray-700 mb-1">Position Title</label>
                        <input type="text"
                               id="position_title"
                               name="position_title"
                               value="{{ old('position_title') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('position_title') border-red-500 @enderror"
                               placeholder="Professor, Lecturer, etc.">
                        @error('position_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expertise" class="block text-sm font-medium text-gray-700 mb-1">Expertise</label>
                        <input type="text"
                               id="expertise"
                               name="expertise"
                               value="{{ old('expertise') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('expertise') border-red-500 @enderror"
                               placeholder="Information Systems, Database, etc.">
                        @error('expertise')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="scholar_url" class="block text-sm font-medium text-gray-700 mb-1">Google Scholar URL</label>
                        <input type="url"
                               id="scholar_url"
                               name="scholar_url"
                               value="{{ old('scholar_url') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('scholar_url') border-red-500 @enderror"
                               placeholder="https://scholar.google.com/...">
                        @error('scholar_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="researchgate_url" class="block text-sm font-medium text-gray-700 mb-1">ResearchGate URL</label>
                        <input type="url"
                               id="researchgate_url"
                               name="researchgate_url"
                               value="{{ old('researchgate_url') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('researchgate_url') border-red-500 @enderror"
                               placeholder="https://www.researchgate.net/...">
                        @error('researchgate_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                        <input type="url"
                               id="linkedin_url"
                               name="linkedin_url"
                               value="{{ old('linkedin_url') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('linkedin_url') border-red-500 @enderror"
                               placeholder="https://www.linkedin.com/in/...">
                        @error('linkedin_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                        <input type="file"
                               id="photo"
                               name="photo"
                               accept="image/*"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('photo') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Upload profile photo. Recommended size: 300x300px.</p>
                        @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Content Translations -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Translations</h3>
                    
                    @foreach ($languages as $language)
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: @if($loop->first) block @else none @endif;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} Translation</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="full_name_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                    <input type="text"
                                           id="full_name_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][full_name]"
                                           value="{{ old('translations.' . $language->code . '.full_name') }}"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 @error('translations.' . $language->code . '.full_name') border-red-500 @enderror"
                                           placeholder="Enter full name"
                                           required>
                                    @error('translations.{{ $language->code }}.full_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="bio_html_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
                                    <textarea id="bio_html_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][bio_html]"
                                              rows="8"
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 ckeditor @error('translations.' . $language->code . '.bio_html') border-red-500 @enderror"
                                              placeholder="Enter biography">{{ old('translations.' . $language->code . '.bio_html') }}</textarea>
                                    @error('translations.{{ $language->code }}.bio_html')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="research_interests_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Research Interests</label>
                                    <textarea id="research_interests_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][research_interests]"
                                              rows="6"
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 ckeditor @error('translations.' . $language->code . '.research_interests') border-red-500 @enderror"
                                              placeholder="Enter research interests">{{ old('translations.' . $language->code . '.research_interests') }}</textarea>
                                    @error('translations.{{ $language->code }}.research_interests')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="achievement_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Achievements</label>
                                    <textarea id="achievement_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][achievement]"
                                              rows="6"
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 ckeditor @error('translations.' . $language->code . '.achievement') border-red-500 @enderror"
                                              placeholder="Enter achievements">{{ old('translations.' . $language->code . '.achievement') }}</textarea>
                                    @error('translations.{{ $language->code }}.achievement')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Status Options -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="featured"
                                   value="1"
                                   {{ old('featured') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Featured Lecturer</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('lecturers.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Create Lecturer with All Translations
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function switchLanguageTab(languageCode) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.border-b button').forEach(button => {
        button.classList.remove('border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const selectedTab = document.querySelector(`.tab-content[data-lang="${languageCode}"]`);
    if (selectedTab) {
        selectedTab.style.display = 'block';
    }
    
    // Highlight selected tab button
    const selectedButton = document.querySelector(`button[data-lang="${languageCode}"]`);
    if (selectedButton) {
        selectedButton.classList.remove('border-transparent', 'text-gray-500');
        selectedButton.classList.add('border-indigo-500', 'text-indigo-600');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Create lecturer loaded, initializing CKEditor for all languages...');
    
    // Initialize CKEditor for all content textareas
    const contentTextareas = document.querySelectorAll('textarea.ckeditor');
    
    contentTextareas.forEach(function(textarea) {
        const textareaId = textarea.id;
        
        // Function to initialize CKEditor for a specific textarea
        function initCKEditorForTextarea() {
            if (typeof CKEDITOR !== 'undefined') {
                try {
                    // Destroy any existing instance
                    if (CKEDITOR.instances[textareaId]) {
                        CKEDITOR.instances[textareaId].destroy();
                    }
                    
                    // Replace textarea with CKEditor
                    CKEDITOR.replace(textareaId, {
                        height: 300,
                        width: '100%',
                        toolbar: 'Full',
                        filebrowserBrowseUrl: '/laravel-filemanager?type=file',
                        filebrowserImageBrowseUrl: '/laravel-filemanager?type=image',
                        filebrowserFlashBrowseUrl: '/laravel-filemanager?type=flash'
                    });
                    
                    console.log('CKEditor initialized for:', textareaId);
                } catch (error) {
                    console.error('Error initializing CKEditor for', textareaId, ':', error);
                }
            } else {
                console.log('CKEditor not yet available, retrying for', textareaId);
                setTimeout(initCKEditorForTextarea, 500);
            }
        }
        
        initCKEditorForTextarea();
    });
    
    // Update all CKEditor instances before form submission
    const form = document.getElementById('lecturer-create-form');
    if (form) {
        form.addEventListener('submit', function() {
            console.log('Form submitted, updating all CKEditor instances...');
            for (var instanceName in CKEDITOR.instances) {
                CKEDITOR.instances[instanceName].updateElement();
            }
        });
    }
});
</script>
@endpush
@endsection