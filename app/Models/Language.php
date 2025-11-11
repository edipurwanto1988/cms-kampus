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

    protected $primaryKey = 'code';   // ✅ Use 'code' as primary key
    public $incrementing = false;     // ✅ Because 'code' is not an integer
    protected $keyType = 'string';    // ✅ Ensure Eloquent treats it as a string

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function getRouteKeyName()
{
    return 'code';
}

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