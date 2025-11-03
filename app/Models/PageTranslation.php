<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'locale',
        'title',
        'slug',
        'excerpt',
        'content_html',
        'meta_title',
        'meta_description',
        'source_lang',
        'is_machine_translated',
        'mt_provider',
        'mt_confidence',
        'human_reviewed',
        'translated_at',
    ];

    protected $casts = [
        'is_machine_translated' => 'boolean',
        'human_reviewed' => 'boolean',
        'translated_at' => 'datetime',
    ];

    /**
     * Get the page that owns the translation.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Scope a query to only include translations for a specific locale.
     */
    public function scopeForLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope a query to only include machine translated translations.
     */
    public function scopeMachineTranslated($query)
    {
        return $query->where('is_machine_translated', true);
    }

    /**
     * Scope a query to only include human reviewed translations.
     */
    public function scopeHumanReviewed($query)
    {
        return $query->where('human_reviewed', true);
    }
}