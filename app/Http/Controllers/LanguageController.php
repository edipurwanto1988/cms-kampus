<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::all();
        return view('languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('languages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:languages,code',
            'name' => 'required|string|max:255',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If this language is set as default, unset all other default languages
        if ($request->boolean('is_default')) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        Language::create([
            'code' => $request->code,
            'name' => $request->name,
            'is_default' => $request->boolean('is_default', false),
        ]);

        return redirect()->route('languages.index')
            ->with('success', 'Language created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language)
    {
        return view('languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Language $language)
    {
      
$validator = Validator::make($request->all(), [
    'code' => [
        'required',
        'string',
        'max:10',
        Rule::unique('languages', 'code')->ignore($language->code, 'code'),
    ],
    'name' => 'required|string|max:255',
    'is_default' => 'boolean',
]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If this language is set as default, unset all other default languages
        if ($request->boolean('is_default')) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        $language->update([
            'code' => $request->code,
            'name' => $request->name,
            'is_default' => $request->boolean('is_default', false),
        ]);

        return redirect()->route('languages.index')
            ->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Language $language)
    {
        // Prevent deletion of default language
        if ($language->is_default) {
            return redirect()->route('languages.index')
                ->with('error', 'Cannot delete the default language.');
        }

        $language->delete();

        return redirect()->route('languages.index')
            ->with('success', 'Language deleted successfully.');
    }

    /**
     * Set a language as default.
     */
    public function setDefault(Language $language)
    {
        // Unset all other default languages
        Language::where('is_default', true)->update(['is_default' => false]);
        
        // Set this language as default
        $language->update(['is_default' => true]);

        return redirect()->route('languages.index')
            ->with('success', 'Language set as default successfully.');
    }

    /**
     * Switch language for the application.
     */
public function switchLanguage($locale)
{
    // Validate the language
    $language = Language::where('code', $locale)->first();
    if (!$language) {
        return redirect()->back()->with('error', 'Invalid language selected.');
    }
    
    // Set the language in session
    session(['locale' => $locale]);
    
    // Get the current path
    $currentPath = request()->path();
    $segments = explode('/', $currentPath);
    
    // Remove ALL language prefixes from the beginning (handles the infinite loop case)
    while (count($segments) > 0 && Language::where('code', $segments[0])->exists()) {
        array_shift($segments);
    }
    
    // Handle special case for language switching route
    if (count($segments) > 0 && $segments[0] === 'language') {
        // If we're on the language switching route, redirect to home
        return redirect('/' . $locale);
    }
    
    // Check if this is an admin route
    $adminRoutes = ['admin', 'cms', 'dashboard', 'login', 'logout', 'users', 'roles', 'permissions',
        'pages', 'posts', 'categories', 'partners', 'lecturers', 'languages', 'settings', 'menus'];
    
    if (count($segments) > 0 && in_array($segments[0], $adminRoutes)) {
        // For admin routes, redirect without language prefix
        $newPath = '/' . implode('/', $segments);
    } else {
        // For public routes, add language prefix
        $newPath = '/' . $locale;
        if (!empty($segments)) {
            $newPath .= '/' . implode('/', $segments);
        }
    }
    
    return redirect($newPath);
}
}