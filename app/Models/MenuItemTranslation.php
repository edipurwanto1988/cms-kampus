<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItemTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'locale',
        'label',
        'slug_override',
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

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}