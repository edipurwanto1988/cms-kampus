# Implementation Plan for Simplified Multilingual Interface

## 1. HasTranslations Trait

### File: `app/Traits/HasTranslations.php`

```php
<?php

namespace App\Traits;

use App\Models\Language;

trait HasTranslations
{
    /**
     * Get translation for specific language
     */
    public function getTranslationForLanguage($languageCode)
    {
        return $this->translations()->where('locale', $languageCode)->first();
    }
    
    /**
     * Get all translations organized by language code
     */
    public function getAllTranslations()
    {
        $languages = Language::getAllOrdered();
        $translations = [];
        
        foreach ($languages as $language) {
            $translation = $this->getTranslationForLanguage($language->code);
            $translations[$language->code] = $translation ?: $this->createEmptyTranslation($language->code);
        }
        
        return $translations;
    }
    
    /**
     * Create empty translation for a language
     */
    protected function createEmptyTranslation($languageCode)
    {
        $translationClass = $this->getTranslationClass();
        return new $translationClass(['locale' => $languageCode]);
    }
    
    /**
     * Get the translation class name
     */
    protected function getTranslationClass()
    {
        return static::class . 'Translation';
    }
    
    /**
     * Save multiple translations at once
     */
    public function saveTranslations($translationsData)
    {
        foreach ($translationsData as $locale => $data) {
            $translation = $this->getTranslationForLanguage($locale);
            
            if ($translation) {
                $translation->update($data);
            } else {
                $translationClass = $this->getTranslationClass();
                $translation = new $translationClass(array_merge($data, ['locale' => $locale]));
                $this->translations()->save($translation);
            }
        }
    }
    
    /**
     * Get translated attribute with fallback
     */
    public function getTranslatedAttribute($attribute, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = $this->getTranslationForLanguage($locale);
        if ($translation && !empty($translation->$attribute)) {
            return $translation->$attribute;
        }
        
        // Fallback to default language
        $defaultLanguage = Language::getDefault();
        if ($defaultLanguage && $defaultLanguage->code !== $locale) {
            $defaultTranslation = $this->getTranslationForLanguage($defaultLanguage->code);
            if ($defaultTranslation && !empty($defaultTranslation->$attribute)) {
                return $defaultTranslation->$attribute;
            }
        }
        
        return null;
    }
}
```

## 2. Updated Page Model

### File: `app/Models/Page.php`

```php
<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'key_name',
        'published_at',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the translations for the page.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PageTranslation::class);
    }

    /**
     * Get the user who created the page.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the page.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the title attribute from translation.
     */
    public function getTitleAttribute()
    {
        return $this->getTranslatedAttribute('title');
    }

    /**
     * Get the slug attribute from translation.
     */
    public function getSlugAttribute()
    {
        return $this->getTranslatedAttribute('slug');
    }

    /**
     * Get the content attribute from translation.
     */
    public function getContentAttribute()
    {
        return $this->getTranslatedAttribute('content_html');
    }

    /**
     * Get the excerpt attribute from translation.
     */
    public function getExcerptAttribute()
    {
        return $this->getTranslatedAttribute('excerpt');
    }

    /**
     * Get the meta title attribute from translation.
     */
    public function getMetaTitleAttribute()
    {
        return $this->getTranslatedAttribute('meta_title');
    }

    /**
     * Get the meta description attribute from translation.
     */
    public function getMetaDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('meta_description');
    }

    /**
     * Scope a query to only include active pages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include published pages.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Check if the page is published.
     */
    public function isPublished(): bool
    {
        return $this->is_active && $this->published_at && $this->published_at->isPast();
    }

    /**
     * Get the status label for the page.
     */
    public function getStatusLabel(): string
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if (!$this->published_at) {
            return 'Draft';
        }
        
        if ($this->published_at->isFuture()) {
            return 'Scheduled';
        }
        
        return 'Published';
    }

    /**
     * Get the status color for the page.
     */
    public function getStatusColor(): string
    {
        if (!$this->is_active) {
            return 'gray';
        }
        
        if (!$this->published_at) {
            return 'yellow';
        }
        
        if ($this->published_at->isFuture()) {
            return 'blue';
        }
        
        return 'green';
    }
}
```

## 3. Updated Post Model

### File: `app/Models/Post.php`

```php
<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'category_id',
        'language_code',
        'cover_media_id',
        'published_at',
        'is_pinned',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function coverMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_code', 'code');
    }

    /**
     * Get the title attribute from translation.
     */
    public function getTitleAttribute()
    {
        return $this->getTranslatedAttribute('title');
    }

    /**
     * Get the slug attribute from translation.
     */
    public function getSlugAttribute()
    {
        return $this->getTranslatedAttribute('slug');
    }

    /**
     * Get the excerpt attribute from translation.
     */
    public function getExcerptAttribute()
    {
        return $this->getTranslatedAttribute('excerpt');
    }

    /**
     * Get the content attribute from translation.
     */
    public function getContentAttribute()
    {
        return $this->getTranslatedAttribute('content_html');
    }

    /**
     * Get the meta title attribute from translation.
     */
    public function getMetaTitleAttribute()
    {
        return $this->getTranslatedAttribute('meta_title');
    }

    /**
     * Get the meta description attribute from translation.
     */
    public function getMetaDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('meta_description');
    }
}
```

## 4. Updated Category Model

### File: `app/Models/Category.php`

```php
<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'parent_id',
        'image_media_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function imageMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_media_id');
    }

    /**
     * Get the name attribute from translation.
     */
    public function getNameAttribute()
    {
        return $this->getTranslatedAttribute('name');
    }

    /**
     * Get the slug attribute from translation.
     */
    public function getSlugAttribute()
    {
        return $this->getTranslatedAttribute('slug');
    }

    /**
     * Get the description attribute from translation.
     */
    public function getDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('description');
    }
}
```

## 5. Updated PageController

### File: `app/Http/Controllers/PageController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::with(['creator', 'translations'])
                     ->withCount('translations')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        
        return view('pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $languages = Language::getAllOrdered();
        $activeTab = $request->get('tab', Language::getDefault()->code);
        
        return view('pages.create', compact('languages', 'activeTab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key_name' => 'required|string|max:255|unique:pages',
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.excerpt' => 'nullable|string|max:500',
            'translations.*.content' => 'nullable|string',
            'translations.*.meta_title' => 'nullable|string|max:255',
            'translations.*.meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Create the page
        $page = Page::create([
            'key_name' => $request->key_name,
            'is_active' => $request->boolean('is_active', true),
            'published_at' => $request->published_at,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // Create all translations
        foreach ($request->translations as $locale => $translationData) {
            PageTranslation::create([
                'page_id' => $page->id,
                'locale' => $locale,
                'title' => $translationData['title'],
                'slug' => $translationData['slug'] ?: Str::slug($translationData['title']),
                'excerpt' => $translationData['excerpt'] ?? null,
                'content_html' => $translationData['content'] ?? null,
                'meta_title' => $translationData['meta_title'] ?? null,
                'meta_description' => $translationData['meta_description'] ?? null,
                'translated_at' => now(),
            ]);
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page created with all translations successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        $page->load(['creator', 'updater', 'translations']);
        return view('pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page, Request $request)
    {
        $page->load('translations');
        $languages = Language::getAllOrdered();
        $activeTab = $request->get('tab', Language::getDefault()->code);
        
        return view('pages.edit', compact('page', 'languages', 'activeTab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'key_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages')->ignore($page->id),
            ],
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.excerpt' => 'nullable|string|max:500',
            'translations.*.content' => 'nullable|string',
            'translations.*.meta_title' => 'nullable|string|max:255',
            'translations.*.meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Update the page
        $page->update([
            'key_name' => $request->key_name,
            'is_active' => $request->boolean('is_active', $page->is_active),
            'published_at' => $request->published_at,
            'updated_by' => Auth::id(),
        ]);

        // Update all translations
        foreach ($request->translations as $locale => $translationData) {
            $translation = $page->translations()->where('locale', $locale)->first();
            
            $data = [
                'title' => $translationData['title'],
                'slug' => $translationData['slug'] ?: Str::slug($translationData['title']),
                'excerpt' => $translationData['excerpt'] ?? null,
                'content_html' => $translationData['content'] ?? null,
                'meta_title' => $translationData['meta_title'] ?? null,
                'meta_description' => $translationData['meta_description'] ?? null,
                'translated_at' => now(),
            ];
            
            if ($translation) {
                $translation->update($data);
            } else {
                PageTranslation::create(array_merge($data, [
                    'page_id' => $page->id,
                    'locale' => $locale,
                ]));
            }
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page updated with all translations successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        // Delete all translations first
        $page->translations()->delete();
        
        // Delete the page
        $page->delete();

        return redirect()->route('pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
```

## 6. Language Tabs Component

### File: `resources/views/components/language-tabs.blade.php`

```blade
@props(['languages', 'activeTab', 'translations'])

<div class="border-b border-gray-200 mb-6">
    <nav class="-mb-px flex space-x-8" aria-label="Language Tabs">
        @foreach ($languages as $language)
            <button type="button" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                           @if ($activeTab === $language->code)
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
                
                @if(isset($translations[$language->code]) && !empty($translations[$language->code]->title))
                    <span class="ml-1 text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded">✓</span>
                @endif
            </button>
        @endforeach
    </nav>
</div>

<script>
function switchLanguageTab(languageCode) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.language-tabs button').forEach(button => {
        button.classList.remove('border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const selectedTab = document.querySelector(`.tab-content[data-lang="${languageCode}"]`);
    if (selectedTab) {
        selectedTab.style.display = 'block';
    }
    
    // Highlight selected tab button
    const selectedButton = document.querySelector(`.language-tabs button[data-lang="${languageCode}"]`);
    if (selectedButton) {
        selectedButton.classList.remove('border-transparent', 'text-gray-500');
        selectedButton.classList.add('border-indigo-500', 'text-indigo-600');
    }
}

// Initialize first tab on page load
document.addEventListener('DOMContentLoaded', function() {
    const firstTab = document.querySelector('.language-tabs button');
    if (firstTab) {
        switchLanguageTab(firstTab.dataset.lang);
    }
});
</script>
```

## 7. Updated Page Create View

### File: `resources/views/pages/create.blade.php`

```blade
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
    <x-language-tabs :languages="$languages" :active-tab="$activeTab" />

    <!-- Form -->
    <div class="card">
        <form action="{{ route('pages.store') }}" method="POST" id="page-create-form">
            @csrf
            
            <!-- Basic Information -->
            <div class="space-y-6">
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

                <!-- Content Translations -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Translations</h3>
                    
                    @foreach ($languages as $language)
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: none;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} Translation</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="title_{{ $language->code }}" class="form-label">Page Title</label>
                                    <input type="text"
                                           id="title_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][title]"
                                           value="{{ old('translations.' . $language->code . '.title') }}"
                                           class="form-input @error('translations.' . $language->code . '.title') border-red-500 @enderror"
                                           placeholder="Enter page title"
                                           required>
                                    @error('translations.{{ $language->code }}.title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="slug_{{ $language->code }}" class="form-label">URL Slug</label>
                                    <input type="text"
                                           id="slug_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][slug]"
                                           value="{{ old('translations.' . $language->code . '.slug') }}"
                                           class="form-input @error('translations.' . $language->code . '.slug') border-red-500 @enderror"
                                           placeholder="leave blank to auto-generate from title">
                                    <p class="mt-1 text-xs text-gray-500">Leave blank to automatically generate from title</p>
                                    @error('translations.{{ $language->code }}.slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="excerpt_{{ $language->code }}" class="form-label">Excerpt</label>
                                    <textarea id="excerpt_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][excerpt]"
                                              rows="3"
                                              class="form-input @error('translations.' . $language->code . '.excerpt') border-red-500 @enderror"
                                              placeholder="Brief description of the page content">{{ old('translations.' . $language->code . '.excerpt') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Short description used in previews and meta tags</p>
                                    @error('translations.{{ $language->code }}.excerpt')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="content_{{ $language->code }}" class="form-label">Content</label>
                                    <textarea id="content_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][content]"
                                              rows="10"
                                              class="form-input ckeditor @error('translations.' . $language->code . '.content') border-red-500 @enderror"
                                              placeholder="Enter page content (HTML supported)">{{ old('translations.' . $language->code . '.content') }}</textarea>
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
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="meta_title_{{ $language->code }}" class="form-label">Meta Title</label>
                                    <input type="text"
                                           id="meta_title_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][meta_title]"
                                           value="{{ old('translations.' . $language->code . '.meta_title') }}"
                                           class="form-input @error('translations.' . $language->code . '.meta_title') border-red-500 @enderror"
                                           placeholder="SEO title (leave blank to use page title)">
                                    <p class="mt-1 text-xs text-gray-500">Title displayed in search results (60 characters max)</p>
                                    @error('translations.{{ $language->code }}.meta_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="meta_description_{{ $language->code }}" class="form-label">Meta Description</label>
                                    <textarea id="meta_description_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][meta_description]"
                                              rows="3"
                                              class="form-input @error('translations.' . $language->code . '.meta_description') border-red-500 @enderror"
                                              placeholder="SEO description (160 characters max)">{{ old('translations.' . $language->code . '.meta_description') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Description displayed in search results</p>
                                    @error('translations.{{ $language->code }}.meta_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                    <i class="fas fa-save mr-2"></i>Create Page with All Translations
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Create page loaded, initializing CKEditor for all languages...');
    
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
    const form = document.getElementById('page-create-form');
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

## 8. Updated Page Edit View

### File: `resources/views/pages/edit.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Edit Page')
@section('breadcrumb', 'Edit Page')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Edit Page: {{ $page->title }}</h2>
        <p class="mt-1 text-sm text-gray-600">Update page content and settings</p>
    </div>

    <!-- Language Tabs -->
    <x-language-tabs :languages="$languages" :active-tab="$activeTab" :translations="$page->translations->keyBy('locale')" />

    <!-- Form -->
    <div class="card">
        <form action="{{ route('pages.update', $page) }}" method="POST" id="page-edit-form">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <label for="key_name" class="form-label">Key Name</label>
                    <input type="text"
                           id="key_name"
                           name="key_name"
                           value="{{ old('key_name', $page->key_name) }}"
                           class="form-input @error('key_name') border-red-500 @enderror"
                           placeholder="e.g., about_us"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Unique identifier for the page (used for system references)</p>
                    @error('key_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content Translations -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Translations</h3>
                    
                    @foreach ($languages as $language)
                        @php
                            $translation = $page->translations->where('locale', $language->code)->first();
                        @endphp
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: none;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} Translation</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="title_{{ $language->code }}" class="form-label">Page Title</label>
                                    <input type="text"
                                           id="title_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][title]"
                                           value="{{ old('translations.' . $language->code . '.title', $translation ? $translation->title : '') }}"
                                           class="form-input @error('translations.' . $language->code . '.title') border-red-500 @enderror"
                                           placeholder="Enter page title"
                                           required>
                                    @error('translations.{{ $language->code }}.title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="slug_{{ $language->code }}" class="form-label">URL Slug</label>
                                    <input type="text"
                                           id="slug_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][slug]"
                                           value="{{ old('translations.' . $language->code . '.slug', $translation ? $translation->slug : '') }}"
                                           class="form-input @error('translations.' . $language->code . '.slug') border-red-500 @enderror"
                                           placeholder="leave blank to auto-generate from title">
                                    <p class="mt-1 text-xs text-gray-500">Leave blank to automatically generate from title</p>
                                    @error('translations.{{ $language->code }}.slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="excerpt_{{ $language->code }}" class="form-label">Excerpt</label>
                                    <textarea id="excerpt_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][excerpt]"
                                              rows="3"
                                              class="form-input @error('translations.' . $language->code . '.excerpt') border-red-500 @enderror"
                                              placeholder="Brief description of the page content">{{ old('translations.' . $language->code . '.excerpt', $translation ? $translation->excerpt : '') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Short description used in previews and meta tags</p>
                                    @error('translations.{{ $language->code }}.excerpt')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="content_{{ $language->code }}" class="form-label">Content</label>
                                    <textarea id="content_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][content]"
                                              rows="10"
                                              class="form-input ckeditor @error('translations.' . $language->code . '.content') border-red-500 @enderror"
                                              placeholder="Enter page content (HTML supported)">{{ old('translations.' . $language->code . '.content', $translation ? $translation->content_html : '') }}</textarea>
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
                            $translation = $page->translations->where('locale', $language->code)->first();
                        @endphp
                        <div class="tab-content" data-lang="{{ $language->code }}" style="display: none;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $language->name }} SEO</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="meta_title_{{ $language->code }}" class="form-label">Meta Title</label>
                                    <input type="text"
                                           id="meta_title_{{ $language->code }}"
                                           name="translations[{{ $language->code }}][meta_title]"
                                           value="{{ old('translations.' . $language->code . '.meta_title', $translation ? $translation->meta_title : '') }}"
                                           class="form-input @error('translations.' . $language->code . '.meta_title') border-red-500 @enderror"
                                           placeholder="SEO title (leave blank to use page title)">
                                    <p class="mt-1 text-xs text-gray-500">Title displayed in search results (60 characters max)</p>
                                    @error('translations.{{ $language->code }}.meta_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="meta_description_{{ $language->code }}" class="form-label">Meta Description</label>
                                    <textarea id="meta_description_{{ $language->code }}"
                                              name="translations[{{ $language->code }}][meta_description]"
                                              rows="3"
                                              class="form-input @error('translations.' . $language->code . '.meta_description') border-red-500 @enderror"
                                              placeholder="SEO description (160 characters max)">{{ old('translations.' . $language->code . '.meta_description', $translation ? $translation->meta_description : '') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Description displayed in search results</p>
                                    @error('translations.{{ $language->code }}.meta_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                                       {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Inactive pages won't be accessible to users</p>
                        </div>

                        <div>
                            <label for="published_at" class="form-label">Publish Date</label>
                            <input type="datetime-local"
                                   id="published_at"
                                   name="published_at"
                                   value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}"
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
                    <i class="fas fa-save mr-2"></i>Update Page with All Translations
                </button>
            </div>
        </form>
    </div>

    <!-- Page Information -->
    <div class="mt-6 card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Page Information</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Page ID:</span>
                <span class="text-gray-600">{{ $page->id }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Status:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if ($page->getStatusColor() === 'green') bg-green-100 text-green-800
                    @elseif ($page->getStatusColor() === 'yellow') bg-yellow-100 text-yellow-800
                    @elseif ($page->getStatusColor() === 'blue') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ $page->getStatusLabel() }}
                </span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Created:</span>
                <span class="text-gray-600">{{ $page->created_at->format('d M Y H:i') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Created By:</span>
                <span class="text-gray-600">{{ $page->creator ? $page->creator->name : 'System' }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Last Updated:</span>
                <span class="text-gray-600">{{ $page->updated_at->format('d M Y H:i') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Updated By:</span>
                <span class="text-gray-600">{{ $page->updater ? $page->updater->name : 'System' }}</span>
            </div>
        </div>
    </div>

    <!-- Existing Translations -->
    <div class="mt-6 card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Existing Translations ({{ $page->translations->count() }})</h3>
        @if ($page->translations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($page->translations as $translation)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-indigo-600 font-medium text-xs">{{ strtoupper(substr($translation->locale, 0, 2)) }}</span>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $translation->title }}</p>
                        <p class="text-xs text-gray-500">{{ $translation->slug }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if ($translation->human_reviewed)
                        <span class="text-green-600" title="Human Reviewed">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        @endif
                        @if ($translation->is_machine_translated)
                        <span class="text-blue-600" title="Machine Translated">
                            <i class="fas fa-robot"></i>
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-language text-gray-400 text-3xl mb-3"></i>
                <p class="text-sm text-gray-500">No translations available</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Edit page loaded, initializing CKEditor for all languages...');
    
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
    const form = document.getElementById('page-edit-form');
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

## Implementation Benefits

### 1. **Simplified User Experience**
- All languages visible in tabs simultaneously
- Single form submission for all languages
- Visual indicators for translation status
- Intuitive tab switching

### 2. **Developer Benefits**
- Simplified model relationships with HasTranslations trait
- Unified controller logic for handling multiple languages
- Reusable components for consistent UI
- Cleaner, more maintainable code

### 3. **Scalability**
- Adding new languages automatically creates new tabs
- No code changes needed for additional languages
- Consistent interface across all content types

This implementation provides the simplified tab-based multilingual interface similar to bazaarjakarta-laravel while maintaining the robust features of your CMS Kampus system.