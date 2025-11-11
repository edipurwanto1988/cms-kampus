<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'locale',
        'name',
        'slug',
        'description',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}


