<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CmsController;
use Illuminate\Support\Facades\Route;

// Language switching route (must be before locale middleware group)
Route::get('/language/{locale}', [LanguageController::class, 'switchLanguage'])->name('language.switch');

// Admin routes (must come before language-prefixed routes)
Route::get('/admin', function () {
    return redirect()->route('login');
});

// OWASP A04: Insecure Design - Rate limiting for authentication routes
Route::middleware('throttle:5,1')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes with RBAC
Route::middleware(['auth', 'security.headers'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // CKEditor Test Route
    Route::get('/test-ckeditor', function () {
        return view('test_ckeditor');
    })->name('test.ckeditor');
    
    // User Management Routes
    Route::resource('users', UserController::class);
    
    // Role Management Routes
    Route::resource('roles', RoleController::class);
    
    // Permission Management Routes
    Route::resource('permissions', PermissionController::class);
    
    // Content Management Routes
    Route::resource('pages', PageController::class);

    // Posts & Categories
    Route::resource('posts', PostController::class);
    Route::put('/posts/order', [PostController::class, 'updateOrder'])->name('posts.order');
    Route::put('/posts/{post}/translations', [PostController::class, 'updateTranslations'])->name('posts.translations');
    Route::resource('categories', CategoryController::class);

    // Patnet/Partner CRUD
    Route::resource('partners', PartnerController::class);

    // Lecturer CRUD
    Route::resource('lecturers', LecturerController::class);
    Route::put('/lecturers/{lecturer}/translations', [LecturerController::class, 'updateTranslations'])->name('lecturers.translations');

    // Language CRUD
    Route::resource('languages', LanguageController::class);
    Route::post('/languages/{language}/set-default', [LanguageController::class, 'setDefault'])->name('languages.setDefault');

    // Settings (tabs)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general');
    Route::post('/settings/seo', [SettingController::class, 'updateSeo'])->name('settings.seo');
    Route::post('/settings/social', [SettingController::class, 'updateSocial'])->name('settings.social');
    Route::post('/settings/landing', [SettingController::class, 'updateLandingPage'])->name('settings.landing');

    // Menu management
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/order', [MenuController::class, 'updateOrder'])->name('menus.order');
    Route::put('/menus/{menu}/translations', [MenuController::class, 'updateTranslations'])->name('menus.translations');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::post('/menus/{menu}/items', [MenuController::class, 'addItem'])->name('menus.items.add');
    Route::put('/menus/{menu}/items/{menuItem}', [MenuController::class, 'updateItem'])->name('menus.items.update');
    Route::delete('/menus/{menu}/items/{menuItem}', [MenuController::class, 'deleteItem'])->name('menus.items.delete');
    Route::post('/menus/{menu}/structure', [MenuController::class, 'updateStructure'])->name('menus.structure');

    // CMS Management Routes (outside locale middleware - admin doesn't need language prefix)
    Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
    Route::post('/cms', [CmsController::class, 'update'])->name('cms.update');
});

// Language-prefixed routes for guest pages
Route::prefix('{locale}')->middleware('locale')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('guest.home.localized');
    Route::get('/articles', [GuestController::class, 'articles'])->name('guest.articles.localized');
    Route::get('/articles/{slug}', [GuestController::class, 'articleDetail'])->name('guest.article.detail.localized')->where('slug', '[a-zA-Z0-9\-_]+');
    Route::get('/faculty', [GuestController::class, 'lecturers'])->name('guest.lecturers.localized');
    Route::get('/faculty/{id}', [GuestController::class, 'lecturerDetail'])->name('guest.lecturer.detail.localized')->where('id', '[0-9]+');
    Route::get('/contact', [GuestController::class, 'contact'])->name('guest.contact.localized');
    Route::post('/contact', [GuestController::class, 'contactSubmit'])->name('guest.contact.submit.localized');
    Route::get('/partners', [GuestController::class, 'partners'])->name('guest.partners.localized');
    Route::get('/search', [GuestController::class, 'search'])->name('guest.search.localized');
});

// Guest routes (public website) - without locale prefix (for default language)
Route::middleware('locale')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('guest.home');
    Route::get('/articles', [GuestController::class, 'articles'])->name('guest.articles');
    Route::get('/articles/{slug}', [GuestController::class, 'articleDetail'])->name('guest.article.detail')->where('slug', '[a-zA-Z0-9\-_]+');
    Route::get('/faculty', [GuestController::class, 'lecturers'])->name('guest.lecturers');
    Route::get('/faculty/{id}', [GuestController::class, 'lecturerDetail'])->name('guest.lecturer.detail')->where('id', '[0-9]+');
    Route::get('/contact', [GuestController::class, 'contact'])->name('guest.contact');
    Route::post('/contact', [GuestController::class, 'contactSubmit'])->name('guest.contact.submit');
    Route::get('/partners', [GuestController::class, 'partners'])->name('guest.partners');
    Route::get('/search', [GuestController::class, 'search'])->name('guest.search');
});


// Fallback routes for non-prefixed URLs (redirect to default language) - MUST BE LAST
Route::fallback(function () {
    $defaultLanguage = \App\Models\Language::getDefault();
    $currentPath = request()->path();
    
    // Get all language codes
    $languageCodes = \App\Models\Language::pluck('code')->toArray();
    
    // Exclude admin routes from locale redirection
    $adminRoutes = ['admin', 'cms', 'dashboard', 'login', 'logout', 'users', 'roles', 'permissions',
                   'pages', 'posts', 'categories', 'partners', 'lecturers', 'languages', 'settings', 'menus'];
    
    // Also exclude guest routes from fallback
    $guestRoutes = ['/', 'articles', 'faculty', 'contact', 'partners', 'search'];
    
    $firstSegment = explode('/', $currentPath)[0] ?? '';
    $secondSegment = explode('/', $currentPath)[1] ?? '';
    
    // Log for debugging
    \Log::info("Fallback: Path={$currentPath}, First={$firstSegment}, Second={$secondSegment}");
    
    // If first segment is a language code, let it pass through to locale middleware
    if (in_array($firstSegment, $languageCodes)) {
        // Don't return anything, let Laravel continue processing the route
        // This allows the locale middleware to handle the language-prefixed routes
        return;
    }
    
    if (in_array($firstSegment, $adminRoutes)) {
        // Let admin routes handle themselves
        return response()->view('errors.404', [], 404);
    }
    
    // If this is a guest route, don't handle in fallback
    if (in_array($secondSegment, $guestRoutes)) {
        return;
    }
    
    // Only exclude the language switching route from locale redirection
    if (str_starts_with($currentPath, 'language')) {
        return response()->view('errors.404', [], 404);
    }
    
    // Avoid redirect loops
    if ($currentPath === '' || $currentPath === '/') {
        return redirect()->to('/' . $defaultLanguage->code);
    }
    
    // Don't redirect if the path already starts with a language code
    if (in_array($firstSegment, $languageCodes)) {
        return;
    }
    
    return redirect()->to('/' . $defaultLanguage->code . '/' . $currentPath);
});
