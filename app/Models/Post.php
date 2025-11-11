<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'category_id',
        'language_code',
        'cover_media_id',
        'published_at',
        'is_pinned',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function translation($locale = null): HasMany
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }

        return $this->translations()->where('locale', $locale);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function coverMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_code', 'code');
    }

    /**
     * Get the title attribute from translation.
     */
    public function getTitleAttribute()
    {
        return $this->getTranslatedAttribute('title');
    }

    /**
     * Get the slug attribute from translation.
     */
    public function getSlugAttribute()
    {
        return $this->getTranslatedAttribute('slug');
    }

    /**
     * Get the excerpt attribute from translation.
     */
    public function getExcerptAttribute()
    {
        return $this->getTranslatedAttribute('excerpt');
    }

    /**
     * Get the content attribute from translation.
     */
    public function getContentAttribute()
    {
        return $this->getTranslatedAttribute('content_html');
    }

    /**
     * Get the meta title attribute from translation.
     */
    public function getMetaTitleAttribute()
    {
        return $this->getTranslatedAttribute('meta_title');
    }

    /**
     * Get the meta description attribute from translation.
     */
    public function getMetaDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('meta_description');
    }

    /**
     * Get the status color for UI display
     */
    public function getStatusColor()
    {
        if (!$this->is_active) {
            return 'draft';
        }
        
        if ($this->published_at && $this->published_at->isFuture()) {
            return 'scheduled';
        }
        
        if ($this->published_at && $this->published_at->isPast()) {
            return 'published';
        }
        
        return 'draft';
    }

    /**
     * Get the status label for UI display
     */
    public function getStatusLabel()
    {
        if (!$this->is_active) {
            return 'Draft';
        }
        
        if ($this->published_at && $this->published_at->isFuture()) {
            return 'Scheduled';
        }
        
        if ($this->published_at && $this->published_at->isPast()) {
            return 'Published';
        }
        
        return 'Draft';
    }

    /**
     * Get the creator relationship
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}


