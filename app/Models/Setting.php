<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_name',
        'group_name',
        'input_type',
        'is_multilang',
    ];

    protected $casts = [
        'is_multilang' => 'boolean',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(SettingValue::class);
    }
}


