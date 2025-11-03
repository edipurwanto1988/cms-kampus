<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_name',
        'published_at',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the translations for the page.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PageTranslation::class);
    }

    /**
     * Get the translation for the current locale.
     */
    public function translation(): HasMany
    {
        return $this->translations()->where('locale', app()->getLocale());
    }

    /**
     * Get the English translation (fallback).
     */
    public function englishTranslation(): HasMany
    {
        return $this->translations()->where('locale', 'en');
    }

    /**
     * Get the user who created the page.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the page.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the title attribute from translation.
     */
    public function getTitleAttribute()
    {
        $translation = $this->translation()->first();
        if ($translation) {
            return $translation->title;
        }
        
        // Fallback to English
        $englishTranslation = $this->englishTranslation()->first();
        return $englishTranslation ? $englishTranslation->title : 'Untitled';
    }

    /**
     * Get the slug attribute from translation.
     */
    public function getSlugAttribute()
    {
        $translation = $this->translation()->first();
        if ($translation) {
            return $translation->slug;
        }
        
        // Fallback to English
        $englishTranslation = $this->englishTranslation()->first();
        return $englishTranslation ? $englishTranslation->slug : 'untitled';
    }

    /**
     * Get the content attribute from translation.
     */
    public function getContentAttribute()
    {
        $translation = $this->translation()->first();
        if ($translation) {
            return $translation->content_html;
        }
        
        // Fallback to English
        $englishTranslation = $this->englishTranslation()->first();
        return $englishTranslation ? $englishTranslation->content_html : '';
    }

    /**
     * Get the excerpt attribute from translation.
     */
    public function getExcerptAttribute()
    {
        $translation = $this->translation()->first();
        if ($translation) {
            return $translation->excerpt;
        }
        
        // Fallback to English
        $englishTranslation = $this->englishTranslation()->first();
        return $englishTranslation ? $englishTranslation->excerpt : '';
    }

    /**
     * Get the meta title attribute from translation.
     */
    public function getMetaTitleAttribute()
    {
        $translation = $this->translation()->first();
        if ($translation) {
            return $translation->meta_title;
        }
        
        // Fallback to English
        $englishTranslation = $this->englishTranslation()->first();
        return $englishTranslation ? $englishTranslation->meta_title : '';
    }

    /**
     * Get the meta description attribute from translation.
     */
    public function getMetaDescriptionAttribute()
    {
        $translation = $this->translation()->first();
        if ($translation) {
            return $translation->meta_description;
        }
        
        // Fallback to English
        $englishTranslation = $this->englishTranslation()->first();
        return $englishTranslation ? $englishTranslation->meta_description : '';
    }

    /**
     * Scope a query to only include active pages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include published pages.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Check if the page is published.
     */
    public function isPublished(): bool
    {
        return $this->is_active && $this->published_at && $this->published_at->isPast();
    }

    /**
     * Get the status label for the page.
     */
    public function getStatusLabel(): string
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if (!$this->published_at) {
            return 'Draft';
        }
        
        if ($this->published_at->isFuture()) {
            return 'Scheduled';
        }
        
        return 'Published';
    }

    /**
     * Get the status color for the page.
     */
    public function getStatusColor(): string
    {
        if (!$this->is_active) {
            return 'gray';
        }
        
        if (!$this->published_at) {
            return 'yellow';
        }
        
        if ($this->published_at->isFuture()) {
            return 'blue';
        }
        
        return 'green';
    }
}