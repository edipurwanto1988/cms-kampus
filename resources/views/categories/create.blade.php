@extends('layouts.app')

@section('title', 'Create Category')
@section('breadcrumb', 'Create Category')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create New Category</h2>
        <p class="mt-1 text-sm text-gray-600">Create a new category with multilingual content support</p>
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

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Content Translations -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Category Translations</h3>
                    
                    @foreach ($languages as $language)
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: @if($loop->first) block @else none @endif;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} Translation</h4>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                        <input type="text"
                                               id="name_{{ $language->code }}"
                                               name="translations[{{ $language->code }}][name]"
                                               value="{{ old('translations.' . $language->code . '.name') }}"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 @error('translations.' . $language->code . '.name') border-red-500 @enderror"
                                               placeholder="Category name"
                                               required>
                                        @error('translations.{{ $language->code }}.name')
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
                                               placeholder="category-slug"
                                               required>
                                        @error('translations.{{ $language->code }}.slug')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="description_{{ $language->code }}" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea id="description_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][description]"
                                              rows="4"
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 @error('translations.' . $language->code . '.description') border-red-500 @enderror"
                                              placeholder="Category description">{{ old('translations.' . $language->code . '.description') }}</textarea>
                                    @error('translations.{{ $language->code }}.description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Category Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                    <input type="file"
                           id="image"
                           name="image"
                           accept="image/*"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 @error('image') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Upload image. System will automatically create thumbnail based on settings.</p>
                    @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Options -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center space-x-6">
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

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Create Category with All Translations
                    </button>
                </div>
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
</script>
@endpush
@endsection

