<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasTranslations;

class Lecturer extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'NUPTK',
        'nip',
        'email',
        'phone',
        'photo_media_id',
        'dept_id',
        'position_title',
        'expertise',
        'scholar_url',
        'researchgate_url',
        'linkedin_url',
        'featured',
        'is_active',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(LecturerTranslation::class);
    }

    public function translation($locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }

        return $this->translations()->where('locale', $locale)->first();
    }

    public function getTranslatedAttribute($attribute, $locale = null)
    {
        $translation = $this->translation($locale);
        
        if ($translation && isset($translation->{$attribute})) {
            return $translation->{$attribute};
        }

        // Fallback to first available translation
        $firstTranslation = $this->translations->first();
        if ($firstTranslation && isset($firstTranslation->{$attribute})) {
            return $firstTranslation->{$attribute};
        }

        return null;
    }

    public function getFullNameAttribute()
    {
        return $this->getTranslatedAttribute('full_name');
    }

    public function getBioHtmlAttribute()
    {
        return $this->getTranslatedAttribute('bio_html');
    }

    public function getResearchInterestsAttribute()
    {
        return $this->getTranslatedAttribute('research_interests');
    }

    public function getAchievementAttribute()
    {
        return $this->getTranslatedAttribute('achievement');
    }

    public function photo()
    {
        return $this->belongsTo(Media::class, 'photo_media_id');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && $this->photo->path) {
            return Storage::url($this->photo->path);
        }

        // Default placeholder image
        return 'https://via.placeholder.com/300x300/004A99/FFFFFF?text=' . urlencode(substr($this->full_name ?? '?', 0, 1));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByDepartment($query, $deptId)
    {
        return $query->where('dept_id', $deptId);
    }
}