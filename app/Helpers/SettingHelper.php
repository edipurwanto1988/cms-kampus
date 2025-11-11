<?php

if (!function_exists('getSettingValue')) {
    /**
     * Get setting value from settings collection
     *
     * @param \Illuminate\Support\Collection $settings
     * @param string $key
     * @param string $default
     * @return string
     */
    function getSettingValue($settings, $key, $default = '')
    {
        if (!$settings || !$settings->has($key)) {
            return $default;
        }
        
        $setting = $settings[$key];
        if (!$setting || !$setting->values || $setting->values->isEmpty()) {
            return $default;
        }
        
        $locale = app()->getLocale();
        
        // Try to find locale-specific value first
        $localeValue = $setting->values->firstWhere('locale', $locale);
        if ($localeValue && !empty($localeValue->value_text)) {
            return $localeValue->value_text;
        }
        
        // Fallback to null locale (default)
        $defaultValue = $setting->values->firstWhere('locale', null);
        if ($defaultValue && !empty($defaultValue->value_text)) {
            return $defaultValue->value_text;
        }
        
        // Final fallback to first non-empty value
        foreach ($setting->values as $value) {
            if (!empty($value->value_text)) {
                return $value->value_text;
            }
        }
        
        return $default;
    }
}

if (!function_exists('guestSetting')) {
    /**
     * Get setting value for guest pages (alias for getSettingValue)
     *
     * @param \Illuminate\Support\Collection $settings
     * @param string $key
     * @param string $default
     * @return string
     */
    function guestSetting($settings, $key, $default = '')
    {
        return getSettingValue($settings, $key, $default);
    }
}

if (!function_exists('getGuestSettingValue')) {
    /**
     * Get setting value for guest pages (alias for getSettingValue)
     *
     * @param \Illuminate\Support\Collection $settings
     * @param string $key
     * @param string $default
     * @return string
     */
    function getGuestSettingValue($settings, $key, $default = '')
    {
        return getSettingValue($settings, $key, $default);
    }
}

if (!function_exists('uiTrans')) {
    /**
     * Get translated UI text
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    function uiTrans($key, $default = '')
    {
        return \App\Helpers\TranslationHelper::uiTrans($key, $default);
    }
}