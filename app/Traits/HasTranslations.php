<?php

namespace App\Traits;

use App\Models\Language;

trait HasTranslations
{
    /**
     * Get translation for specific language
     */
    public function getTranslationForLanguage($languageCode)
    {
        return $this->translations()->where('locale', $languageCode)->first();
    }
    
    /**
     * Get all translations organized by language code
     */
    public function getAllTranslations()
    {
        $languages = Language::getAllOrdered();
        $translations = [];
        
        foreach ($languages as $language) {
            $translation = $this->getTranslationForLanguage($language->code);
            $translations[$language->code] = $translation ?: $this->createEmptyTranslation($language->code);
        }
        
        return $translations;
    }
    
    /**
     * Create empty translation for a language
     */
    protected function createEmptyTranslation($languageCode)
    {
        $translationClass = $this->getTranslationClass();
        return new $translationClass(['locale' => $languageCode]);
    }
    
    /**
     * Get translation class name
     */
    protected function getTranslationClass()
    {
        return static::class . 'Translation';
    }
    
    /**
     * Save multiple translations at once
     */
    public function saveTranslations($translationsData)
    {
        foreach ($translationsData as $locale => $data) {
            $translation = $this->getTranslationForLanguage($locale);
            
            if ($translation) {
                $translation->update($data);
            } else {
                $translationClass = $this->getTranslationClass();
                $translation = new $translationClass(array_merge($data, ['locale' => $locale]));
                $this->translations()->save($translation);
            }
        }
    }
    
    /**
     * Get translated attribute with fallback
     */
    public function getTranslatedAttribute($attribute, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = $this->getTranslationForLanguage($locale);
        if ($translation && !empty($translation->$attribute)) {
            return $translation->$attribute;
        }
        
        // Fallback to default language
        $defaultLanguage = Language::getDefault();
        if ($defaultLanguage && $defaultLanguage->code !== $locale) {
            $defaultTranslation = $this->getTranslationForLanguage($defaultLanguage->code);
            if ($defaultTranslation && !empty($defaultTranslation->$attribute)) {
                return $defaultTranslation->$attribute;
            }
        }
        
        return null;
    }
}