<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Post;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Setting;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        
        $featuredLecturers = Lecturer::active()->featured()
            ->with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'photo'])
            ->limit(4)
            ->get();
            
        $latestPosts = Post::with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'category'])
            ->where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();
            
        $partners = Partner::active()->get();
        
        // Load landing page settings with translations
        $landingSettings = Setting::with(['values' => function ($query) use ($locale) {
            $query->where(function($q) use ($locale) {
                $q->where('locale', $locale)
                  ->orWhereNull('locale');
            })->latest();
        }])->where('group_name', 'landing')->get()->keyBy('key_name');
        
        // Load SEO settings with translations
        $seoSettings = Setting::with(['values' => function ($query) use ($locale) {
            $query->where(function($q) use ($locale) {
                $q->where('locale', $locale)
                  ->orWhereNull('locale');
            })->latest();
        }])->where('group_name', 'seo')->get()->keyBy('key_name');
        
        // Load general settings with translations
        $generalSettings = Setting::with(['values' => function ($query) use ($locale) {
            $query->where(function($q) use ($locale) {
                $q->where('locale', $locale)
                  ->orWhereNull('locale');
            })->latest();
        }])->where('group_name', 'general')->get()->keyBy('key_name');
        
        // Load social settings with translations
        $socialSettings = Setting::with(['values' => function ($query) use ($locale) {
            $query->where(function($q) use ($locale) {
                $q->where('locale', $locale)
                  ->orWhereNull('locale');
            })->latest();
        }])->where('group_name', 'social')->get()->keyBy('key_name');

        return view('guest.index_cms_website', compact('featuredLecturers', 'latestPosts', 'partners', 'landingSettings', 'seoSettings', 'generalSettings', 'socialSettings'));
    }

    public function articles()
    {
        
        $locale = app()->getLocale();
        
        $posts = Post::with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'category'])
            ->where('is_active', true)
            ->latest()
            ->paginate(9);

        $categories = Category::with(['translations' => function($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();

        return view('guest.artikel_semua', compact('posts', 'categories'));
    }

    public function articleDetail($a, $slug)
    {
        
        $locale = app()->getLocale();
        
        // Debug: Log the slug and locale
        \Log::info("Looking for article with slug: {$slug} in locale: {$locale}");
        \Log::info("Request path: " . request()->path());
        \Log::info("All segments: " . json_encode(request()->segments()));
        
        // First try to find the post with the slug in the current locale
        $post = Post::with(['translations', 'category.translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->whereHas('translations', function($query) use ($slug, $locale) {
                $query->where('locale', $locale)
                      ->where('slug', $slug);
            })
            ->where('is_active', true)
            ->first();

        \Log::info("First attempt result: " . ($post ? "Found post ID: {$post->id}" : "Not found"));

        // If not found in current locale, try to find the post with slug in any locale
        if (!$post) {
            $post = Post::with(['translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                }, 'category.translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                }])
                ->whereHas('translations', function($query) use ($slug) {
                    $query->where('slug', $slug);
                })
                ->where('is_active', true)
                ->first();
        }

        \Log::info("Second attempt result: " . ($post ? "Found post ID: {$post->id}" : "Not found"));

        // If still not found, try to find by title as fallback
        if (!$post) {
            $post = Post::with(['translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                }, 'category.translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                }])
                ->whereHas('translations', function($query) use ($slug) {
                    $query->where('title', 'like', '%' . str_replace('-', ' ', $slug) . '%');
                })
                ->where('is_active', true)
                ->first();
        }

        \Log::info("Third attempt result: " . ($post ? "Found post ID: {$post->id}" : "Not found"));

        // If still not found, return 404
        if (!$post) {
            \Log::info("Article not found, returning 404");
            abort(404, 'Article not found');
        }

        $relatedPosts = Post::with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'category.translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('is_active', true)
            ->limit(3)
            ->get();

        return view('guest.artikel_detail', compact('post', 'relatedPosts'));
    }

    public function lecturers()
    {
        $locale = app()->getLocale();
        
        $lecturers = Lecturer::active()
            ->with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'photo'])
            ->latest()
            ->paginate(12);

        return view('guest.profil_dosen', compact('lecturers'));
    }

    public function lecturerDetail($id)
    {
        $locale = app()->getLocale();
        
        $lecturer = Lecturer::with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'photo'])
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedLecturers = Lecturer::active()
            ->where('dept_id', $lecturer->dept_id)
            ->where('id', '!=', $lecturer->id)
            ->with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'photo'])
            ->limit(4)
            ->get();

        return view('guest.profil_dosen_detail', compact('lecturer', 'relatedLecturers'));
    }

    public function contact()
    {
        $locale = app()->getLocale();
        
        // Load general settings with translations
        $generalSettings = Setting::with(['values' => function ($query) use ($locale) {
            $query->where(function($q) use ($locale) {
                $q->where('locale', $locale)
                  ->orWhereNull('locale');
            })->latest();
        }])->where('group_name', 'general')->get()->keyBy('key_name');
        
        return view('guest.contact', compact('generalSettings'));
    }

    public function partners()
    {
        $partners = Partner::active()->get();
        return view('guest.partners', compact('partners'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Here you would typically save to database or send email
        // For now, just redirect back with success message
        
        return redirect()->route('guest.contact')->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $locale = app()->getLocale();
        
        $posts = Post::with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'category'])
            ->whereHas('translations', function($q) use ($query, $locale) {
                $q->where('locale', $locale)
                  ->where(function($subQuery) use ($query) {
                      $subQuery->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                  });
            })
            ->where('is_active', true)
            ->latest()
            ->paginate(9);

        $lecturers = Lecturer::with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'photo'])
            ->whereHas('translations', function($q) use ($query, $locale) {
                $q->where('locale', $locale)
                  ->where(function($subQuery) use ($query) {
                      $subQuery->where('full_name', 'like', "%{$query}%")
                        ->orWhere('bio_html', 'like', "%{$query}%")
                        ->orWhere('research_interests', 'like', "%{$query}%");
                  });
            })
            ->where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();

        return view('guest.search', compact('posts', 'lecturers', 'query'));
    }
}