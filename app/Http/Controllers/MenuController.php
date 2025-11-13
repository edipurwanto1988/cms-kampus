<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuTranslation;
use App\Models\Page;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('translations')->ordered()->get();
        $languages = Language::getAllOrdered();
        return view('menus.index', compact('menus', 'languages'));
    }

    public function edit(Menu $menu)
    {
        // Get available items for left panel
        $pages = Page::with('translations')->get()->map(function ($page) {
            return [
                'id' => 'page_' . $page->id,
                'type' => 'page',
                'title' => $page->title,
                'url' => '/pages/' . $page->slug,
            ];
        });

        $posts = Post::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->get()->map(function ($post) {
            $translation = $post->translations->first();
            if (!$translation) {
                // Fallback to English
                $translation = $post->translations()->where('locale', 'en')->first();
            }
            $title = $translation ? $translation->title : 'Untitled Post';
            $slug = $translation ? $translation->slug : 'untitled';
            return [
                'id' => 'post_' . $post->id,
                'type' => 'post',
                'title' => $title,
                'url' => '/posts/' . $slug,
            ];
        });

        $categories = Category::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->get()->map(function ($category) {
            $translation = $category->translations->first();
            if (!$translation) {
                // Fallback to English
                $translation = $category->translations()->where('locale', 'en')->first();
            }
            $title = $translation ? $translation->name : 'Untitled Category';
            $slug = $translation ? $translation->slug : 'untitled';
            return [
                'id' => 'category_' . $category->id,
                'type' => 'category',
                'title' => $title,
                'url' => '/categories/' . $slug,
            ];
        });

        $availableItems = [
            'pages' => $pages,
            'posts' => $posts,
            'categories' => $categories,
        ];

        return view('menus.edit', compact('menu', 'availableItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'location' => ['nullable', 'string'],
            'url' => ['nullable', 'string'],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $maxPosition = Menu::max('position') ?? -1;
        
        $menu = Menu::create([
            'name' => $data['name'],
            'location' => $data['location'] ?? null,
            'url' => $data['url'] ?? null,
            'target' => $data['target'] ?? '_self',
            'is_active' => $data['is_active'] ?? true,
            'position' => $maxPosition + 1,
        ]);

        // Create translations if provided
        if ($request->has('translations')) {
            foreach ($request->input('translations') as $locale => $translationData) {
                if (!empty($translationData['name'])) {
                    MenuTranslation::create([
                        'menu_id' => $menu->id,
                        'locale' => $locale,
                        'name' => $translationData['name'],
                    ]);
                }
            }
        }

        return redirect()->route('menus.index')->with('success', 'Menu created');
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'location' => ['nullable', 'string'],
            'url' => ['nullable', 'string'],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $menu->update([
            'name' => $data['name'],
            'location' => $data['location'] ?? $menu->location,
            'url' => $data['url'] ?? $menu->url,
            'target' => $data['target'] ?? $menu->target,
            'is_active' => $data['is_active'] ?? $menu->is_active,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully');
    }

    // Remove menu item related methods as we're using direct menu links

    public function updateOrder(Request $request)
    {
        $data = $request->validate([
            'menus' => ['required', 'array'],
            'menus.*.id' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['menus'] as $index => $menuData) {
                $menu = Menu::find($menuData['id']);
                if ($menu) {
                    $menu->update(['position' => $index]);
                }
            }
        });

        return response()->json(['status' => 'ok']);
    }

    public function updateTranslations(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($menu, $data) {
            foreach ($data['translations'] as $locale => $translationData) {
                MenuTranslation::updateOrCreate(
                    ['menu_id' => $menu->id, 'locale' => $locale],
                    ['name' => $translationData['name']]
                );
            }
        });

        return response()->json(['status' => 'ok']);
    }

    public function destroy(Menu $menu)
    {
        // Delete menu items first (if any exist)
        $menu->items()->delete();
        
        // Delete translations
        $menu->translations()->delete();
        
        // Delete menu
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully');
    }
}


