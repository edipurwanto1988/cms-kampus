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
        // Load menu items with proper hierarchy
        $menu->load(['items' => function ($q) {
            $q->whereNull('parent_id')->orderBy('position');
            $q->with(['children' => function ($childQ) {
                $childQ->orderBy('position');
            }]);
        }]);

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
        ]);

        $maxPosition = Menu::max('position') ?? -1;
        
        $menu = Menu::create([
            'name' => $data['name'],
            'location' => $data['location'] ?? null,
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

    public function addItem(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'in:page,post,category,custom'],
            'title' => ['required', 'string'],
            'url' => ['required', 'string'],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
        ]);

        $maxPosition = MenuItem::where('menu_id', $menu->id)
            ->whereNull('parent_id')
            ->max('position') ?? -1;

        $menuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'parent_id' => null,
            'title' => $data['title'],
            'url' => $data['url'],
            'target' => $data['target'] ?? '_self',
            'position' => $maxPosition + 1,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => 'ok',
            'item' => [
                'id' => $menuItem->id,
                'title' => $menuItem->title,
                'url' => $menuItem->url,
                'target' => $menuItem->target,
                'is_active' => $menuItem->is_active,
            ]
        ]);
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $menuItem)
    {
        // Ensure the menu item belongs to this menu
        if ($menuItem->menu_id !== $menu->id) {
            return response()->json(['status' => 'error', 'message' => 'Invalid menu item'], 403);
        }

        $data = $request->validate([
            'title' => ['required', 'string'],
            'url' => ['required', 'string'],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $menuItem->update($data);

        return response()->json(['status' => 'ok']);
    }

    public function deleteItem(Menu $menu, MenuItem $menuItem)
    {
        // Ensure the menu item belongs to this menu
        if ($menuItem->menu_id !== $menu->id) {
            return response()->json(['status' => 'error', 'message' => 'Invalid menu item'], 403);
        }

        // Delete children first
        MenuItem::where('parent_id', $menuItem->id)->delete();
        
        // Delete the item
        $menuItem->delete();

        return response()->json(['status' => 'ok']);
    }

    public function updateStructure(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
        ]);

        DB::transaction(function () use ($data, $menu) {
            $this->updateMenuItems($data['items'], $menu->id, null, 0);
        });

        return response()->json(['status' => 'ok']);
    }

    private function updateMenuItems(array $items, $menuId, $parentId, $position)
    {
        foreach ($items as $itemData) {
            $item = MenuItem::where('id', $itemData['id'])
                ->where('menu_id', $menuId)
                ->first();

            if ($item) {
                $item->update([
                    'parent_id' => $parentId,
                    'position' => $position,
                ]);

                // Update children if they exist
                if (isset($itemData['children']) && is_array($itemData['children']) && count($itemData['children']) > 0) {
                    $this->updateMenuItems($itemData['children'], $menuId, $item->id, 0);
                } else {
                    // Remove any existing children that are no longer in the structure
                    MenuItem::where('parent_id', $item->id)
                        ->whereNotIn('id', collect($itemData['children'] ?? [])->pluck('id'))
                        ->update(['parent_id' => null]);
                }
            }

            $position++;
        }
    }

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
        // Delete menu items first
        $menu->items()->delete();
        
        // Delete translations
        $menu->translations()->delete();
        
        // Delete menu
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully');
    }
}


