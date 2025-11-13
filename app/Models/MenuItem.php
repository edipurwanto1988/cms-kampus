<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'position',
        'target',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('position');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(MenuItemTranslation::class);
    }

    /**
     * Get the translated title for the current locale
     */
    public function getTranslatedTitle($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        $translation = $this->translations()->where('locale', $locale)->first();
        
        if ($translation) {
            return $translation->label;
        }
        
        // Fallback to the default title
        return $this->title;
    }

    /**
     * Get the translated URL for the current locale
     */
    public function getTranslatedUrl($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        $translation = $this->translations()->where('locale', $locale)->first();
        
        if ($translation && $translation->slug_override) {
            return $translation->slug_override;
        }
        
        // Fallback to the default URL
        return $this->url;
    }
}


