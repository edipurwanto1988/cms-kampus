<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_id',
        'locale',
        'value_text',
        'value_json',
    ];

    protected $casts = [
        'value_json' => 'array',
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class);
    }
}


