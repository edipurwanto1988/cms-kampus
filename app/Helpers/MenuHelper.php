<?php

use App\Models\Menu;
use App\Models\MenuItem;

if (!function_exists('renderGuestMenu')) {
    /**
     * Render menu for guest layout
     *
     * @param string $location Menu location (e.g., 'header', 'footer')
     * @param string $locale Locale code
     * @return string HTML output
     */
    function renderGuestMenu($location = 'header', $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        // Get menus by location (position)
        $menus = Menu::where('location', $location)
            ->where('is_active', true)
            ->with('translations')
            ->ordered()
            ->get();
        
        if ($menus->isEmpty()) {
            return '';
        }
        
        $html = '';
        
        foreach ($menus as $menu) {
            $html .= renderMenu($menu, $locale);
        }
        
        return $html;
    }
}

if (!function_exists('renderMenuItem')) {
    /**
     * Render single menu item with its children
     *
     * @param MenuItem $item
     * @param string $locale
     * @param int $level
     * @return string HTML output
     */
    function renderMenu($menu, $locale)
    {
        $title = $menu->getTranslatedName($locale);
        $url = $menu->getTranslatedUrl($locale);
        $target = $menu->target ?? '_self';
        
        // Check if current route matches this menu
        $isActive = false;
        $currentRoute = request()->route()->getName();
        $currentPath = request()->path();
        
        // Simple URL matching for active state
        if ($url && str_contains($currentPath, ltrim($url, '/'))) {
            $isActive = true;
        }
        
        $activeClass = $isActive ? 'text-is-teal' : 'text-is-charcoal';
        $hoverClass = 'hover:text-is-teal transition-colors';
        
        $html = '<a class="' . $activeClass . ' ' . $hoverClass . ' text-sm font-medium leading-normal" href="' . $url . '"';
        
        if ($target === '_blank') {
            $html .= ' target="_blank" rel="noopener noreferrer"';
        }
        
        $html .= '>' . $title . '</a>';
        
        return $html;
    }
}

if (!function_exists('renderGuestFooterMenu')) {
    /**
     * Render footer menu for guest layout
     *
     * @param string $location Menu location (e.g., 'footer')
     * @param string $locale Locale code
     * @return string HTML output
     */
    function renderGuestFooterMenu($location = 'footer', $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        // Get menus by location
        $menus = Menu::where('location', $location)
            ->where('is_active', true)
            ->with('translations')
            ->ordered()
            ->get();
        
        if ($menus->isEmpty()) {
            return '';
        }
        
        $html = '';
        
        foreach ($menus as $menu) {
            $html .= renderFooterMenu($menu, $locale);
        }
        
        return $html;
    }
}

if (!function_exists('renderFooterMenuItem')) {
    /**
     * Render single footer menu item
     *
     * @param MenuItem $item
     * @param string $locale
     * @return string HTML output
     */
    function renderFooterMenu($menu, $locale)
    {
        $title = $menu->getTranslatedName($locale);
        $url = $menu->getTranslatedUrl($locale);
        $target = $menu->target ?? '_self';
        
        $html = '<li><a class="text-slate-300 hover:text-white transition-colors" href="' . $url . '"';
        
        if ($target === '_blank') {
            $html .= ' target="_blank" rel="noopener noreferrer"';
        }
        
        $html .= '>' . $title . '</a></li>';
        
        return $html;
    }
}