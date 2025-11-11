<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_media_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_media_id');
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return $this->logo->url;
        }

        // Default placeholder image
        return 'https://via.placeholder.com/300x300/004A79D/FFFFFF?text=' . strtoupper(substr($this->name ?? '?', 0, 1)) . ')';
    }
}


