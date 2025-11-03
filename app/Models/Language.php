<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the default language.
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first() ?: static::where('code', 'id')->first();
    }

    /**
     * Get all languages ordered with default first.
     */
    public static function getAllOrdered()
    {
        return static::orderBy('is_default', 'desc')->orderBy('name')->get();
    }
}