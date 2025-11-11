<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTranslation extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'post_id',
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

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}


