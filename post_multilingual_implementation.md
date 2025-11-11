# Post Multilingual Tab Implementation

## Updated Post Create View with Language Tabs

### File: `resources/views/posts/create.blade.php`

```blade
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category_id"
                                name="category_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 @error('category_id') border-red-500 @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                @php
                                    $translation = $category->translations->first();
                                    if (!$translation) {
                                        $translation = $category->translations()->where('locale', 'en')->first();
                                    }
                                    $name = $translation ? $translation->name : 'Untitled Category';
                                @endphp
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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
```

## Updated Post Edit View with Language Tabs

### File: `resources/views/posts/edit.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Edit Post')
@section('breadcrumb', 'Edit Post')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Edit Post: {{ $post->title }}</h2>
        <p class="mt-1 text-sm text-gray-600">Update post content and settings</p>
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
                    
                    @php
                        $translation = $post->translations->where('locale', $language->code)->first();
                    @endphp
                    @if($translation && !empty($translation->title))
                        <span class="ml-1 text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded">✓</span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="post-edit-form">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information (Non-translatable) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category_id"
                                name="category_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 @error('category_id') border-red-500 @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                @php
                                    $translation = $category->translations->first();
                                    if (!$translation) {
                                        $translation = $category->translations()->where('locale', 'en')->first();
                                    }
                                    $name = $translation ? $translation->name : 'Untitled Category';
                                @endphp
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Published At</label>
                        <input type="datetime-local"
                               id="published_at"
                               name="published_at"
                               value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
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
                        @if($post->coverMedia)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $post->coverMedia->path) }}" alt="{{ $post->title }}" class="h-20 w-20 object-cover rounded">
                            </div>
                        @endif
                        @error('cover')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Content Translations -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Translations</h3>
                    
                    @foreach ($languages as $language)
                        @php
                            $translation = $post->translations->where('locale', $language->code)->first();
                        @endphp
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: @if($loop->first) block @else none @endif;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} Translation</h4>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="title_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                                        <input type="text"
                                               id="title_{{ $language->code }}"
                                               name="translations[{{ $language->code }}][title]"
                                               value="{{ old('translations.' . $language->code . '.title', $translation ? $translation->title : '') }}"
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
                                               value="{{ old('translations.' . $language->code . '.slug', $translation ? $translation->slug : '') }}"
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
                                              placeholder="Brief description of post">{{ old('translations.' . $language->code . '.excerpt', $translation ? $translation->excerpt : '') }}</textarea>
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
                                              placeholder="Enter post content">{{ old('translations.' . $language->code . '.content', $translation ? $translation->content_html : '') }}</textarea>
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
                        @php
                            $translation = $post->translations->where('locale', $language->code)->first();
                        @endphp
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: none;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} SEO</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="meta_title_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                    <input type="text"
                                           id="meta_title_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][meta_title]"
                                           value="{{ old('translations.' . $language->code . '.meta_title', $translation ? $translation->meta_title : '') }}"
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
                                              placeholder="SEO meta description">{{ old('translations.' . $language->code . '.meta_description', $translation ? $translation->meta_description : '') }}</textarea>
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
                                   {{ old('is_pinned', $post->is_pinned) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Pin to Top</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $post->is_active) ? 'checked' : '' }}
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
                    <i class="fas fa-save mr-2"></i>Update Post with All Translations
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
    console.log('Edit post loaded, initializing CKEditor for all languages...');
    
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
    const form = document.getElementById('post-edit-form');
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
```

## Updated PostController for Tab Interface

### File: `app/Http/Controllers/PostController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['translation', 'category'])->latest()->paginate(20);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::with('translations')->get();
        $languages = Language::getAllOrdered();
        return view('posts.create', compact('categories', 'languages'));
    }

    public function store(Request $request, ImageService $imageService)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string'],
            'translations.*.slug' => ['required', 'string'],
            'translations.*.excerpt' => ['nullable', 'string'],
            'translations.*.content' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'cover' => ['nullable', 'image'],
        ]);

        DB::transaction(function () use ($request, $imageService, $data) {
            $post = Post::create([
                'category_id' => $data['category_id'] ?? null,
                'published_at' => $data['published_at'] ?? null,
                'is_pinned' => $data['is_pinned'] ?? false,
                'is_active' => $data['is_active'] ?? true,
                'created_by' => optional($request->user())->id,
            ]);

            if ($request->hasFile('cover')) {
                $stored = $imageService->storeWithThumbnail($request->file('cover'));
                $post->update(['cover_media_id' => $stored['media']->id]);
            }

            // Create all translations
            foreach ($data['translations'] as $locale => $translationData) {
                PostTranslation::create([
                    'post_id' => $post->id,
                    'locale' => $locale,
                    'title' => $translationData['title'],
                    'slug' => $translationData['slug'],
                    'excerpt' => $translationData['excerpt'] ?? null,
                    'content_html' => $translationData['content'] ?? null,
                    'meta_title' => $translationData['meta_title'] ?? null,
                    'meta_description' => $translationData['meta_description'] ?? null,
                    'translated_at' => now(),
                ]);
            }
        });

        return redirect()->route('posts.index')->with('success', 'Post created with all translations');
    }

    public function edit(Post $post)
    {
        $post->load(['translations', 'category']);
        $categories = Category::with('translations')->get();
        $languages = Language::getAllOrdered();
        return view('posts.edit', compact('post', 'categories', 'languages'));
    }

    public function update(Request $request, Post $post, ImageService $imageService)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string'],
            'translations.*.slug' => ['required', 'string'],
            'translations.*.excerpt' => ['nullable', 'string'],
            'translations.*.content' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'cover' => ['nullable', 'image'],
        ]);

        DB::transaction(function () use ($request, $imageService, $post, $data) {
            $post->update([
                'category_id' => $data['category_id'] ?? null,
                'published_at' => $data['published_at'] ?? null,
                'is_pinned' => $data['is_pinned'] ?? false,
                'is_active' => $data['is_active'] ?? true,
                'updated_by' => optional($request->user())->id,
            ]);

            if ($request->hasFile('cover')) {
                $stored = $imageService->storeWithThumbnail($request->file('cover'));
                $post->update(['cover_media_id' => $stored['media']->id]);
            }

            // Update all translations
            foreach ($data['translations'] as $locale => $translationData) {
                $translation = $post->translations()->where('locale', $locale)->first();
                
                $data = [
                    'title' => $translationData['title'],
                    'slug' => $translationData['slug'],
                    'excerpt' => $translationData['excerpt'] ?? null,
                    'content_html' => $translationData['content'] ?? null,
                    'meta_title' => $translationData['meta_title'] ?? null,
                    'meta_description' => $translationData['meta_description'] ?? null,
                    'translated_at' => now(),
                ];
                
                if ($translation) {
                    $translation->update($data);
                } else {
                    PostTranslation::create(array_merge($data, [
                        'post_id' => $post->id,
                        'locale' => $locale,
                    ]));
                }
            }
        });

        return redirect()->route('posts.index')->with('success', 'Post updated with all translations');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted');
    }
}
```

## Key Features of This Implementation

### 1. **Dynamic Tab Generation**
- Tabs are created automatically based on available languages
- If you have 4 languages, you'll see 4 tabs
- Each tab represents one language with all content fields

### 2. **Visual Translation Status**
- Green checkmark (✓) indicates language has content
- Default language is clearly marked
- Active tab is highlighted

### 3. **Single Form Submission**
- All language content submitted together in `translations` array
- Controller processes each language separately
- No need to save each language individually

### 4. **Complete Content Fields**
- Title, slug, excerpt, content for each language
- SEO fields (meta title, meta description) for each language
- Non-translatable fields (category, publish date, etc.) outside tabs

### 5. **CKEditor Integration**
- Each language content area has CKEditor
- All editors updated before form submission
- Rich text editing for all languages

This implementation provides exactly what you requested: a tab-based interface where each tab represents a language, and if you have 4 languages in the database, you'll see 4 tabs for each post.