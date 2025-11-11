<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get language from URL segment (e.g., /en/home or /id/home)
        $locale = $request->segment(1);
        
        // Check if the locale is valid
        $validLocale = Language::where('code', $locale)->exists();
        
        if ($validLocale) {
            // Set the locale
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            // If no valid locale in URL, check session
            $sessionLocale = Session::get('locale');
            if ($sessionLocale && Language::where('code', $sessionLocale)->exists()) {
                App::setLocale($sessionLocale);
            } else {
                // Fallback to default language
                $defaultLanguage = Language::getDefault();
                App::setLocale($defaultLanguage->code);
                Session::put('locale', $defaultLanguage->code);
            }
        }

        return $next($request);
    }
}