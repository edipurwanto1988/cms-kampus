@extends('layouts.app')

@section('title', 'Create Page')
@section('breadcrumb', 'Create Page')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create New Page</h2>
        <p class="mt-1 text-sm text-gray-600">Create a new page with multilingual content support</p>
    </div>

    <!-- Language Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            @foreach ($languages as $index => $language)
                <a href="?tab={{ $language->code }}"
                   class="py-2 px-1 border-b-2 font-medium text-sm
                   @if ($activeTab === $language->code)
                       border-indigo-500 text-indigo-600
                   @else
                       border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                   @endif">
                    {{ $language->name }}
                    @if ($language->is_default)
                        <span class="ml-1 text-xs bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded">Default</span>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Form -->
    <div class="card">
        <form action="{{ route('pages.store') }}?tab={{ $activeTab }}" method="POST" id="page-create-form">
            @csrf
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="key_name" class="form-label">Key Name</label>
                        <input type="text"
                               id="key_name"
                               name="key_name"
                               value="{{ old('key_name') }}"
                               class="form-input @error('key_name') border-red-500 @enderror"
                               placeholder="e.g., about_us"
                               required>
                        <p class="mt-1 text-xs text-gray-500">Unique identifier for the page (used for system references)</p>
                        @error('key_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Language</label>
                        <div class="form-input bg-gray-50 flex items-center">
                            <span>{{ $languages->where('code', $activeTab)->first()->name }}</span>
                            <input type="hidden" name="locale" value="{{ $activeTab }}">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Creating translation for: {{ $languages->where('code', $activeTab)->first()->name }}</p>
                    </div>
                </div>

                <!-- Content Translation -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Translation ({{ $languages->where('code', $activeTab)->first()->name }})</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="form-label">Page Title</label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   class="form-input @error('title') border-red-500 @enderror"
                                   placeholder="Enter page title"
                                   required>
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slug" class="form-label">URL Slug</label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug') }}"
                                   class="form-input @error('slug') border-red-500 @enderror"
                                   placeholder="leave blank to auto-generate from title">
                            <p class="mt-1 text-xs text-gray-500">Leave blank to automatically generate from title</p>
                            @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea id="excerpt"
                                      name="excerpt"
                                      rows="3"
                                      class="form-input @error('excerpt') border-red-500 @enderror"
                                      placeholder="Brief description of the page content">{{ old('excerpt') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Short description used in previews and meta tags</p>
                            @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="form-label">Content</label>
                            <textarea id="content"
                                      name="content"
                                      rows="10"
                                      class="form-input ckeditor @error('content') border-red-500 @enderror"
                                      placeholder="Enter page content (HTML supported)">{{ old('content') }}</textarea>
                            @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings ({{ $languages->where('code', $activeTab)->first()->name }})</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text"
                                   id="meta_title"
                                   name="meta_title"
                                   value="{{ old('meta_title') }}"
                                   class="form-input @error('meta_title') border-red-500 @enderror"
                                   placeholder="SEO title (leave blank to use page title)">
                            <p class="mt-1 text-xs text-gray-500">Title displayed in search results (60 characters max)</p>
                            @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea id="meta_description"
                                      name="meta_description"
                                      rows="3"
                                      class="form-input @error('meta_description') border-red-500 @enderror"
                                      placeholder="SEO description (160 characters max)">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Description displayed in search results</p>
                            @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Publishing Options -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing Options</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       class="form-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                       {{ old('is_active', '1') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Inactive pages won't be accessible to users</p>
                        </div>

                        <div>
                            <label for="published_at" class="form-label">Publish Date</label>
                            <input type="datetime-local"
                                   id="published_at"
                                   name="published_at"
                                   value="{{ old('published_at') }}"
                                   class="form-input @error('published_at') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Leave blank to publish immediately, or schedule for later</p>
                            @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('pages.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Create Page
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Create page loaded, initializing CKEditor...');
    
    // Function to initialize CKEditor
    function initCKEditor() {
        if (typeof CKEDITOR !== 'undefined') {
            console.log('CKEditor is available, initializing...');
            
            // Find the content textarea
            const contentTextarea = document.getElementById('content');
            if (contentTextarea) {
                console.log('Found content textarea, replacing with CKEditor...');
                
                try {
                    // Destroy any existing instance
                    if (CKEDITOR.instances.content) {
                        CKEDITOR.instances.content.destroy();
                    }
                    
                    // Replace textarea with CKEditor
                    const editor = CKEDITOR.replace('content', {
                        height: 400,
                        width: '100%',
                        toolbar: 'Full',
                        filebrowserBrowseUrl: '/laravel-filemanager?type=file',
                        filebrowserImageBrowseUrl: '/laravel-filemanager?type=image',
                        filebrowserFlashBrowseUrl: '/laravel-filemanager?type=flash'
                    });
                    
                    // Update textarea before form submission
                    const form = document.getElementById('page-create-form');
                    if (form) {
                        form.addEventListener('submit', function() {
                            console.log('Form submitted, updating CKEditor content...');
                            CKEDITOR.instances.content.updateElement();
                        });
                    }
                    
                    console.log('CKEditor initialized successfully');
                } catch (error) {
                    console.error('Error initializing CKEditor:', error);
                }
            } else {
                console.error('Content textarea not found');
            }
        } else {
            console.log('CKEditor not yet available, retrying...');
            setTimeout(initCKEditor, 500);
        }
    }
    
    // Start initialization
    initCKEditor();
});
</script>
@endpush
@endsection