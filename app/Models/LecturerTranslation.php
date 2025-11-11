<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'locale',
        'full_name',
        'bio_html',
        'research_interests',
        'achievement',
    ];

    public $timestamps = false;

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}