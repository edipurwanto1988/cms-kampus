<?php

namespace App\Helpers;

use App\Models\Setting;

class TranslationHelper
{
    /**
     * Get translated UI text
     */
    public static function uiTrans($key, $default = '')
    {
        static $uiSettings = null;
        
        if ($uiSettings === null) {
            $uiSettings = Setting::with(['values' => function ($query) {
                $query->whereNull('locale')->latest();
            }])->where('group_name', 'ui')->get()->keyBy('key_name');
        }
        
        return self::getTranslatedSettingValue($uiSettings, $key, $default);
    }
    
    /**
     * Get translated setting value with fallback logic
     */
    private static function getTranslatedSettingValue($settings, $key, $default = '')
    {
        if (!$settings->has($key)) {
            return $default;
        }
        
        $setting = $settings[$key];
        $locale = app()->getLocale();
        
        // Try to find translated value first
        $translatedValue = $setting->values->firstWhere('locale', $locale);
        if ($translatedValue) {
            return $translatedValue->value_text;
        }
        
        // Fallback to null locale (default)
        $defaultValue = $setting->values->firstWhere('locale', null);
        if ($defaultValue) {
            return $defaultValue->value_text;
        }
        
        // Final fallback
        return $setting->values->first()->value_text ?? $default;
    }
    
    /**
     * Get general setting value
     */
    public static function getGuestSettingValue($settings, $key, $default = '')
    {
        return $settings->has($key) ? $settings[$key]->values->first()->value_text ?? $default : $default;
    }
}