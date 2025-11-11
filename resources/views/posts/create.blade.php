@extends('layouts.app')

@section('title', 'Create Post')
@section('breadcrumb', 'Create Post')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create New Post</h2>
        <p class="mt-1 text-sm text-gray-600">Create a new post with multilingual content support</p>
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
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="post-create-form">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information (Non-translatable) -->
                <div class="space-y-6">
                    <!-- Category Selection -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category_id"
                                name="category_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 @error('category_id') border-red-500 @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach($categoryOptions as $categoryId => $categoryName)
                                <option value="{{ $categoryId }}" {{ old('category_id') == $categoryId ? 'selected' : '' }}>
                                    {{ $categoryName }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Select a category for this post (optional)</p>
                        @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Other Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Published At</label>
                            <input type="datetime-local"
                                   id="published_at"
                                   name="published_at"
                                   value="{{ old('published_at') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 @error('published_at') border-red-500 @enderror">
                            @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                            <input type="file"
                                   id="cover"
                                   name="cover"
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 @error('cover') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Upload image. System will automatically create thumbnail based on settings.</p>
                            @error('cover')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Content Translations -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Translations</h3>
                    
                    @foreach ($languages as $language)
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: @if($loop->first) block @else none @endif;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} Translation</h4>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="title_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                                        <input type="text"
                                               id="title_{{ $language->code }}"
                                               name="translations[{{ $language->code }}][title]"
                                               value="{{ old('translations.' . $language->code . '.title') }}"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('translations.' . $language->code . '.title') border-red-500 @enderror"
                                               placeholder="Enter post title"
                                               required>
                                        @error('translations.{{ $language->code }}.title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="slug_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">URL Slug *</label>
                                        <input type="text"
                                               id="slug_{{ $language->code }}"
                                               name="translations[{{ $language->code }}][slug]"
                                               value="{{ old('translations.' . $language->code . '.slug') }}"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('translations.' . $language->code . '.slug') border-red-500 @enderror"
                                               placeholder="post-slug"
                                               required>
                                        @error('translations.{{ $language->code }}.slug')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="excerpt_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                                    <textarea id="excerpt_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][excerpt]"
                                              rows="3"
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 @error('translations.' . $language->code . '.excerpt') border-red-500 @enderror"
                                              placeholder="Brief description of post">{{ old('translations.' . $language->code . '.excerpt') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Short description used in previews and meta tags</p>
                                    @error('translations.{{ $language->code }}.excerpt')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="content_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                    <textarea id="content_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][content]"
                                              rows="10"
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 ckeditor @error('translations.' . $language->code . '.content') border-red-500 @enderror"
                                              placeholder="Enter post content">{{ old('translations.' . $language->code . '.content') }}</textarea>
                                    @error('translations.{{ $language->code }}.content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- SEO Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                    
                    @foreach ($languages as $language)
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: none;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} SEO</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="meta_title_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                    <input type="text"
                                           id="meta_title_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][meta_title]"
                                           value="{{ old('translations.' . $language->code . '.meta_title') }}"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 @error('translations.' . $language->code . '.meta_title') border-red-500 @enderror"
                                           placeholder="SEO meta title">
                                    <p class="mt-1 text-xs text-gray-500">Title displayed in search results (60 characters max)</p>
                                    @error('translations.{{ $language->code }}.meta_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="meta_description_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                    <textarea id="meta_description_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][meta_description]"
                                              rows="3"
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 @error('translations.' . $language->code . '.meta_description') border-red-500 @enderror"
                                              placeholder="SEO meta description">{{ old('translations.' . $language->code . '.meta_description') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Description displayed in search results</p>
                                    @error('translations.{{ $language->code }}.meta_description')
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
                                   name="is_pinned"
                                   value="1"
                                   {{ old('is_pinned') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Pin to Top</span>
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
                <a href="{{ route('posts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Create Post with All Translations
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
    console.log('Create post loaded, initializing CKEditor for all languages...');
    
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
                        height: 400,
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
    const form = document.getElementById('post-create-form');
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
