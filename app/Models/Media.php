<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'alt_text',
        'mime_type',
    ];

    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }
}


