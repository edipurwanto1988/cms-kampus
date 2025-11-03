<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageTranslation;
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
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'locale' => 'required|string|max:10',
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

        // Create the translation
        PageTranslation::create([
            'page_id' => $page->id,
            'locale' => $request->locale,
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content_html' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'translated_at' => now(),
        ]);

        return redirect()->route('pages.index')
            ->with('success', 'Page created successfully.');
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
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'locale' => 'required|string|max:10',
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

        // Update or create the translation
        $translation = $page->translations()->where('locale', $request->locale)->first();
        
        if ($translation) {
            $translation->update([
                'title' => $request->title,
                'slug' => $request->slug ?: Str::slug($request->title),
                'excerpt' => $request->excerpt,
                'content_html' => $request->content,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'translated_at' => now(),
            ]);
        } else {
            PageTranslation::create([
                'page_id' => $page->id,
                'locale' => $request->locale,
                'title' => $request->title,
                'slug' => $request->slug ?: Str::slug($request->title),
                'excerpt' => $request->excerpt,
                'content_html' => $request->content,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'translated_at' => now(),
            ]);
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page updated successfully.');
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