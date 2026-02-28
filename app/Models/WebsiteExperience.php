<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_website_id', 'company', 'position',
        'start_date', 'end_date', 'is_current', 'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
    ];

    public function personalWebsite()
    {
        return $this->belongsTo(PersonalWebsite::class);
    }
}
