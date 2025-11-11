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

    public function updateOrder(Request $request)
    {
        $data = $request->validate([
            'posts' => ['required', 'array'],
            'posts.*.id' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['posts'] as $index => $postData) {
                $post = Post::find($postData['id']);
                if ($post) {
                    $post->update(['position' => $index]);
                }
            }
        });

        return response()->json(['status' => 'ok']);
    }

    public function updateTranslations(Request $request, Post $post)
    {
        $data = $request->validate([
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string'],
            'translations.*.excerpt' => ['nullable', 'string'],
            'translations.*.content' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($post, $data) {
            foreach ($data['translations'] as $locale => $translationData) {
                PostTranslation::updateOrCreate(
                    ['post_id' => $post->id, 'locale' => $locale],
                    [
                        'title' => $translationData['title'],
                        'excerpt' => $translationData['excerpt'] ?? null,
                        'content_html' => $translationData['content'] ?? null,
                        'meta_title' => $translationData['meta_title'] ?? null,
                        'meta_description' => $translationData['meta_description'] ?? null,
                        'translated_at' => now(),
                    ]
                );
            }
        });

        return response()->json(['status' => 'ok']);
    }

    public function create()
    {
        $categories = Category::with('translations')->where('is_active', true)->get();
        $categoryOptions = $this->prepareCategoryOptions($categories);
        $languages = Language::getAllOrdered();
        return view('posts.create', compact('categories', 'categoryOptions', 'languages'));
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
        $post->load('translations');
        $categories = Category::with('translations')->where('is_active', true)->get();
        $categoryOptions = $this->prepareCategoryOptions($categories);
        $languages = Language::getAllOrdered();
        return view('posts.edit', compact('post', 'categories', 'categoryOptions', 'languages'));
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
                
                $translationUpdateData = [
                    'title' => $translationData['title'],
                    'slug' => $translationData['slug'],
                    'excerpt' => $translationData['excerpt'] ?? null,
                    'content_html' => $translationData['content'] ?? null,
                    'meta_title' => $translationData['meta_title'] ?? null,
                    'meta_description' => $translationData['meta_description'] ?? null,
                    'translated_at' => now(),
                ];
                
                if ($translation) {
                    $translation->update($translationUpdateData);
                } else {
                    PostTranslation::create(array_merge($translationUpdateData, [
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

    /**
     * Prepare categories for dropdown format
     */
    private function prepareCategoryOptions($categories)
    {
        $options = [];
        $locale = app()->getLocale();
        
        foreach ($categories as $category) {
            $translation = $category->translations->where('locale', $locale)->first();
            
            // Fallback to English if current locale translation doesn't exist
            if (!$translation) {
                $translation = $category->translations->where('locale', 'en')->first();
            }
            
            $name = $translation ? $translation->name : 'Untitled Category';
            
            // Add indentation for nested categories
            $indentation = '';
            if ($category->parent_id) {
                $parent = Category::find($category->parent_id);
                if ($parent) {
                    $indentation = '— ';
                }
            }
            
            $options[$category->id] = $indentation . $name;
        }
        
        asort($options);
        return $options;
    }
}


