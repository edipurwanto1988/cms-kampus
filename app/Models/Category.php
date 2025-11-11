<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'parent_id',
        'image_media_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation(): HasMany
    {
        return $this->translations()->where('locale', app()->getLocale());
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function imageMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_media_id');
    }

    /**
     * Get the name attribute from translation.
     */
    public function getNameAttribute()
    {
        return $this->getTranslatedAttribute('name');
    }

    /**
     * Get the slug attribute from translation.
     */
    public function getSlugAttribute()
    {
        return $this->getTranslatedAttribute('slug');
    }

    /**
     * Get the description attribute from translation.
     */
    public function getDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('description');
    }
}


