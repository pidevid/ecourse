<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteEducation extends Model
{
    use HasFactory;

    protected $table = 'website_educations';

    protected $fillable = [
        'personal_website_id', 'institution', 'degree',
        'field', 'start_year', 'end_year', 'description',
    ];

    public function personalWebsite()
    {
        return $this->belongsTo(PersonalWebsite::class);
    }
}
