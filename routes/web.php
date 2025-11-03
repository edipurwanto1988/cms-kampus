<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
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
});
