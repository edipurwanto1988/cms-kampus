<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'location',
        'position',
        'url',
        'target',
        'is_active',
    ];

    protected $translatable = [
        'name',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('parent_id')->orderBy('position');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(MenuTranslation::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the translated name for the current locale
     */
    public function getTranslatedName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        $translation = $this->translations()->where('locale', $locale)->first();
        
        if ($translation) {
            return $translation->name;
        }
        
        // Fallback to the default name
        return $this->name;
    }

    /**
     * Get the translated URL for the current locale
     */
    public function getTranslatedUrl($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        // For now, return the default URL - can be extended with menu URL translations later
        return $this->url;
    }
}


