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
    ];

    protected $translatable = [
        'name',
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
}


