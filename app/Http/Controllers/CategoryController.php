<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Language;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['translation', 'children'])->paginate(50);
        // dd($categories->first()->translation->first()->name);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::with('translations')->get();
        $languages = Language::getAllOrdered();
        return view('categories.create', compact('categories', 'languages'));
    }

    public function store(Request $request, ImageService $imageService)
    {
        $data = $request->validate([
            'is_active' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string'],
            'translations.*.slug' => ['required', 'string'],
            'translations.*.description' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
        ]);

        DB::transaction(function () use ($request, $imageService, $data) {
            $category = Category::create([
                'is_active' => $data['is_active'] ?? true,
            ]);

            if ($request->hasFile('image')) {
                $stored = $imageService->storeWithThumbnail($request->file('image'));
                $category->update(['image_media_id' => $stored['media']->id]);
            }

            // Create all translations
            foreach ($data['translations'] as $locale => $translationData) {
                CategoryTranslation::updateOrCreate(
                    ['category_id' => $category->id, 'locale' => $locale],
                    [
                        'name' => $translationData['name'],
                        'slug' => $translationData['slug'],
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('categories.index')->with('success', 'Category created with all translations');
    }

    public function edit(Category $category)
    {
        $category->load('translations');
        $categories = Category::where('id', '!=', $category->id)->with('translations')->get();
        $languages = Language::getAllOrdered();
        return view('categories.edit', compact('category', 'categories', 'languages'));
    }

    public function update(Request $request, Category $category, ImageService $imageService)
    {
        $data = $request->validate([
            'is_active' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string'],
            'translations.*.slug' => ['required', 'string'],
            'translations.*.description' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
        ]);

        DB::transaction(function () use ($request, $imageService, $data, $category) {
            $category->update([
                'is_active' => $data['is_active'] ?? true,
            ]);

            if ($request->hasFile('image')) {
                $stored = $imageService->storeWithThumbnail($request->file('image'));
                $category->update(['image_media_id' => $stored['media']->id]);
            }

            // Update all translations
            foreach ($data['translations'] as $locale => $translationData) {
                CategoryTranslation::updateOrCreate(
                    ['category_id' => $category->id, 'locale' => $locale],
                    [
                        'name' => $translationData['name'],
                        'slug' => $translationData['slug'],
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('categories.index')->with('success', 'Category updated with all translations');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted');
    }
}


